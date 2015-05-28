<?php

namespace app\models;

use \app\models\Expert;
use \app\models\Solution;
use \app\models\Historycomment;
use \app\extensions\helper\Avatar as AvatarHelper;
use \app\extensions\helper\Brief;
use \app\extensions\helper\Solution as SolutionHelper;
use \app\extensions\helper\NameInflector;
use \lithium\storage\Session;
use app\extensions\mailers\CommentsMailer;
use app\extensions\storage\Rcache;

class Comment extends AppModel {

    public static $result = '';
    public static $level = 0;

	public $belongsTo = array('Pitch', 'User');

	public static function __init() {
		parent::__init();
        self::applyFilter('save', function($self, $params, $chain){
            if(($params['entity']->data()) && (isset($params['entity']->id) && $params['entity']->id > 0) && (isset($params['entity']->text))) {
                $comment = $params['entity'];
                $cacheKey = 'commentsraw_' . $comment->pitch_id;
                Rcache::delete($cacheKey);
                if($original = Comment::first($comment->id)) {
                    $comment = $params['entity'];
                    $historyArchive = $comment->history;
                    if($historyArchive == '') {
                        $history = array();
                    }else {
                        $history = unserialize($historyArchive);
                    }
                    $history[] = array('date' => date('Y-m-d H:i:s'), 'text' => $original->text);
                    $params['entity']->history = serialize($history);
                }
            }
            return $chain->next($self, $params, $chain);
        });
		self::applyFilter('createComment', function($self, $params, $chain){
            $params['solution_id'] = 0;
            $change = array(4, 5);
            $admin = 108;
            if(in_array($params['user_id'], $change)) {
                $params['user_id'] = $admin;
            }
            if(false == $self::isCommentRepeat($params['user_id'], $params['pitch_id'], $params['text'])) {
                $params['error'] = 'repeated';
                return $params;
            }
            if($num = $self::parseComment($params['text'])) {
                if($solutionId = Solution::getSolutionIdFromOrder($params['pitch_id'], $num)) {
                    $params['solution_id'] = $solutionId;
                }
            }
            if((isset($params['comment_id'])) && (!empty($params['comment_id']))) {
                $repliedComment = Comment::first($params['comment_id']);
                if(($repliedComment) && ($repliedComment->solution_id > 0)) {
                    $params['solution_id'] = $repliedComment->solution_id;
                }
            }

            if(false == $self::checkComment($params['text'])) {
                $params['error'] = 'empty';
                return $params;
            }

            $params = $chain->next($self, $params, $chain);

			if($params) {
				Event::createEvent($params['pitch_id'], 'CommentAdded', $params['user_id'], $params['solution_id'], $params['id']);
			    $cacheKey = 'commentsraw_' . $params['pitch_id'];
                Rcache::delete($cacheKey);
            }
            preg_match_all('@(#\d*).@', $params['text'], $matches);
            $nums = array();
            foreach($matches[1] as $hashtag){
                $nums[] = substr($hashtag, 1);
            }
            $sender = User::first($params['user_id']);
            $admin = User::getAdmin();
            $pitch = Pitch::first($params['pitch_id']);
            // Expert writing
            $experts = unserialize($pitch->{'expert-ids'});
            if($pitch->status > 0 && in_array($params['user_id'], Expert::getPitchExpertUserIds($experts))) {
                $data = array(
                    'pitch' => $pitch,
                    'text' => $params['text'],
                );
                User::sendSpamExpertSpeaking($data);
            }
            if($pitch->status > 0 && $params['user_id'] != $admin) {
                // notify admin
                User::sendAdminNotification($params);
                if((!empty($num)) || ((isset($params['reply_to'])) && ($params['reply_to'] != 0))) {
                    // Отправить комментарий владельцу питча
                    $client = User::first($pitch->user_id);
                    $nameInflector = new nameInflector();
                    $message = 'Дизайнеры больше не могут предлагать решения и оставлять комментарии!';
                    $data = array('pitch_id' => $params['pitch_id'], 'reply_to' => $client->id, 'user_id' => $admin, 'text' => $message, 'public' => (int) $params['public'], 'question_id' => $params['id']);
                    Comment::createComment($data);
                }
                return $params;
            }
            // Если упоминаются номера решения, отправляем комментарии их владельцам
            if(!empty($num) && $pitch->status == 0) {
                $solutions = Solution::all(array('with' => array('User'), 'conditions' => array('pitch_id' => $params['pitch_id'], 'num' => $nums)));
                $emails = array();
                foreach($solutions as $solution) {
                    $emails[$solution->user->email] = $solution;
                }
                foreach($emails as $solution) {
                    $data = $params;
                    $data['solution_id'] = $solution->id;
                    User::sendSpamNewcomment($data);
                }
            }
            // Если коммент написал админ всем пользователям, отправляем уведомление об этом всем участникам питча
            if(($sender->isAdmin == 1) && ($params['solution_id'] == 0) && ((!isset($params['reply_to'])) || ((isset($params['reply_to'])) && $params['reply_to'] == 0))) {
                Task::createNewTask($params['id'], 'newCommentFromAdminNotification');
            }
            // Если ответ какому-то пользователю, отправляем уведомление пользователю
            if((isset($params['reply_to'])) && ($params['reply_to'] != 0) && ($pitch->status == 0)) {
                Task::createNewTask($params['id'], 'newPersonalCommentNotification');
            }
            // Если комментарий владельца питча, запишем в историю для статистики
            if($pitch->user_id == $sender->id) {
                $historyComment = Historycomment::create();
                $historyComment->set($params);
                $historyComment->created = date('Y-m-d H:i:s');
                $historyComment->save();
            }
			return $params;
		});
        self::applyFilter('delete', function($self, $params, $chain){
            if($result = $chain->next($self, $params, $chain)) {
                $record = $params['entity'];
                if($event = Event::first(array('conditions' => array('comment_id' => $record->id)))) {
                    $event->delete();
                }
                if ($childComment = Comment::first(array('conditions' => array('question_id' => $record->id)))) {
                    $childComment->delete();
                }
            }
        });
        self::applyFilter('find', function($self, $params, $chain){
            $result = $chain->next($self, $params, $chain);
            if((!isset($params['options']['nofilters'])) && (is_object($result))) {
                $addMentionLink = function($record) {
                    if(isset($record->text)) {
                        $record->text = nl2br($record->text);
                        $pitchId = $record->pitch_id;
                        if (preg_match_all('/(#\d+)/', $record->text, $matches)) {
                            $solutionsHere = array_unique($matches[1]);
                            usort($solutionsHere, function ($s1, $s2) {
                                if (strlen($s1['name']) < strlen($s2['name'])) {
                                    return 1;
                                } elseif (strlen($s1['name']) > strlen($s2['name'])) {
                                    return -1;
                                } else {
                                    return 0;
                                }
                            });
                            foreach ($solutionsHere as $solutionNum) {
                                $num = substr($solutionNum, 1);
                                $solution = Solution::first(array('conditions' => array('pitch_id' => $pitchId, 'num' => $num), 'with' => array('Pitch')));
                                if (isset($solution)) {
                                    $solutionHelper = new SolutionHelper;
                                    $record->text = preg_replace("/($solutionNum\D)([\W]*)/", '<a href="http://www.godesigner.ru/pitches/viewsolution/' . $solution->id . '" target="_blank" class="solution-link hoverimage" data-comment-to="$1" data-thumbnail="' . $solutionHelper->renderImageUrlRights($solution, 'solution_galleryLargeSize', $solution->pitch) . '">$1</a>$2', $record->text);
                                } else {
                                    $record->text = preg_replace("/($solutionNum\D)([\W]*)/", '<a href="#" target="_blank" class="solution-link hoverimage">$1</a>$2', $record->text);
                                }
                            }
                        }
                        $record->text = preg_replace('/@([^@]*? [^@]\.)(,?)/u', '<a href="#" class="mention-link" data-comment-to="$1">@$1$2</a>', strip_tags($record->text, '<br><a>'));

                    }
                    return $record;
                };
                $addSolutionNumLinkIfNotExists = function($record) {
                    if((isset($record->text)) && (!preg_match('/^(#|@)/', $record->text)) && ($record->solution_id > 0)) {
                        $solution = Solution::first($record->solution_id);
                        if($solution) {
                            $prependText = '<a href="#" class="solution-link" data-comment-to="#' . $solution->num . '">#' . $solution->num . '</a>, ';
                            $record->text = $prependText . $record->text;
                        }
                    }
                    return $record;
                };
                $addHyperlink = function($record){
                    if(isset($record->text)) {
                        $regex = '!(^|\s)([-a-zA-Z0-9:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?)!';
                        $regex2 = '!(^|\s)([-a-zA-Z0-9:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?)!';
                        while(preg_match($regex, $record->text)) {
                            $record->text = preg_replace($regex2, '$1<a href="$2" target="_blank">$2</a>', $record->text);

                        }
                        $record->text = preg_replace('#href="(?!(http|https)://)(.*)"#', 'href="http://$2"', $record->text);
                    }
                    return $record;
                };
                $addOriginalText = function($record){
                    if(isset($record->text)) {
                        $record->originalText = $record->text;
                    }
                    return $record;
                };
                $stripEmail = function($record) {
                    if (isset($record->text)) {
                        $briefHelper = new Brief;
                        $record->text = $briefHelper->stripemail($record->text);
                        $record->originalText = $briefHelper->stripemail($record->originalText);
                    }
                    return $record;
                };
                if(get_class($result) == 'lithium\data\entity\Record') {
                    //$result = $addSolutionNumLinkIfNotExists($result);
                    $result = $addOriginalText($result);
                    $result = $addMentionLink($result);
                    $result = $addHyperlink($result);
                    $result = $stripEmail($result);

                }else {
                    foreach($result as $foundItem) {
                        //$foundItem = $addSolutionNumLinkIfNotExists($foundItem);
                        $foundItem = $addOriginalText($foundItem);
                        $foundItem = $addMentionLink($foundItem);
                        $foundItem = $addHyperlink($foundItem);
                        $foundItem = $stripEmail($foundItem);

                    }
                }
            }
            return $result;
        });
	}

