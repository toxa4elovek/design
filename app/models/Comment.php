<?php

namespace app\models;

use \app\models\Solution;
use \app\models\Historycomment;

class Comment extends \app\models\AppModel {

	public $belongsTo = array('Pitch', 'User');

	public static function __init() {
		parent::__init();
        self::applyFilter('save', function($self, $params, $chain){
            if(($params['entity']->data()) && (isset($params['entity']->id) && $params['entity'] > 0) && (isset($params['entity']->text))) {
                $comment = $params['entity'];
                $original = Comment::first($comment->id);
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
            return $chain->next($self, $params, $chain);
        });
		self::applyFilter('createComment', function($self, $params, $chain){
            $params['solution_id'] = 0;
            $change = array(4, 5);
            $admin = 108;
            if(in_array($params['user_id'], $change)) {
                $params['user_id'] = $admin;
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
                return $params;
            }

            $params = $chain->next($self, $params, $chain);

			if($params) {
				Event::createEvent($params['pitch_id'], 'CommentAdded', $params['user_id'], $params['solution_id'], $params['id']);
			}
            preg_match_all('@(#\d*).@', $params['text'], $matches);
            $nums = array();
            foreach($matches[1] as $hashtag){
                $nums[] = substr($hashtag, 1);
            }
            if(!empty($num)) {
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
            $sender = User::first($params['user_id']);
            $pitch = Pitch::first($params['pitch_id']);
            if($pitch->status > 0) {
                // notify admin
                User::sendAdminNotification($params);
            }
            if(($sender->isAdmin == 1) && ($params['solution_id'] == 0) && ((!isset($params['reply_to'])) || ((isset($params['reply_to'])) && $params['reply_to'] == 0))) {
                User::sendAdminSpamComment($params);
            }
            if((isset($params['reply_to'])) && ($params['reply_to'] != 0)) {
                User::sendPersonalComment($params);
            }
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
            }
        });
        self::applyFilter('find', function($self, $params, $chain){
            $result = $chain->next($self, $params, $chain);
            if(is_object($result)) {
                $addMentionLink = function($record) {
                    if(isset($record->text)) {
                        $record->text = nl2br($record->text);
                        $pitchId = $record->pitch_id;
                        if(preg_match('/(#\d*)/', $record->text, $matches)) {
                            $num = substr($matches[1], 1);
                            $solution = Solution::first(array('conditions' => array('pitch_id' => $pitchId, 'num' => $num)));
                            if(isset($solution)) {
                                $record->text = preg_replace('/(#\d*)/', '<a href="http://www.godesigner.ru/pitches/viewsolution/' . $solution->id . '" target="_blank" class="solution-link hoverimage" data-comment-to="$1">$1</a>$2', $record->text);
                            }else {
                                $record->text = preg_replace('/(#\d*)/', '<a href="#" target="_blank" class="solution-link hoverimage">$1</a>$2', $record->text);
                            }
                        }else {
                            $record->text = preg_replace('/(#\d*)/', '<a href="#" target="_blank" class="solution-link hoverimage" data-comment-to="$1">$1</a>$2', $record->text);
                        }
                        $record->text = preg_replace('/@([^@]* [^@]\.),?/u', '<a href="#" class="mention-link" data-comment-to="$1">@$1</a>', strip_tags($record->text, '<br><a>'));

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
                        $record->text = preg_replace('#href="(?!http://)(.*)"#', 'href="http://$1"', $record->text);
                    }
                    return $record;
                };
                $addOriginalText = function($record){
                    if(isset($record->text)) {
                        $record->originalText = $record->text;
                    }
                    return $record;
                };
                if(get_class($result) == 'lithium\data\entity\Record') {
                    //$result = $addSolutionNumLinkIfNotExists($result);
                    $result = $addOriginalText($result);
                    $result = $addMentionLink($result);
                    $result = $addHyperlink($result);

                }else {
                    foreach($result as $foundItem) {
                        //$foundItem = $addSolutionNumLinkIfNotExists($foundItem);
                        $foundItem = $addOriginalText($foundItem);
                        $foundItem = $addMentionLink($foundItem);
                        $foundItem = $addHyperlink($foundItem);

                    }
                }
            }
            return $result;
        });
	}

    public static function filterComments($currentNum, $allcomments) {
        $solutionComments = array();
        foreach($allcomments as $comment) {
            if(preg_match('@#' . $currentNum . '\D@', $comment->text)) {
                $solutionComments[] = $comment;
            }
        }
        return $solutionComments;
    }

	public static function createComment($data) {
		return static::_filter(__FUNCTION__, $data, function($self, $params) {
		    \lithium\analysis\Logger::write('debug', 'function');
            $comment = $self::create();
            if((isset($params['comment_id'])) && ($mentionedComment = $self::first($params['comment_id']))) {
                $params['reply_to'] = $mentionedComment->user_id;
                unset($params['comment_id']);
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
        $res = preg_replace($patterns, '', $text);
        $res = preg_match('/[\p{L}]+/', $res);
        if ($res == 1) {
            return true;
        }
    }

}