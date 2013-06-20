<?php

namespace app\models;

use \app\extensions\helper\NameInflector;

use \app\models\Pitch;
use \app\models\User;
use \app\models\Comment;
use \app\models\Solution;
use \app\extensions\helper\MoneyFormatter;
use \lithium\storage\Session;

class Event extends \app\models\AppModel {

    public $belongsTo = array('Pitch', 'User', 'Comment', 'Solution');

    public static function __init() {
        parent::__init();
        self::applyFilter('find', function($self, $params, $chain){
            $result = $chain->next($self, $params, $chain);
            if(is_object($result)) {
                $addUpdateText = function($record) {
                    $record->updateText = ''; 
                    if($record->type == 'SolutionAdded'){
                        $record->updateText = ''; 
                    }
                    if($record->type == 'SolutionPicked'){
                        $moneyFormatter = new MoneyFormatter();
                        $solution = Solution::first($record->solution_id);
                        $record->updateText = '#' . $solution->num . ' стал победителем питча. Поздравляем! Вознаграждение ' . $moneyFormatter->formatMoney($record->pitch->price, array('suffix' => ' Р.-'));
                    }
                    if(($record->type == 'CommentAdded') || ($record->type == 'CommentCreated')){
                        $commentText = '';
                        if($record->comment) {
                            $commentText = $record->comment->text;
                        }
                        $record->updateText = $commentText; 
                    }
                    return $record;
                };
                $addBindings = function($record) {
                    if((isset($record->solution_id)) && ($record->solution_id > 0)) {
                        $record->solution = Solution::first(array('with' => array('Pitch'), 'conditions' => array('Solution.id' => $record->solution_id)));
                    }else {
                        $record->solution = Solution::getBestSolution($record->pitch_id);
                    }
                    if(($record->solution->pitch->private == 1) || ($record->solution->pitch->category_id == 7)) {
                        if(($record->user_id != Session::read('user.id')) && ($record->solution->pitch->user_id != Session::read('user.id'))) {
                            ///img/copy-inv.png
                            $record->solution->images['solution_solutionView']['weburl'] = '/img/copy-inv.png';
                            $record->solution->images['solution_gallerySiteSize']['weburl'] = '/img/copy-inv.png';
                            $record->solution->images['solution_galleryLargeSize']['weburl'] = '/img/copy-inv.png';
                            $record->solution->images['solution']['weburl'] = '/img/copy-inv.png';
                            //var_dump($record->solution->images['solution_solutionView']);
                        }
                    }
                    if((isset($record->pitch_id)) && ($record->pitch_id > 0)) {
                        $record->pitch= Pitch::first($record->pitch_id);
                        $category = Category::first($record->pitch->category_id);
                        $record->pitch->category = $category;
                        $record->pitch->category->lcTitle = mb_strtolower($record->pitch->category->title, 'utf-8');
                    }
                    if((isset($record->comment_id)) && ($record->comment_id > 0)) {
                        $record->comment= Comment::first($record->comment_id);
                    }
                    if((isset($record->user_id)) && ($record->user_id > 0)) {
                        $record->user= User::first($record->user_id);
                    }
                    return $record;
                };
                $addHumanDate = function($record) {
                    if(isset($record->created)) {
                        $record->humanCreated = date('d.m.Y H:i', strtotime($record->created));
                        $record->humanCreatedShort = date('d.m.Y', strtotime($record->created));
                    }
                    return $record;
                };
                $addJSDate = function($record) {
                    if(isset($record->created)) {
                        $record->jsCreated = date('Y/m/d H:i:s', strtotime($record->created));
                    }
                };
                $addCreator = function($record) {
                    $autoCreators = array('SolutionPicked', 'PitchFinished');
                    if(in_array($record->type, $autoCreators)) {
                        $record->creator = 'Go Designer';
                    }else {
                        if($record->user) {
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
                    );
                    if(isset($typesMap[$record->type])) {
                        $record->humanType = $typesMap[$record->type];
                    }
                    return $record;   
                };
                if(get_class($result) == 'lithium\data\entity\Record') {
                    $result = $addBindings($result);
                    $result = $addCreator($result);
                    $result = $addHumanDate($result);
                    $result = $addHumanType($result);
                    $result = $addUpdateText($result);
                    $result = $addJSDate($result);
                }else {
                    foreach($result as $foundItem) {
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
	
	public static function createEvent($pitchId, $type, $userId, $solutionId = 0, $commentId = 0) {
		$newEvent = self::create();
		$newEvent->created = date('Y-m-d H:i:s');
		$newEvent->pitch_id = $pitchId;
        $newEvent->user_id = $userId;
        $newEvent->solution_id = $solutionId;
        $newEvent->comment_id = $commentId;
		$newEvent->type = $type;
		return $newEvent->save();
	}

    public static function getEvents($pitchIds, $page = 1, $created = null){
        $eventList = $conditions = array();
        $limit = 10;
        //var_dump(isset($created));
        if(isset($created)) {
            $page = 1;
            $limit = 100;
            $conditions = array('created' => array('>' => $created));
        }
        if(!empty($pitchIds)) {
            $events = Event::find('all', array(
                'conditions' => $conditions + Event::createConditions($pitchIds),
                'order' => array('created' => 'desc'),
                'limit' => $limit,
                'page' => $page
                )
            );
            //echo $created;
            //echo '<pre>';
            //var_dump($conditions + Event::createConditions($pitchIds));
            //var_dump($conditions);
            //echo '</pre>';
            $i = 1;
            foreach($events as $event) {
                if(($event->pitch->private == 1) || ($event->pitch->category_id == 7)) {
                    if(($event->type == 'CommentAdded' || $event->type == 'CommentCreated') && ($event->user_id != Session::read('user.id')) && ($event->pitch->user_id != Session::read('user.id'))) {
                        if(preg_match_all('@#(\d)@', $event->comment->text, $matches, PREG_PATTERN_ORDER)) {
                            $array = array();
                            foreach($matches[1] as $match):
                                $array[] = $match;
                            endforeach;
                            $noSolutions = true;

                            foreach($array as $num):
                                $solution = Solution::first(array('conditions' => array(
                                    'num' => $num,
                                    'pitch_id' => $event->pitch->id,
                                )));
                                if($solution->user_id == Session::read('user.id')) {
                                    $noSolutions = false;
                                    break;
                                }
                            endforeach;
                            //108
                            if(($noSolutions) && ($event->user_id != '108')):
                                $event->updateText = 'Этот комментарий закрыт для просмотра';
                            endif;
                        }
                        if(($event->comment->reply_to != 0) && ($event->comment->reply_to != Session::read('user.id'))) {
                            $event->updateText = 'Этот комментарий закрыт для просмотра';
                        }
                    }
                }
                $event->sort = $i;
                $eventList[] = $event->data();
                $i++;
            }
        }
        return $eventList;
    }

    public static function getSidebarEvents($created, $limit = null) {
        $eventList = $conditions = array();
        if($created == false) {
            $conditions = array('type' => array('PitchCreated', 'PitchFinished'));
        }else {
            $conditions = array('created' => array('>' => $created), 'type' => array('PitchCreated', 'PitchFinished'));
        }
        $events = Event::all(array(
            'conditions' => $conditions,
            'order' => array('created' => 'desc'),
            'limit' => $limit,
            'with' => array('Pitch')
        ));
        $i = 1;
        foreach($events as $event) {
            if(($event->type == 'SolutionAdded') && (($event->pitch->private == 1) || ($event->pitch->category_id))) {
                continue;
            }else {
                $event->sort = $i;
                $eventList[] = $event->data();
                $i++;
            }
        }
        return $eventList;
    }

    public static function createConditions($input) {
        $list = array();
        foreach($input as $pitchId => $created) {
            $list[] = array('AND' => array('type' => array('SolutionPicked', 'CommentAdded', 'CommentCreated', 'PitchFinished', 'SolutionAdded'), 'pitch_id' => $pitchId, 'created' => array('>=' =>$created)));
        }
        $list[] = array('AND' => array('type' => 'PitchCreated', 'created' => array('>=' =>$created)));
        $output = array('OR' => $list);
        return $output;
    }

}