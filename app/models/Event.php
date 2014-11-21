<?php

namespace app\models;

use \app\extensions\helper\NameInflector;
use \app\models\Pitch;
use \app\models\User;
use \app\models\Comment;
use \app\models\Category;
use \app\models\Solution;
use \app\extensions\helper\MoneyFormatter;
use \lithium\storage\Session;

class Event extends \app\models\AppModel {

    public $belongsTo = array('Pitch', 'User', 'Comment', 'Solution');

    public static function __init() {
        parent::__init();
        self::applyFilter('find', function($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if (is_object($result)) {
                $addUpdateText = function($record) {
                    $record->updateText = '';
                    if ($record->type == 'SolutionAdded') {
                        $record->updateText = '';
                    }
                    if ($record->type == 'SolutionPicked') {
                        $moneyFormatter = new MoneyFormatter();
                        $solution = Solution::first($record->solution_id);
                        $record->updateText = '#' . $solution->num . ' стал победителем питча. Поздравляем! Вознаграждение ' . $moneyFormatter->formatMoney($record->pitch->price, array('suffix' => ' Р.-'));
                    }
                    if (($record->type == 'CommentAdded') || ($record->type == 'CommentCreated')) {
                        $commentText = '';
                        if ($record->comment) {
                            $commentText = $record->comment->text;
                        }
                        $record->updateText = $commentText;
                    }
                    return $record;
                };
                $addBindings = function($record) {
                    if ((isset($record->solution_id)) && ($record->solution_id > 0)) {
                        $record->solution = Solution::first(array('with' => array('Pitch'), 'conditions' => array('Solution.id' => $record->solution_id, 'category_id' => array('!=' => 7))));
                        if ($record->type == 'SolutionAdded') {
                            $record->pitchesCount = Pitch::getCountBilledMultiwinner($record->pitch_id);
                            $selectedsolution = false;
                            $nominatedSolutionOfThisPitch = Solution::first(array(
                                        'conditions' => array('nominated' => 1, 'pitch_id' => $record->solution->pitch->id)
                            ));
                            if ($nominatedSolutionOfThisPitch) {
                                $selectedsolution = true;
                            }
                            $record->selectedSolutions = $selectedsolution;
                            $allowLike = 0;
                            if (Session::read('user.id') && (!$like = Like::first('first', array('conditions' => array('solution_id' => $record->solution->id, 'user_id' => Session::read('user.id')))))) {
                                $allowLike = 1;
                            }
                            $record->allowLike = $allowLike;
                        }
                    } else {
                        //$record->solution = Solution::getBestSolution($record->pitch_id);
                        $record->solution = null;
                    }
                    if ($record->solution && ($record->solution->pitch->private == 1) || ($record->solution->pitch->category_id == 7)) {
                        if (($record->user_id != Session::read('user.id')) && ($record->solution->pitch->user_id != Session::read('user.id'))) {
                            ///img/copy-inv.png
                            $record->solution->images['solution_solutionView']['weburl'] = '/img/copy-inv.png';
                            $record->solution->images['solution_gallerySiteSize']['weburl'] = '/img/copy-inv.png';
                            $record->solution->images['solution_galleryLargeSize']['weburl'] = '/img/copy-inv.png';
                            $record->solution->images['solution']['weburl'] = '/img/copy-inv.png';
                            //var_dump($record->solution->images['solution_solutionView']);
                        }
                    }
                    if ((isset($record->pitch_id)) && ($record->pitch_id > 0) && !isset($record->pitch)) {
                        if ($record->pitch = Pitch::first($record->pitch_id)) {
                            $category = Category::first($record->pitch->category_id);
                            $record->pitch->category = $category;
                            $record->pitch->category->lcTitle = mb_strtolower($record->pitch->category->title, 'utf-8');
                        }
                    }
                    if ((isset($record->comment_id)) && ($record->comment_id > 0)) {
                        $record->comment = Comment::first($record->comment_id);
                        $allowLike = 0;
                        if (Session::read('user.id') && (!$like = Like::first('first', array('conditions' => array('solution_id' => $record->solution->id, 'user_id' => Session::read('user.id')))))) {
                            $allowLike = 1;
                        }
                        $record->allowLike = $allowLike;
                    }
                    if ((isset($record->user_id)) && ($record->user_id > 0)) {
                        $record->user = User::first(array('conditions' => array('id' => $record->user_id), 'fields' => array('id', 'first_name', 'last_name', 'isAdmin', 'gender')));
                    }
                    if ($record->type == 'newsAdded') {
                        $news = News::first($record->news_id);
                        $str = strpos($news->tags, '|');
                        if ($str) {
                            $news->tags = substr($news->tags, 0, $str);
                        }
                        $host = parse_url($news->link);
                        $record->host = $host['host'];
                        $record->news = $news;
                    }
                    return $record;
                };
                $addHumanDate = function($record) {
                    if (isset($record->created)) {
                        $record->humanCreated = date('d.m.Y H:i', strtotime($record->created));
                        $record->humanCreatedShort = date('d.m.Y', strtotime($record->created));
                    }
                    return $record;
                };
                $addJSDate = function($record) {
                    if (isset($record->created)) {
                        $record->jsCreated = date('Y/m/d H:i:s', strtotime($record->created));
                    }
                    return $record;
                };
                $addCreator = function($record) {
                    $autoCreators = array('SolutionPicked', 'PitchFinished');
                    if (in_array($record->type, $autoCreators)) {
                        $record->creator = 'Go Designer';
                    } else {
                        if ($record->user) {
                            $nameInflector = new NameInflector();
                            $record->creator = $nameInflector->renderName($record->user->first_name, $record->user->last_name);
                        }
                    }
                    return $record;
                };
                $addHumanType = function($record) {
                    $record->humanType = '';
                    $typesMap = array(
                        'SolutionPicked' => 'Выбран победитель',
                        'CommentAdded' => 'Добавлен комментарий',
                        'CommentCreated' => 'Добавлен комментарий',
                        'PitchFinished' => 'Питч завершён',
                        'SolutionAdded' => 'Добавлено решение',
                        'PitchCreated' => 'Новый питч',
                        'newsAdded' => 'Добавлена новость'
                    );
                    if (isset($typesMap[$record->type])) {
                        $record->humanType = $typesMap[$record->type];
                    }
                    return $record;
                };
                if (get_class($result) == 'lithium\data\entity\Record') {
                    $result = $addBindings($result);
                    $result = $addCreator($result);
                    $result = $addHumanDate($result);
                    $result = $addHumanType($result);
                    $result = $addUpdateText($result);
                    $result = $addJSDate($result);
                } else {
                    foreach ($result as $foundItem) {
                        $foundItem = $addBindings($foundItem);
                        $foundItem = $addHumanDate($foundItem);
                        $foundItem = $addCreator($foundItem);
                        $foundItem = $addHumanType($foundItem);
                        $foundItem = $addUpdateText($foundItem);
                        $foundItem = $addJSDate($foundItem);
                    }
                }
            }
            return $result;
        });
    }