    public static function filterComments($currentNum, $allcomments) {
        $solutionComments = new \lithium\util\Collection();
        foreach($allcomments as $comment) {
            if(preg_match('@#' . $currentNum . '\D@', $comment->text)) {
                $solutionComments->append($comment);
            }
        }
        return $solutionComments;
    }

    public static function filterCommentsTree($commentsRaw, $pitchUserId) {
        self::$result = new \lithium\util\Collection();
        $commentsFiltered = self::fetchCommentsTree($commentsRaw);
        $currentUser = Session::read('user');
        $isUserClient = ($currentUser['id'] == $pitchUserId) ? true : false;
        $isUserAdmin = (($currentUser['isAdmin'] == 1) || User::checkRole('admin')) ? true : false;

        // Set Publicity
        foreach ($commentsFiltered as $comment) {
            // Parent
            if ($comment->isChild == 1 && $comment->public == 1) {
                $question_id = $comment->question_id;
                $parent = $commentsFiltered->find(function($comment) use ($question_id) {
                    if ($comment->id == $question_id) {
                        return true;
                    }
                    return false;
                });
                $parent->current()->public = 1;
            }
            // Child
            if ($comment->user_id == $pitchUserId) {
                $comment_id = $comment->id;
                $child = $commentsFiltered->find(function($comm) use ($comment_id) {
                    if ($comm->question_id == $comment_id) {
                        return true;
                    }
                    return false;
                });
                if (count($child) > 0) {
                    $child->current()->public = $comment->public;
                }
            }
        }

        if ((true == $isUserClient) || (true == $isUserAdmin)) {
            foreach ($commentsFiltered as $comment) {
                $comment->needAnswer = '';
                if (($comment->user->isAdmin != 1) && ($comment->user->id != $pitchUserId) && (!in_array($comment->user->id, User::$admins))) {
                    $comment->needAnswer = 1;
                }
                if (true == $isUserAdmin) {
                    $comment->needAnswer = 1;
                }
            }
        } else {
            foreach ($commentsFiltered as $comment) {
                $designer = false;
                $comment->needAnswer = '';
                if (($comment->solution_id != 0) && ($solution = Solution::first(array('fields' => array('user_id'), 'conditions' => array( 'id' => $comment->solution_id))))) {
                    $designer = $solution->user_id;
                }
                if (($comment->user_id != $currentUser['id']) && (($designer === $currentUser['id']) || ($comment->reply_to === $currentUser['id']))) {
                    $comment->needAnswer = 1;
                }
                if (($comment->public == 0) && ($comment->user_id != $currentUser['id']) && ($designer !== $currentUser['id']) && ($comment->reply_to !== $currentUser['id'])) {
                    $comment->text = '__must be unset__';
                }
            }
        }

        // Delete marked forbidden comment
        $commentsFiltered = $commentsFiltered->find(function($comment) {
            if ($comment->text != '__must be unset__') {
                return true;
            }
            return false;
        });

        // Set inconsequent padding
        foreach ($commentsFiltered as $comment) {
            if ($comment->isChild == 1) {
                $question_id = $comment->question_id;
                $emptyParent = $commentsFiltered->find(function($comm) use ($question_id) {
                    if ($comm->id == $question_id) {
                        return true;
                    }
                    return false;
                });
                if (count($emptyParent) == 0) {
                    $comment->isChild = 0;
                }
            }
        }
        return $commentsFiltered;
    }

