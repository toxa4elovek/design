<?php

namespace app\models;

use app\extensions\helper\Pitch as PitchHekper;
use app\extensions\storage\Rcache;
use app\extensions\helper\NameInflector;
use lithium\analysis\Logger;
use lithium\storage\Session;
use lithium\data\collection\RecordSet;
use lithium\data\entity\Record;
use app\models\User;
use OneSignal\Config;
use OneSignal\OneSignal;

/**
 * Class Solution
 * @package app\models
 * @method Record|null first(array $conditions = []) static
 * @method int count(array $conditions = []) static
 * @method RecordSet|null all(array $conditions = []) static
 */
class Solution extends AppModel
{

    public $belongsTo = array('Pitch', 'User');
    public $hasMany = array('Like', 'Solutiontag', 'Promo');
    public static $logosaleNarrowSearches = array(
        'it',
        'кот'
    );
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
    public static $industryDictionary = array(
        'realty' => 'Недвижимость / Строительство',
        'auto' => 'Автомобили / Транспорт',
        'finances' => 'Финансы / Бизнес',
        'food' => 'Еда / Напитки',
        'adv' => 'Реклама / Коммуникации',
        'tourism' => 'Туризм / Путешествие',
        'sport' => 'Спорт',
        'sci' => 'Образование / Наука',
        'fashion' => 'Красота / Мода',
        'music' => 'Развлечение / Музыка',
        'culture' => 'Искусство / Культура',
        'animals' => 'Животные',
        'children' => 'Дети',
        'security' => 'Охрана / Безопасность',
        'health' => 'Медицина / Здоровье',
        'it' => 'Компьютеры / IT'
    );

    public static function __init()
    {
        parent::__init();
        self::applyFilter('find', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            return $result;
        });
        self::applyFilter('delete', function ($self, $params, $chain) {
            if ($result = $chain->next($self, $params, $chain)) {
                $record = $params['entity'];
                if ($event = Event::first(array('conditions' => array('solution_id' => $record->id, 'user_id' => $record->user_id, 'pitch_id' => $record->pitch_id)))) {
                    $event->delete();
                }
                Pitch::decreaseIdeasCountOne($record->pitch_id);
            }
        });