    public static function createEvent($pitchId, $type, $userId, $solutionId = 0, $commentId = 0, $news_id = 0) {
        $newEvent = self::create();
        $newEvent->created = date('Y-m-d H:i:s');
        $newEvent->pitch_id = $pitchId;
        $newEvent->user_id = $userId;
        $newEvent->solution_id = $solutionId;
        $newEvent->comment_id = $commentId;
        $newEvent->type = $type;
        $newEvent->news_id = $news_id;
        return $newEvent->save();
    }

    public function createEventNewsAdded($news_id, $pitch_id, $created) {
        $newEvent = Event::create();
        $newEvent->created = $created;
        $newEvent->pitch_id = $pitch_id;
        $newEvent->type = 'newsAdded';
        $newEvent->news_id = $news_id;
        return $newEvent->save();
    }

    public static function getEvents($pitchIds, $page = 1, $created = null) {
        $eventList = $conditions = array();
        $limit = 14;
        if (isset($created)) {
            $page = 1;
            $limit = 100;
            $conditions = array('created' => array('>' => $created));
        }
        if (!empty($pitchIds)) {
            $events = Event::find('all', array(
                        'conditions' => $conditions + Event::createConditions($pitchIds),
                        'order' => array('created' => 'desc'),
                        'limit' => $limit,
                        'page' => $page
                        )
            );
        } else {
            $events = Event::find('all', array(
                        'conditions' => $conditions + array('type' => 'newsAdded'),
                        'order' => array('created' => 'desc'),
                        'limit' => $limit,
                        'page' => $page
                        )
            );
        }
        $i = 1;
        foreach ($events as $event) {
            if (($event->type == 'CommentAdded' || $event->type == 'CommentCreated') && ($event->user_id != Session::read('user.id')) && ($event->pitch->user_id != Session::read('user.id'))) {


                if ($event->type == 'SolutionAdded' && !$event->solution) {
                    $event->delete();
                } elseif ($event->type == 'CommentAdded' && !$event->comment) {
                    $event->delete();
                } elseif ($event->type == 'LikeAdded' && !$event->solution) {
                    $event->delete();
                }

                // Parent
                if (($event->comment->question_id == 0) && ($event->comment->public != 1)) {
                    if (Comment::find('count', array('conditions' => array('question_id' => $event->comment->id, array('public = 1 OR user_id = ' . Session::read('user.id'))))) == 0) {
                        continue;
                    }
                }

                // Child
                if (($event->comment->question_id != 0) && ($event->comment->public != 1) && ($event->comment->reply_to != Session::read('user.id'))) {
                    if (Comment::find('count', array('conditions' => array('id' => $event->comment->question_id, array('public = 1 OR user_id = ' . Session::read('user.id'))))) == 0) {
                        continue;
                    }
                }


            }
            $event->sort = $i;
            $eventList[] = $event->data();
            $i++;
        }
        return $eventList;
    }