    function fetchCommentsTree($commentsRaw) {
        self::$level++;
        foreach ($commentsRaw as $comment) {
            $avatarHelper = new AvatarHelper;
            $comment->avatar = $avatarHelper->show($comment->user->data(), false, true);
            if (self::$level > 1) {
                $comment->isChild = 1;
            }
            $children = Comment::all(array(
                'conditions' => array(
                    'question_id' => $comment->id,
                ),
                'with' => array('User'),
                'order' => array('id' => 'desc'),
            ));
            if (count($children) > 0) {
                $comment->hasChild = 1;
                self::$result->append($comment);
                self::fetchCommentsTree($children);
            } else {
                self::$result->append($comment);
            }
        }
        self::$level--;
        if (self::$level == 0) {
            return self::$result;
        }
    }

    public static function addAvatars($comments) {
        $avatarHelper = new AvatarHelper;
        foreach($comments as $comment) {
            $comment->avatar = $avatarHelper->show($comment->user->data(), false, true);
        }
        return $comments;
    }

    public static function addSolutionUrl($comments) {
        foreach($comments as $comment) {
            if($comment->solution_id != 0) {
                $solution = Solution::first($comment->solution_id);
                $comment->solution_url = $solution->images;
            }
        }
        return $comments;
    }

	public static function createComment($data) {
		return static::_filter(__FUNCTION__, $data, function($self, $params) {
            $comment = $self::create();
            if((isset($params['comment_id'])) && ($mentionedComment = $self::first($params['comment_id']))) {
                $params['reply_to'] = $mentionedComment->user_id;
                unset($params['comment_id']);
            }
            if (isset($params['question_id']) && ($mentionedComment = $self::first($params['question_id']))) {
                $params['reply_to'] = $mentionedComment->user_id;
            }
            $comment->set($params);
            $comment->created = date('Y-m-d H:i:s');
            $comment->save();
            $params['id'] = $comment->id;
			return $params;
		});
	}

