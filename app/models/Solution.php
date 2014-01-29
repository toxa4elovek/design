<?php

namespace app\models;

use \app\models\Event;
use \app\models\Pitch;
use \app\models\Like;
use \app\models\Comment;
use \app\models\Ratingchange;
use \app\models\Historysolution;
use app\models\Task;
use \app\extensions\helper\NameInflector;
use \app\extensions\helper\MoneyFormatter;

class Solution extends \app\models\AppModel {

	public $belongsTo = array('Pitch', 'User');
    public $hasMany = array('Like');

    protected static $_behaviors = array(
        'UploadableSolution'
    );

    public static $attaches = array('solution' => array(
        'validate' => array('uploadedOnly' => true),
        'moveFile' => array('preserveFileName' => false, 'path' => '/webroot/solutions/'),
        'setPermission' => array('mode' => 0644),
        'processImage' => array(
        ),
    ));

    public static function __init() {
        parent::__init();
        self::applyFilter('find', function($self, $params, $chain){
            $result = $chain->next($self, $params, $chain);
            return $result;
        });
        self::applyFilter('delete', function($self, $params, $chain){
            if($result = $chain->next($self, $params, $chain)) {
                $record = $params['entity'];
                if($event = Event::first(array('conditions' => array('solution_id' => $record->id, 'user_id' => $record->user_id, 'pitch_id' => $record->pitch_id)))) {
                    $event->delete();
                }
                Pitch::decreaseIdeasCountOne($record->pitch_id);
            }

        });

        self::applyFilter('uploadSolution', function($self, $params, $chain){
            $result = $chain->next($self, $params, $chain);
            if($result) {
                Event::createEvent($result->pitch_id, 'SolutionAdded', $result->user_id, $result->id);
                Pitch::increaseIdeasCountOne($result->pitch_id);
                $historySolution = Historysolution::create();
                $historySolution->set($result->data());
                $historySolution->save();
                $count = $self::count(array('conditions' => array('pitch_id' => $result->pitch_id)));
                if($count == 1) {
                    User::sendSpamFirstSolutionForPitch($result->pitch_id);
                    $admin = User::getAdmin();
                    $pitch = Pitch::first($result->pitch_id);
                    $client = User::first($pitch->user_id);
                    $nameInflector = new nameInflector();
                    $data = array('pitch_id' => $result->pitch_id, 'user_id' => $admin, 'text' => '@' . $nameInflector->renderName($client->first_name, $client->last_name) . ', если вы хотите получить больше идей — комментируйте решения и обязательно выставляйте рейтинг (звезды). Постарайтесь объяснить, чем приведенные идеи вам нравятся или, наоборот, не близки. Умейте сказать «Спасибо», ведь до победы дизайнеры работают для вас безвозмездно. Про то, как еще можно мотивировать дизайнеров, можно прочитать тут:
http://godesigner.ru/answers/view/78
http://godesigner.ru/answers/view/73');
                    Comment::createComment($data);
                }else {
                    // Добавляем задание о рассылке уведомления о новом решении в очередь
                    Task::createNewTask($result->id, 'newSolutionNotification');
                }
            }
            return $result;
        });
        self::applyFilter('selectSolution', function($self, $params, $chain){
            Event::createEvent($params['solution']->pitch->id, 'SolutionPicked', $params['solution']->pitch->user_id, $params['solution']->id);
            $result = $chain->next($self, $params, $chain);
            if($result != false) {
                $admin = User::getAdmin();
                $solution = $params['solution'];
                $pitch = Pitch::first($solution->pitch_id);
                $message = 'Друзья, выбран победитель. <a href="http://www.godesigner.ru/pitches/viewsolution/' . $params['solution']->id . '">Им стал</a> #' . $params['solution']->num . '.  Мы поздравляем автора решения и благодарим всех за участие. Если ваша идея не выиграла в этот раз, то, возможно, в следующий вам повезет больше - все права сохраняются за вами, и вы можете адаптировать идею для участия в другом питче!<br/>
Подробнее читайте тут: <a href="http://www.godesigner.ru/answers/view/51">http://godesigner.ru/answers/view/51</a>';
                $data = array('pitch_id' => $params['solution']->pitch_id, 'user_id' => $admin, 'text' => $message, 'public' => 1);
                Comment::createComment($data);
                $params = '?utm_source=twitter&utm_medium=tweet&utm_content=winner-tweet&utm_campaign=sharing';
                $solutionUrl = 'http://www.godesigner.ru/pitches/viewsolution/' . $solution->id . $params;
                $winner = User::first($solution->user_id);
                $nameInflector = new nameInflector();
                $winnerName = $nameInflector->renderName($winner->first_name, $winner->last_name);
                $moneyFormatter = new MoneyFormatter();
                $winnerPrice = $moneyFormatter->formatMoney($pitch->price, array('suffix' => ' РУБ.-'));
                if (rand(1, 100) <= 50) {
                    $tweet = $winnerName . ' заработал ' . $winnerPrice . ' за питч «' . $pitch->title . '» ' . $solutionUrl . ' #Go_Deer';
                } else {
                    $tweet = $winnerName . ' победил в питче «' . $pitch->title . '», вознаграждение ' . $winnerPrice . ' ' . $solutionUrl . ' #Go_Deer';
                }
                User::sendTweet($tweet);
                User::sendSpamSolutionSelected($result);
            }
            return $result;
        });
    }

    public static function uploadSolution($formdata) {
        if((!isset($formdata['licensed_work'])) || ($formdata['licensed_work'] == 0)) {
            $copyrightedMaterial = 0;
        }else {
            $copyrightedMaterial = 1;
        }
        if(!isset($formdata['filename'])) {
            $formdata['filename'] = array();
        }
        if(!isset($formdata['source'])) {
            $formdata['source'] = array();
        }
        if(!isset($formdata['needtobuy'])) {
            $formdata['needtobuy'] = array();
        }
        $dataToSerialize = array(
            'filename' => $formdata['filename'],
            'source' => $formdata['source'],
            'needtobuy' => $formdata['needtobuy']
        );
        if(!isset($formdata['solution'])) {
            $formdata['solution'] = null;
        }
        $data = array(
            'user_id' => $formdata['user_id'],
            'pitch_id' => $formdata['pitch_id'],
            'num' => self::getNextNum($formdata['pitch_id']),
            'copyrightedMaterial' => $copyrightedMaterial,
            'copyrightedInfo' => serialize($dataToSerialize),
            'description' => $formdata['description'],
            'solution' => $formdata['solution'],
            'created' => date('Y-m-d H:i:s')
        );
        $solution = Solution::create();
        $solution->save($data);
        $params = $solution;
        return static::_filter(__FUNCTION__, $params, function($self, $params) {
            return $params;
        });
    }

    public static function increaseView($solutionId) {
        if($solution = self::first($solutionId)) {
            $pitch = Pitch::first(array('conditions' => array('id' => $solution->pitch_id)));
            if($pitch->status == 0) {
                $solution->views += 1;
                $solution->save();
            }
            return $solution->views;
        }
        return false;
    }

    public static function increaseLike($solutionId, $userId = 0) {
        $solution = self::first($solutionId);
        $pitch = Pitch::first(array('conditions' => array('id' => $solution->pitch_id)));
        $userId = (int)$userId;
        $allowAnon = false;
        if (!$userId && (!isset($_COOKIE['bmx_' . $solutionId]) || ($_COOKIE['bmx_' . $solutionId] == 'false'))) {
            $allowAnon = true;
            setcookie('bmx_' . $solutionId, 'true', strtotime('+3 month'), '/');
        }
        $allowUser = false;
        if ($userId && (!$like = Like::find('first', array('conditions' => array('solution_id' => $solutionId, 'user_id' => $userId))))) {
            $allowUser = true;
        }
        if (($allowUser || $allowAnon) && ($pitch->status == 0)) {
            $solution->likes += 1;
            $solution->save();
            $like = Like::create();
            $like->set(array('solution_id' => $solutionId, 'user_id' => $userId, 'created' => date('Y-m-d H:i:s')));
            $like->save();
        }
        return $solution->likes;
    }

    public static function hideimage($solutionId, $userId) {
        $solution = self::first($solutionId);
        $pitch = Pitch::first(array('conditions' => array('id' => $solution->pitch_id)));
        if($pitch->user_id == $userId) {
            $solution->hidden = 1;
            $solution->save();
        }
        return $solution->hidden;
    }

    public static function unhideimage($solutionId, $userId) {
        $solution = self::first($solutionId);
        $pitch = Pitch::first(array('conditions' => array('id' => $solution->pitch_id)));
        if($pitch->user_id == $userId) {
            $solution->hidden = 0;
            $solution->save();
        }
        return $solution->hidden;
    }

    public static function decreaseLike($solutionId, $userId = 0) {
        $solution = self::first($solutionId);
        $userId = (int)$userId;
        $allowAnon = false;
        if (!$userId && (isset($_COOKIE['bmx_' . $solutionId]) && ($_COOKIE['bmx_' . $solutionId] == 'true'))) {
            $allowAnon = true;
            setcookie('bmx_' . $solutionId, 'false', strtotime('+3 month'), '/');
        }
        if(($like = Like::find('first', array('conditions' => array('solution_id' => $solutionId, 'user_id' => $userId)))) && ($userId || ($allowAnon))) {
            $solution->likes -= 1;
            $solution->save();
            $like->delete();
        }
        return $solution->likes;
    }

    public static function setRating($solutionId, $rating, $userId) {
        $solution = Solution::first($solutionId);
        $pitch = Pitch::first($solution->pitch_id);
        $history = Ratingchange::create();
        Ratingchange::remove(array('user_id' => $userId, 'solution_id' => $solutionId));
        $history->set(array('user_id' => $userId, 'solution_id' => $solutionId, 'created' => date('Y-m-d H:i:s')));
        $history->save();
        $params = compact('pitch', 'solution', 'rating', 'userId');
        return static::_filter(__FUNCTION__, $params, function($self, $params) {
            extract($params);
            if($pitch->user_id == $userId) {
                $solution->rating = $rating;
                $solution->save();
            }
            return $solution;
        });
    }

    public static function getSolutionIdFromOrder($pitchId, $orderNum) {
        if($item = self::find('first', array('conditions' => array('pitch_id' => $pitchId, 'num' => $orderNum), 'limit' => 1))) {
            return $item->id;
        }
        return false;
    }

    public static function getSolutionIdFromReply($commentId) {
        $comment = Comment::first($commentId);
        return $comment->solution_id;
    }

    public static function getBestSolution($pitchId) {
        $mostLiked = self::first(array('conditions' => array('pitch_id' => $pitchId), 'with' => array('Pitch'), 'order' => array('likes' => 'desc')));
        return $mostLiked;
    }

    public static function getNextNum($pitchId) {
        $pitch = Pitch::first($pitchId);
        if (($prevSolution = self::first(array('conditions' => array('pitch_id' => $pitchId), 'order' => array('num' => 'desc')))) && (strtotime($prevSolution->created) < strtotime('2013-10-23 00:00:00'))) {
            $res = $prevSolution->num + 1;
        } else {
	       $res = $pitch->last_solution + 1;
        }
        $pitch->last_solution = $res;
        $pitch->save();
        return $res;
    }

    public static function getUserSolutionGallery($userId) {
        $createdPitches = Pitch::all(array('conditions' => array('user_id' => $userId)));
        $records = array();
        foreach($createdPitches as $pitch) {
            $solutions = Solution::all(array('conditions' => array('pitch_id' => $pitch->id)));
            foreach($solutions as $solution) {
                $records[] = $solution->data();
            }
        }

        $solutions = Solution::all(array('conditions' => array('Solution.user_id' => $userId), 'with' => array('Pitch')));
        foreach($solutions as $solution) {
            $records[] = $solution->data();
        }
        usort($records, function($a, $b) {
            if ($a['created'] == $b['created']) {
                return 0;
            }
            return (strtotime($a['created']) < strtotime($b['created'])) ? 1 : -1;
        });
        return $records;
    }

    public static function selectSolution($solution) {
        $pitch = Pitch::first($solution->pitch_id);
        $params = compact('solution', 'pitch');
        return static::_filter(__FUNCTION__, $params, function($self, $params) {
            extract($params);
            $solution->nominated = 1;
            $solution->change = date('Y-m-d H:i:s');
            $solution->save();
            $pitch->awarded = $solution->id;
            $pitch->status = 1;
            $pitch->awardedDate = date('Y-m-d H:i:s');
            $pitch->save();
            $result = $solution->data();
            return compact('result');
        });
    }

    public static function getNumOfUploadedSolutionInLastDay() {
        $count = Solution::count(array('conditions' => array('created' => array('>' => date('Y-m-d H:i:s', (time() - DAY))))));
        return $count;
    }

    public static function getTotalParticipants() {
        $count = self::all(array('group' => 'user_id'));
        return count($count);
    }

}