    public static function getEventSolutions() {
        return Event::all(array('conditions' => array('type' => 'SolutionAdded', 'private' => 0, 'category_id' => array('!=' => 7), 'multiwinner' => 0), 'order' => array('Event.created' => 'desc'), 'limit' => 10, 'with' => array('Pitch')));
    }

    public static function getSidebarEvents($created, $limit = null) {
        $eventList = $conditions = array();
        if ($created == false) {
            $conditions = array('type' => array('PitchCreated', 'PitchFinished'));
        } else {
            $conditions = array('created' => array('>' => $created), 'type' => array('PitchCreated', 'PitchFinished'));
        }
        $events = Event::all(array(
                    'conditions' => $conditions,
                    'order' => array('created' => 'desc'),
                    'limit' => $limit,
                    'with' => array('Pitch')
        ));
        $i = 1;
        foreach ($events as $event) {
            if (($event->type == 'SolutionAdded') && (($event->pitch->private == 1) || ($event->pitch->category_id))) {
                continue;
            } else {
                $event->sort = $i;
                $eventList[] = $event->data();
                $i++;
            }
        }
        return $eventList;
    }

    public static function createConditions($input) {
        $list = array();
        foreach ($input as $pitchId => $created) {
            $list[] = array('AND' => array('type' => array('SolutionPicked', 'CommentAdded', 'CommentCreated', 'PitchFinished', 'SolutionAdded', 'LikeAdded'), 'pitch_id' => $pitchId, 'created' => array('>=' => $created)));
        }
        $list[] = array('AND' => array('type' => array('PitchCreated', 'newsAdded'), 'created' => array('>=' => $created)));
        $output = array('OR' => $list);
        return $output;
    }

}