    public static function parseComment($text) {
        if(preg_match('/^#(\d*),/', $text, $matches)) {
            return $matches[1];
        }else {
            return 0;
        }
    }

    /**
     * Check if the Comment includes real text
     *
     * $text - текст для проверки
     * return boolean
     */
    public static function checkComment($text) {
        $patterns = array(
            '/#\d+,/', // #15,
            '/#\d+ ,/', // #15 ,
            '/#\d+/', // #15
            '/@[\p{L}]+ [\p{L}]{1}\.,/', // @Дмитрий Н.,
            '/@[\p{L}]+ [\p{L}]{1}\. ,/', // @Дмитрий Н. ,
            '/@[\p{L}]+ [\p{L}]{1}/', // @Дмитрий Н
        );
        $text = preg_replace($patterns, '', $text);
        return (bool) mb_strlen($text, 'UTF-8');
    }

    public static function isCommentRepeat($user_id, $pitch_id, $currentText) {
        if (User::first(array('conditions' => array('id' => $user_id, 'isAdmin' => 1)))) {
            return true;
        }
        $previousComment = Comment::first(array(
            'conditions' => array(
                'user_id' => $user_id,
                'pitch_id' => $pitch_id,
            ),
            'order' => array(
                'created' => 'DESC',
            ),
        ));
        if ($previousComment && $previousComment->originalText == $currentText) {
            return false;
        }
        return true;
    }
}