        self::applyFilter('uploadSolution', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if ($result) {
                Event::createEvent($result->pitch_id, 'SolutionAdded', $result->user_id, $result->id);
                if ($uploadnonce = Uploadnonce::first(array('conditions' => array('nonce' => $params->uploadnonce)))) {
                    $nonce = $uploadnonce->id;
                }
                if ($files = Solutionfile::all(array('fields' => array('id', 'position'), 'conditions' => array('model' => '\app\models\Uploadnonce', 'model_id' => $nonce)))) {
                    foreach ($files as $file) {
                        // Change order
                        if (!empty($params->resortable[$file->position]) && $file->position != $params->resortable[$file->position]) {
                            $file->set(array(
                                'position' => $params->resortable[$file->position],
                            ));
                        }
                        $file->set(array(
                            'model' => '\app\models\Solution',
                            'model_id' => $result->id,
                        ));
                        $file->save();
                    }
                }
                Pitch::increaseIdeasCountOne($result->pitch_id);
                $historySolution = Historysolution::create();
                $historySolution->set($result->data());
                $historySolution->save();
                $count = $self::count(array('conditions' => array('pitch_id' => $result->pitch_id)));
                if ($count == 1) {
                    User::sendSpamFirstSolutionForPitch($result->pitch_id);
                    $admin = User::getAdmin();
                    $pitch = Pitch::first($result->pitch_id);
                    $client = User::first($pitch->user_id);
                    $nameInflector = new nameInflector();
                    $data = array('pitch_id' => $result->pitch_id, 'user_id' => $admin, 'text' => '@' . $nameInflector->renderName($client->first_name, $client->last_name) . ', если вы хотите получить больше идей — комментируйте решения и обязательно выставляйте рейтинг (звезды). Постарайтесь объяснить, чем приведенные идеи вам нравятся или, наоборот, не близки. Умейте сказать «Спасибо», ведь до победы дизайнеры работают для вас безвозмездно. Про то, как еще можно мотивировать дизайнеров, можно прочитать тут:
http://godesigner.ru/answers/view/78
http://godesigner.ru/answers/view/73');
                    Comment::createComment($data);
                } else {
                    // Добавляем задание о рассылке уведомления о новом решении в очередь
                    Task::createNewTask($result->id, 'newSolutionNotification');
                }
                try {
                    $pitch = Pitch::first($result->pitch_id);
                    if (($pitch->category_id != 7) && ($pitch->private != 1)) {
                        $id = 'https://www.godesigner.ru/pitches/viewsolution/' . $result->id;
                        $url = 'https://graph.facebook.com';
                        $data = array('id' => $id, 'scrape' => 'true');
                        $options = array(
                            'http' => array(
                                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                'method'  => 'POST',
                                'content' => http_build_query($data),
                            ),
                        );
                        $context  = stream_context_create($options);
                        file_get_contents($url, false, $context);
                    }
                } catch (Exception $e) {
                }
            }
            return $result;
        });
        self::applyFilter('selectSolution', function ($self, $params, $chain) {
            Logger::write('info', Session::read('user.id'), array('name' => 'solution'));
            Event::createEvent($params['solution']->pitch->id, 'SolutionPicked', $params['solution']->pitch->user_id, $params['solution']->id);
            $result = $chain->next($self, $params, $chain);
            if ($result != false) {
                $solution = $params['solution'];
                User::sendWinnerComment($solution);
                Task::createNewTask($solution->id, 'victoryNotification');
                if(!$solution->pitch->private) {
                    Task::createNewTask($solution->id, 'victoryNotificationTwitter');
                    $config = new Config();
                    $config->setApplicationId('46001cba-49be-4cc5-945a-bac990a6d995');
                    $config->setApplicationAuthKey('YTRkYWE2OWMtNjQ4OS00ZjI1LThiZjItZjVlMzdlMWM2Mzc2');
                    $config->setUserAuthKey('YmFjYWI1MTQtYjgzOS00NDFhLTg2YjAtY2IzZjc4OWFjNGVm');
                    $api = new OneSignal($config);
                    $api->notifications->add([
                        'contents' => [
                            'en' => $solution->pitch->title,
                            'ru' => $solution->pitch->title
                        ],
                        'headings' => [
                            'en' => 'Выбран победитель!',
                            'ru' => 'Выбран победитель!'
                        ],
                        'included_segments' => ['All'],
                        'url' => "https://www.godesigner.ru/pitches/viewsolution/$solution->id",
                        'isChromeWeb' => true,
                    ]);
                    $api->notifications->add([
                        'contents' => [
                            'en' => 'Выбран победитель! ' . $solution->pitch->title,
                            'ru' => 'Выбран победитель! ' . $solution->pitch->title
                        ],
                        'included_segments' => ['All'],
                        'url' => "https://www.godesigner.ru/pitches/viewsolution/$solution->id",
                        'isSafari' => true,
                    ]);
                }
            }
            return $result;
        });
    }

    public static function uploadSolution($formdata)
    {
        if ((!isset($formdata['licensed_work'])) || ($formdata['licensed_work'] == 0)) {
            $copyrightedMaterial = 0;
        } else {
            $copyrightedMaterial = 1;
        }
        if (!isset($formdata['filename'])) {
            $formdata['filename'] = array();
        }
        if (!isset($formdata['source'])) {
            $formdata['source'] = array();
        }
        if (!isset($formdata['needtobuy'])) {
            $formdata['needtobuy'] = array();
        }
        $dataToSerialize = array(
            'filename' => $formdata['filename'],
            'source' => $formdata['source'],
            'needtobuy' => $formdata['needtobuy']
        );
        if (!isset($formdata['solution'])) {
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
        Tag::add($formdata, $solution->id);
        $params = $solution;
        $params->uploadnonce = $formdata['uploadnonce'];
        $params->resortable = $formdata['reSortable'];
        return static::_filter(__FUNCTION__, $params, function ($self, $params) {
                    return $params;
                });
    }

    public static function increaseView($solutionId)
    {
        if ($solution = self::first($solutionId)) {
            $pitch = Pitch::first(array('conditions' => array('id' => $solution->pitch_id)));
            if ($pitch->status == 0) {
                $solution->views += 1;
                $solution->save();
            }
            return $solution->views;
        }
        return false;
    }

    public static function increaseLike($solutionId, $userId = 0)
    {
        $result = false;
        $solution = self::first($solutionId);
        if ($userId == 0) {
            return array('result' => $result, 'likes' => $solution->likes);
        }
        $pitch = Pitch::first(array('conditions' => array('id' => $solution->pitch_id)));
        $userId = (int) $userId;
        $allowAnon = false;
        if (!$userId && (!isset($_COOKIE['bmx_' . $solutionId]) || ($_COOKIE['bmx_' . $solutionId] == 'false'))) {
            $allowAnon = true;
            setcookie('bmx_' . $solutionId, 'true', strtotime('+3 month'), '/');
        }
        $allowUser = false;
        if ($userId && (!$like = Like::find('first', array('conditions' => array('solution_id' => $solutionId, 'user_id' => $userId))))) {
            $allowUser = true;
        }
        $pitchHelper = new PitchHekper();
        if (($allowUser || $allowAnon) && ($pitch->status == 0)) {
            $solution->likes += 1;
            $solution->save();
            $like = Like::create();
            $like->set(array('solution_id' => $solutionId, 'user_id' => $userId, 'created' => date('Y-m-d H:i:s')));
            if ($result = $like->save()) {
                Event::createEvent($solution->pitch_id, 'LikeAdded', $userId, $solution->id);
            }
        }
        return array('result' => $result, 'likes' => $solution->likes);
    }

    public static function hideimage($solutionId, $userId)
    {
        $solution = self::first($solutionId);
        $pitch = Pitch::first(array('conditions' => array('id' => $solution->pitch_id)));
        if ($pitch->user_id == $userId) {
            $solution->hidden = 1;
            $solution->save();
        }
        return $solution->hidden;
    }

    public static function unhideimage($solutionId, $userId)
    {
        $solution = self::first($solutionId);
        $pitch = Pitch::first(array('conditions' => array('id' => $solution->pitch_id)));
        if ($pitch->user_id == $userId) {
            $solution->hidden = 0;
            $solution->save();
        }
        return $solution->hidden;
    }

    public static function decreaseLike($solutionId, $userId = 0)
    {
        $result = false;
        $solution = self::first($solutionId);
        $userId = (int) $userId;
        $allowAnon = false;
        if (!$userId && (isset($_COOKIE['bmx_' . $solutionId]) && ($_COOKIE['bmx_' . $solutionId] == 'true'))) {
            $allowAnon = true;
            setcookie('bmx_' . $solutionId, 'false', strtotime('+3 month'), '/');
        }
        if (($like = Like::find('first', array('conditions' => array('solution_id' => $solutionId, 'user_id' => $userId)))) && ($userId || ($allowAnon))) {
            $solution->likes -= 1;
            $solution->save();
            if ($result = $like->delete()) {
                if ($event = Event::first(array('conditions' => array('user_id' => $userId, 'solution_id' => $solutionId, 'Event.type' => 'LikeAdded')))) {
                    $event->delete();
                }
            }
        }
        return array('result' => $result, 'likes' => $solution->likes);
    }

    public static function setRating($solutionId, $rating, $userId)
    {
        $solution = Solution::first($solutionId);
        $pitch = Pitch::first($solution->pitch_id);
        if(in_array($userId, User::$admins)) {
            $userId = $pitch->user_id;
        }
        $history = Ratingchange::create();
        Ratingchange::remove(array('user_id' => $userId, 'solution_id' => $solutionId));
        $history->set(array('user_id' => $userId, 'solution_id' => $solutionId, 'created' => date('Y-m-d H:i:s')));
        $history->save();
        $params = compact('pitch', 'solution', 'rating', 'userId');
        return static::_filter(__FUNCTION__, $params, function ($self, $params) {
                    extract($params);
                    if ($pitch->user_id == $userId) {
                        $solution->rating = $rating;
                        $solution->save();
                        if (!$event = Event::first(array('conditions' => array('Event.type' => 'RatingAdded', 'user_id' => $userId, 'solution_id' => $solution->id, 'pitch_id' => $pitch->id)))) {
                            Event::create(array(
                                'Event.type' => 'RatingAdded',
                                'created' => date('Y-m-d H:i:s'),
                                'user_id' => $pitch->user_id,
                                'pitch_id' => $pitch->id,
                                'solution_id' => $solution->id
                            ))->save();
                        } else {
                            $event->created = date('Y-m-d H:i:s');
                            $event->save();
                        }
                    }
                    return $solution;
                });
    }

    public static function getSolutionIdFromOrder($pitchId, $orderNum)
    {
        if ($item = self::find('first', array('conditions' => array('pitch_id' => $pitchId, 'num' => $orderNum), 'limit' => 1))) {
            return $item->id;
        }
        return false;
    }

    public static function getSolutionIdFromReply($commentId)
    {
        $comment = Comment::first($commentId);
        return $comment->solution_id;
    }

    public static function getBestSolution($pitchId)
    {
        $pitch = Pitch::first($pitchId);
        if ($pitch && $pitch->awarded > 0) {
            if (!$mostLiked = Rcache::read('awarded-' . $pitchId)) {
                $mostLiked = self::first(array('conditions' => array('pitch_id' => $pitchId, 'id' => $pitch->awarded)));
                Rcache::write('awarded-' . $pitchId, $mostLiked);
            }
        } else {
            $mostLiked = self::first(array('conditions' => array('pitch_id' => $pitchId), 'with' => array('Pitch'), 'order' => array('likes' => 'desc', 'views' => 'desc')));
        }
        return $mostLiked;
    }

    public static function getNextNum($pitchId)
    {
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

    public static function getUserSolutionGallery($userId)
    {
        $createdPitches = Pitch::all(array('conditions' => array('user_id' => $userId)));
        $records = array();
        foreach ($createdPitches as $pitch) {
            $solutions = Solution::all(array('conditions' => array('pitch_id' => $pitch->id)));
            foreach ($solutions as $solution) {
                $records[] = $solution->data();
            }
        }

        $solutions = Solution::all(array('conditions' => array('Solution.user_id' => $userId), 'with' => array('Pitch')));
        foreach ($solutions as $solution) {
            $records[] = $solution->data();
        }
        usort($records, function ($a, $b) {
            if ($a['created'] == $b['created']) {
                return 0;
            }
            return (strtotime($a['created']) < strtotime($b['created'])) ? 1 : -1;
        });
        return $records;
    }

    public static function selectSolution($solution)
    {
        $pitch = Pitch::first($solution->pitch_id);
        $params = compact('solution', 'pitch');
        return static::_filter(__FUNCTION__, $params, function ($self, $params) {
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

    public static function revert($solution_id)
    {
        if ($solution = self::first($solution_id)) {
            $solution->awarded = 0;
            $solution->nominated = 0;
            $solution->save();
        }
    }

    public static function getNumOfUploadedSolutionInLastDay()
    {
        $count = Solution::count(array('conditions' => array('created' => array('>' => date('Y-m-d H:i:s', (time() - DAY))))));
        return $count;
    }

    public static function getTotalParticipants()
    {
        return User::count(array('conditions' => array('isClient' => 0)));
    }

    public static function copy($new_pitchId, $old_solution)
    {
        if ($solution = Solution::first($old_solution)) {
            $copySolution = Solution::create();
            $data = $solution->data();
            $data['pitch_id'] = $new_pitchId;
            $data['multiwinner'] = $solution->id;
            unset($data['id']);
            $copySolution->set($data);
            if ($copySolution->save()) {
                if ($copySolution->images) {
                    Solutionfile::copy($solution->id, $copySolution->id);
                }
                return $copySolution->id;
            }
        } else {
            return false;
        }
    }

    public static function awardCopy($id)
    {
        if ($solution = Solution::first($id)) {
            $solution->nominated = 1;
            $solution->awarded = 1;
            $solution->save();
        } else {
            return false;
        }
    }

    public static function getCreatedDate($solutionId)
    {
        if ($solutionId && $solutionDate = Solution::first($solutionId)) {
            $monthes = array(
                1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
                5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
                9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
            );
            $solutionDate = strtotime($solutionDate->created);
            $date = (date('j ', $solutionDate) . $monthes[(date('n', $solutionDate))] . date(' Y, H:i', $solutionDate));
            //d F Y, H:i
            return $date;
        }
        return false;
    }

    public static function filterLogoSolutions($solutions)
    {
        if ($solutions) {
            $black_list = array();
            $winnersArray = array();
            $solutionsArray = array();
            // получаем айдишники купленных решений
            $logosalePitches = Pitch::all(array(
                'fields' => array('blank_id'),
                'conditions' => array('blank' => 1, 'billed' => 1)));
            foreach ($logosalePitches as $logosalePitch) {
                $solutionsArray[] = $logosalePitch->blank_id;
            }
            // запоминаем решения победители
            foreach ($solutions as $v) {
                $v->sort = 0;
                if (!in_array($v->pitch->awarded, $winnersArray)) {
                    $solutionsArray[] = $v->pitch->awarded;
                }
                if ($v->awarded) {
                    $winnersArray[] = $v->user_id;
                    $black_list[] = array('user' => $v->user_id, 'pitch' => $v->pitch_id);
                }
            }
            // запоминаем айдишники победителей
            foreach ($solutionsArray as $winnerSolution) {
                $sol = Solution::first($winnerSolution);
                if ((is_object($sol)) && (!in_array($sol->user_id, $winnersArray))) {
                    $winnersArray[] = $sol->user_id;
                }
            }
            $solutions = $solutions->data();
            foreach ($solutions as $k => $solution) {
                // убираем победившие решения
                foreach ($black_list as $v) {
                    if ($v['pitch'] == $solution['pitch_id'] && $v['user'] == $solution['user_id']) {
                        unset($solutions[$k]);
                    }
                }
                // убириаем решение от победителя
                if (in_array($solution['user_id'], $winnersArray)) {
                    unset($solutions[$k]);
                }
                // убирам победившие решения еще раз
                if (in_array($solution['id'], $solutionsArray)) {
                    unset($solutions[$k]);
                }
                if ($solution['pitch_id'] == '102537') {
                    unset($solutions[$k]);
                }
            }
        } else {
            $solutions = array();
        }
        return $solutions;
    }

    public static function applyUserFilters(array $solutions, $prop = array(), $variant = array())
    {
        foreach ($solutions as $k => $solution) {
            $solutions[$k]['sort'] = 0;
            $specific = unserialize($solution['pitch']['specifics']);
            if (count($prop) > 0) {
                $diff_prop = count(array_diff_assoc($prop, $specific['logo-properties']));
            } else {
                $diff_prop = false;
            }
            if (isset($specific['logoType']) && count($variant) > 0) {
                $diff_variant = count(array_diff($specific['logoType'], $variant));
            } else {
                $diff_variant = false;
            }
            if ($diff_prop > 3 || $diff_variant == count($specific['logoType'])) {
                unset($solutions[$k]);
            }
        }
        return $solutions;
    }

    public static function addBlankPitchForLogosale($user_id, $solution_id)
    {
        $result = array();
        $fee = 3500;
        $award = 6000;
        $total = $fee + $award;
        $pitch = Pitch::first(array('conditions' => array('blank' => 1, 'user_id' => $user_id, 'billed' => 0)));
        if ($pitch) {
            $pitch->awarded = $solution_id;
            $pitch->save();
            $result['receipt'] = Receipt::all(array('conditions' => array('pitch_id' => $pitch->id)))->data();
        } else {
            $gatracking = new \Racecore\GATracking\GATracking('UA-9235854-5');
            $gaId = $gatracking->getClientId();
            $pitch = Pitch::create(array(
                        'category_id' => 1,
                        'title' => 'Logosale Pitch',
                        'price' => $award,
                        'total' => $total,
                        'user_id' => $user_id,
                        'awarded' => $solution_id,
                        'blank' => 1,
                        'ga_id' => $gaId
            ));
            if ($pitch->save()) {
                $data = array(
                    array(
                        'pitch_id' => $pitch->id,
                        'name' => 'Награда Дизайнеру',
                        'value' => $award,
                    ),
                    array(
                        'pitch_id' => $pitch->id,
                        'name' => 'Сбор GoDesigner',
                        'value' => $fee,)
                );
                foreach ($data as $v) {
                    $receipt = Receipt::create($v);
                    if ($receipt->save()) {
                        $result['receipt'][] = $receipt->data();
                    }
                }
            }
        }
        $result['total'] = $total;
        $result['pitch_id'] = $pitch->id;
        return $result;
    }

    public static function getTagsArrayForSolution($solution)
    {
        $cacheKey = 'tags_for_solutions_' . $solution->id;
        if (!$temp_tags = Rcache::read($cacheKey)) {
            $temp_tags = array();
            if (count($solution->solutiontags) > 0) {
                foreach ($solution->solutiontags as $v) {
                    if ($v->tag_id) {
                        if ($tag = Tag::first($v->tag_id)) {
                            $temp_tags[$tag->id] = $tag->name;
                        }
                    }
                }
            }
            Rcache::write($cacheKey, $temp_tags);
        }
        return $temp_tags;
    }

    /**
     * Метод возвращяет массив строк, обработанных функцией urldecode(),
     * принимает строчки или массивы
     *
     * @param $input
     * @return array
     */
    public static function stringToWordsForSearchQuery($input)
    {
        if (is_string($input)) {
            if (!empty($input)) {
                return explode(' ', urldecode($input));
            } else {
                return array();
            }
        } elseif (is_array($input)) {
            foreach ($input as &$word) {
                $word = urldecode($word);
            }
            return $input;
        }
    }

    /**
     * Метод возвращяет флипнутый массив видов деятельности (русские название - ключи,
     * английские ключи - значения)
     *
     * @return array|bool
     */
    public static function flipIndustryDictionary()
    {
        if (is_array(self::$industryDictionary)) {
            return array_flip(self::$industryDictionary);
        } else {
            return false;
        }
    }

    /**
     * Метод убирает лишние пробелы и понижает регистр переданной строчки
     *
     * @param $string
     * @return string
     */
    public static function cleanWordForSearchQuery($string)
    {
        return mb_strtolower(trim($string), 'utf-8');
    }

    /**
     * Метод получает массив слов для поиска, проверяет, есть ли среди этих слов
     * строки, соответсвующие видам деятельности (со слешем) и если есть, разбивает
     * такие строчки на индивидуальные слова
     *
     * @param $words
     * @return array
     */
    public static function injectIndustryWords($words)
    {
        $newArray = array();
        foreach ($words as $key => $word) {
            if (preg_match('@\/@', $word)) {
                $exploded = explode('/', $word);
                foreach ($exploded as $newWord) {
                    $newArray[] = Solution::cleanWordForSearchQuery($newWord);
                }
            } else {
                $newArray[] = $word;
            }
        }
        return $newArray;
    }

    /**
     * Метод проверяет слова на наличие видов действительности, и если есть,
     * возвращяет англоязычные клиючи в виде списка
     *
     * @param $words
     * @return array
     */
    public static function getListOfIndustryKeys($words)
    {
        $flippedDict = Solution::flipIndustryDictionary();
        $industries = array();
        foreach ($words as $key => $word) {
            if (preg_match('@\/@', $word)) {
                if (isset($flippedDict[$word])) {
                    $industries[] = $flippedDict[$word];
                }
            }
        }
        return $industries;
    }

    /**
     * Метод строит структуру для поискового запроса с поисковыми словами
     *
     * @param $wordsArray
     * @param $industriesArray
     * @param $tagsIds
     * @param int $page
     * @param int $limit
     * @param $orderArgument
     * @return array
     */
    public static function buildSearchQuery($wordsArray, $industriesArray, $tagsIds, $page = 1, $limit = 28, $orderArgument = null)
    {
        // Разделенные поиск
        $regexp = implode($wordsArray, '|');
        $descriptionWord = implode($wordsArray, ' ');
        // точный поиск
        $regexpFull = '[[:<:]]' . implode($wordsArray, ' ') . '[[:>:]]';
        $narrow = false;
        // если слово - исключение, делаем точный поиск
        if (in_array(mb_strtolower($regexp, 'UTF-8'), self::$logosaleNarrowSearches)) {
            $narrow = true;
        }
        if ((!$orderArgument) || (count($orderArgument) != 3)) {
            $order = array('Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc');
        } else {
            $order = array();
            foreach ($orderArgument as $field) {
                $order['Solution.' . $field] = 'desc';
            }
        }
        $params = array('conditions' => array(
            array('OR' => array(
            )),
            'Solution.multiwinner' => 0,
            'Solution.awarded' => 0,
            'Solution.selected' => 1,
            'Pitch.awardedDate' => array('<' => date('Y-m-d H:i:s', time() - MONTH)),
            'Pitch.status' => array('>' => 1),
            'Pitch.private' => 0,
            'Pitch.category_id' => 1,
            'Solution.rating' => array('>=' => 3)
        ),
            'order' => $order,
            'with' => array('Pitch', 'Solutiontag'));
        if (!$narrow) {
            $params['conditions'][0]['OR'][] = array("Pitch.title REGEXP '" . $regexp . "'");
            $params['conditions'][0]['OR'][] = array("Pitch.description LIKE '%$descriptionWord%'");
            $params['conditions'][0]['OR'][] = array("'Pitch.business-description' LIKE '%$descriptionWord%'");
        } else {
            $params['conditions'][0]['OR'][] = array("Pitch.title REGEXP '$regexpFull'");
            $params['conditions'][0]['OR'][] = array("Pitch.description REGEXP '$regexpFull'");
            $params['conditions'][0]['OR'][] = array("'Pitch.business-description' REGEXP '$regexpFull'");
        }
        if (!empty($industriesArray)) {
            $params['conditions'][0]['OR'][] = array("Pitch.industry LIKE '%" . $industriesArray[0] . "%'");
        }
        if ($tagsIds) {
            $tags = implode($tagsIds, ', ');
            $params['conditions'][0]['OR'][] = array("Solutiontag.tag_id IN($tags)");
        }
        if ($page) {
            $params['page'] = $page;
        }
        if ($limit) {
            $params['limit'] = $limit;
        }
        return $params;
    }

    /**
     * Метод строит поисковую структуру для потокового отображения
     *
     * @param int $page
     * @param int $limit
     * @param array|null $orderArgument
     * @return array
     */
    public static function buildStreamQuery($page = 1, $limit = 28, $orderArgument = null)
    {
        if ((!$orderArgument) || (count($orderArgument) != 3)) {
            $order = array('Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc');
        } else {
            $order = array();
            foreach ($orderArgument as $field) {
                $order['Solution.' . $field] = 'desc';
            }
        }
        $params = array(
            'conditions' =>
                array(
                    'Solution.multiwinner' => 0,
                    'Solution.awarded' => 0,
                    'Solution.selected' => 1,
                    'Pitch.awardedDate' => array('<' => date('Y-m-d H:i:s', time() - MONTH)),
                    'Pitch.status' => array('>' => 1),
                    'private' => 0,
                    'category_id' => 1,
                    'rating' => array('>=' => 3)
                ),
            'order' => $order,
            'with' => array('Pitch'),
            'page' => $page,
            'limit' => $limit);
        return $params;
    }

    /**
     * Метод считает количество логотипов, доступных для распродажи, хранит информацию в кэше 1 день
     *
     * @return bool|mixed
     */
    public static function solutionsForSaleCount()
    {
        if (!$totalCount = Rcache::read('logosale_totalcount')) {
            $countParams = array('conditions' => array('Solution.multiwinner' => 0, 'Solution.awarded' => 0, 'Solution.selected' => 1, 'private' => 0, 'category_id' => 1, 'rating' => array('>=' => 3)), 'order' => array('created' => 'desc'), 'with' => array('Pitch'));
            $totalCount =  Solution::count($countParams);
            Rcache::write('logosale_totalcount', $totalCount, '+1 day');
        }
        return $totalCount;
    }

    /**
     * Метод возвращяет случайный член списка для рандомизации запроса
     * @return array
     */
    public static function randomizeStreamOrder()
    {
        $array = array('likes', 'views', 'rating');
        shuffle($array);
        return $array;
    }

    public static function getUsersSolutions($userId, $selectedOnly = false)
    {
        $conditions = array('Solution.user_id' => $userId, 'Pitch.multiwinner' => 0);
        if ($selectedOnly) {
            $conditions['Solution.selected'] = 1;
        }
        $selectedSolutions = self::all(array(
            'conditions' => $conditions,
            'with' => array('Pitch', 'Solutiontag'),
            'order' => array('Solution.awarded' => 'desc', 'Solution.id' => 'desc')
        ));
        return $selectedSolutions;
    }

    /**
     * Метод определяет, годится ли решение для распродажи
     *
     * @param $solution
     * @param $pitch
     * @return bool
     */
    public static function isReadyForLogosale($solution, $pitch)
    {
        if (!Pitch::isReadyForLogosale($pitch)) {
            return false;
        }
        if ((($solution->rating >= 3) && ($pitch->awarded != $solution->id)) && ($solution->awarded == 0)) {
            return true;
        }
        return false;
    }
}

function in_array_r($needle, $haystack, $strict = false)
{
    foreach ($haystack as $k => $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return $k;
        }
    }
    return false;
}
