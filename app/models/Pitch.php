<?php

namespace app\models;

/*
  use \lithium\util\Validator;
  use \lithium\util\String;
 */

use \lithium\storage\Session;
use \app\models\Addon;
use \app\models\Category;
use \app\models\Event;
use \app\models\Expert;
use \app\models\User;
use \app\models\Task;
use \app\models\Promoted;
use \app\models\Promocode;
use \app\models\Grade;
use \app\models\Transaction;
use \app\models\Paymaster;
use \app\models\Receipt;
use \app\models\Wincomment;
use \app\extensions\helper\NumInflector;
use \app\extensions\helper\NameInflector;
use \app\extensions\helper\MoneyFormatter;
use \app\extensions\mailers\SpamMailer;
use \app\extensions\helper\PdfGetter;

class Pitch extends \app\models\AppModel {

    public $belongsTo = array('Category', 'User');
    public $hasMany = array('Solution');

    /**
     * @var array Валидные строчки для определения типа сортировки решений
     */
    public $validSorts = array('rating', 'created', 'likes', 'number');
    public static $attaches = array('files' => array(
            'validateFile' => array(
                'extensionForbid' => array('php', 'exe', 'sh', 'js'),
            ),
            'moveFile' => array('preserveFileName' => false, 'path' => '/webroot/pitchfiles/'),
            'setPermission' => array('mode' => 0600),
    ));

    public static function __init() {
        parent::__init();
        self::applyFilter('find', function($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            $result = $self::addHumanisedTimeleft($result);
            $result = $self::addEditedBrief($result);
            $result = $self::addNoveltyStatus($result);
            return $result;
        });
        self::applyFilter('activate', function($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if ($result) {
                $params['pitch'] = Pitch::first($params['id']);
                if ($params['pitch']->referal > 0) {
                    User::fillBalance((int) $params['pitch']->referal, 500);
                }
                if (($params['pitch']->status == 0) && ($params['pitch']->brief == 0)) {
                    Event::createEvent($params['id'], 'PitchCreated', $params['user_id']);
                    // Send Tweet for Public Pitch only
                    if ($params['pitch']->private != 1) {
                        $queryString = '?utm_source=twitter&utm_medium=tweet&utm_content=new-pitch-tweet&utm_campaign=sharing';
                        $pitchUrl = 'http://www.godesigner.ru/pitches/details/' . $params['pitch']->id . $queryString;
                        $moneyFormatter = new MoneyFormatter();
                        $winnerPrice = $moneyFormatter->formatMoney($params['pitch']->price, array('suffix' => ' р.-'));
                        if (rand(1, 100) <= 50) {
                            $tweet = 'Нужен «' . $params['pitch']->title . '», вознаграждение ' . $winnerPrice . ' ' . $pitchUrl . ' #Go_Deer';
                        } else {
                            $tweet = 'За ' . $winnerPrice . ' нужен «' . $params['pitch']->title . '», ' . $pitchUrl . ' #Go_Deer';
                        }
                        User::sendTweet($tweet);
                    }
                    Task::createNewTask($params['pitch']->id, 'newpitch');
                } elseif (($params['pitch']->status == 0) && ($params['pitch']->brief == 1)) {
                    User::sendAdminBriefPitch($params);
                }
                User::sendClientSpamNewPitch($params);
                if ($params['pitch']->expert == 1) {
                    Pitch::sendExpertMail($params);
                }
            }
            return $result;
        });
        self::applyFilter('finishPitch', function($self, $params, $chain) {
            Event::createEvent($params['pitch']->id, 'PitchFinished', $params['pitch']->user_id, $params['solutions']->first()->id);
            if (Session::read('user.isAdmin') == 1) {
                $newComment = Wincomment::create();
                $newComment->user_id = Session::read('user.id');
                $newComment->created = date('Y-m-d H:i:s');
                $newComment->solution_id = $params['solutions']->first()->id;
                $newComment->text = 'Питч завершен по правилам сервиса GoDesigner.';
                $newComment->step = 3;
                $newComment->save();

                $recipient = User::first($params['pitch']->user_id);
                User::sendSpamWincomment($newComment, $recipient);
            }
            $result = $chain->next($self, $params, $chain);
            return $result;
        });
        self::applyFilter('timeoutPitch', function($self, $params, $chain) {
            $admin = User::getAdmin();
            $client = User::first($params['pitch']->user_id);
            $nameInflector = new nameInflector();
            if ($params['pitch']->expert == 0):
                $message = '@' . $nameInflector->renderName($client->first_name, $client->last_name) . ', срок питча подошел к концу! Дизайнеры больше не могут предлагать решения и оставлять комментарии! Настал момент анонсировать победителя. У вас есть 4 дня на выбор лучшего решения. Выбрав лучшее, вы получите возможность внесения поправок и время на получение исходников.';
            else:
                $message = '@' . $nameInflector->renderName($client->first_name, $client->last_name) . ', питч завершен и ожидает мнения эксперта, который в течение 2 рабочих дней выберет 3 идеи, которые лучше всего отвечают поставленной задаче. Дизайнеры больше не могут предлагать решения и оставлять комментарии!';
            endif;
            $data = array('pitch_id' => $params['pitch']->id, 'reply_to' => $client->id, 'user_id' => $admin, 'text' => $message, 'public' => 1);
            Comment::createComment($data);
            if ($params['pitch']->expert == 1) {
                Pitch::sendExpertTimeoutMail($params);
            }
            $result = $chain->next($self, $params, $chain);
            return $result;
        });
        self::applyFilter('save', function($self, $params, $chain) {
            $params['entity']->title = preg_replace('/"(.*)"/U', '«\1»', $params['entity']->title);
            $result = $chain->next($self, $params, $chain);
            return $result;
        });
    }

    public static function sendExpertMail($params) {
        $experts = unserialize($params['pitch']->{'expert-ids'});
        foreach ($experts as $expert):
            $expert = Expert::first(array('conditions' => array('id' => $expert)));
            $user = User::first($expert->user_id);
            $params['user'] = $user;
            SpamMailer::expertselected($params);
        endforeach;
        return true;
    }

    public static function sendExpertTimeoutMail($params) {
        $experts = unserialize($params['pitch']->{'expert-ids'});
        foreach ($experts as $expert):
            $expert = Expert::first(array('conditions' => array('id' => $expert)));
            $user = User::first($expert->user_id);
            $params['user'] = $user;
            SpamMailer::expertneedpostcomment($params);
        endforeach;
        return true;
    }

    public static function convertToTimeleftFormat($finishDate, $currentTime = null) {
        if (!$currentTime) {
            $currentTime = time();
        } elseif (!is_numeric($currentTime)) {
            $currentTime = strtotime($currentTime);
        }
        $diff = strtotime($finishDate) - ($currentTime);
        if ($diff < 0) {
            return '';
        }
        $days = floor($diff / DAY);
        $hours = $diff - ($days * DAY);
        $minutesString = '';
        $hoursString = '';
        $dayString = '';
        $numInflector = new NumInflector();
        if ($hours > 0) {
            $hours = floor($hours / HOUR);
            $hoursString = ' ' . $hours;
            $hourWord = $numInflector->formatString($hours, array(
                'string' => "час",
                'first' => '',
                'second' => 'а',
                'third' => 'ов'
            ));
            $hoursString .= ' ' . $hourWord;
        }

        if ($days > 0) {
            $dayWord = $numInflector->formatString($days, array('string' => array('first' => 'день', 'second' => 'дня', 'third' => 'дней')));
            $dayString = $days . ' ' . $dayWord;
        }
        if (($hours == 0) && ($days == 0)) {
            $dayString = '';
            $hoursString = '';
            $minutes = floor($diff / MINUTE);
            $minuteWord = $numInflector->formatString($minutes, array('string' => array('first' => 'минута', 'second' => 'минуты', 'third' => 'минут')));
            $dayString = $minutes . ' ' . $minuteWord;
        }


        $string = $dayString . $hoursString . $minutesString;
        return trim($string);
    }

    public static function getNumOfSolutionsPerProject() {
        $result = self::find('all', array(
                    'fields' => array('AVG(ideas_count) as averageCount'),
                    'conditions' => array('published' => 1)
        ));
        return ceil($result->first()->averageCount);
    }

    public static function getNumOfSolutionsPerProjectOfCategory($category_id) {
        $result = self::find('all', array(
                    'fields' => array('AVG(ideas_count) as averageCount'),
                    'conditions' => array(
                        'published' => 1,
                        'category_id' => $category_id,
                        'started' => array('>' => date('Y-m-d H:i:s', time() - (365 * DAY)))
                    )
        ));
        return ceil($result->first()->averageCount);
    }

    public static function getNumOfCurrentPitches() {
        $result = self::all(array(
                    'conditions' => array(
                        'published' => 1,
                        'status' => array('<' => 2),
                    ),
                    'fields' => array('id', 'Solution.nominated'),
                    'with' => array('Solution'),
        ));
        $result = $result->data();
        foreach ($result as $key => $pitch) {
            $delete = false;
            array_walk_recursive($pitch, function($item, $idx) use (&$delete) {
                if (($idx == 'nominated') && ($item == 1)) {
                    $delete = true;
                }
            });
            if ($delete) {
                unset($result[$key]);
            }
        }
        return count($result);
    }

    public static function getTotalAwards() {
        $result = self::find('all', array(
                    'fields' => array('SUM(price) as total'),
                    'conditions' => array('published' => 1, 'status' => 2))
        );
        return round($result->first()->total);
    }

    public static function getTotalWaitingForClaim() {
        $result = self::find('all', array(
                    'fields' => array('SUM(price) as total'),
                    'conditions' => array('published' => 1, 'status' => array('<' => 2)))
        );
        return round($result->first()->total);
    }

    public static function getTotalAwardsValue() {
        $result = self::find('all', array(
                    'fields' => array('SUM(price) as total'),
                    'conditions' => array('published' => 1))
        );
        return round($result->first()->total);
    }

    public static function activate($id) {
        if ($pitch = self::first($id)) {
            $user_id = $pitch->user_id;
            $params = compact('id', 'user_id', 'pitch');
            return static::_filter(__FUNCTION__, $params, function($self, $params) {
                        extract($params);
                        if ($pitch->status == 0) {
                            $category = Category::first($pitch->category_id);
                            if ($pitch->timelimit == 0) {
                                $modifier = $category->default_timelimit;
                            } elseif ($pitch->timelimit == 1) {
                                $modifier = $category->shortTimelimit;
                            } elseif ($pitch->timelimit == 2) {
                                $modifier = $category->shortestTimelimit;
                            } elseif ($pitch->timelimit == 3) {
                                $modifier = $category->smallIncreseTimelimit;
                            } elseif ($pitch->timelimit == 4) {
                                $modifier = $category->largeIncreaseTimelimit;
                            }
                            if ($pitch->published == 0) {
                                $pitch->started = date('Y-m-d H:i:s');
                                $pitch->finishDate = date('Y-m-d H:i:s', time() + (DAY * $modifier));
                                $pitch->billed = 1;
                                if ($pitch->brief) {
                                    $pitch->published = 0;
                                } else {
                                    $pitch->published = 1;
                                }
                            } else {
                                $pitch->billed = 1;
                            }
                        } else {
                            $pitch->billed = 1;
                        }
                        return $pitch->save();
                    });
        }
        return false;
    }

    public static function timeoutPitches() {
        $pitches = self::all(array('conditions' => array(
                        'status' => 0,
                        'finishDate' => array('<' => date('Y-m-d H:i:s')),
                        'published' => 1
        )));
        $count = 0;
        foreach ($pitches as $pitch) {
            self::timeoutPitch($pitch);
            $count++;
        }
        return $count;
    }

    public static function timeoutPitch($pitch) {
        $params = compact('pitch');
        return static::_filter(__FUNCTION__, $params, function($self, $params) {
                    extract($params);
                    $pitch->status = 1;
                    $pitch->save();
                });
    }

    public static function addHumanisedTimeleft($result) {
        if (is_object($result)) {
            $addHumanDate = function($record) {
                if (isset($record->finishDate)) {
                    $record->startedHuman = Pitch::convertToTimeleftFormat($record->finishDate);
                }
                return $record;
            };
            if (get_class($result) == 'lithium\data\entity\Record') {
                $result = $addHumanDate($result);
            } else {
                foreach ($result as $foundItem) {
                    $foundItem = $addHumanDate($foundItem);
                }
            }
        }
        return $result;
    }

    public static function addNoveltyStatus($result) {
        if (is_object($result)) {
            $addNoveltyStatus = function($record) {
                if ((strtotime($record->started) + DAY) > time()) {
                    $record->new_pitch = 1;
                } else {
                    $record->new_pitch = 0;
                }
                return $record;
            };
            if (get_class($result) == 'lithium\data\entity\Record') {
                $result = $addNoveltyStatus($result);
            } else {
                foreach ($result as $foundItem) {
                    $foundItem = $addNoveltyStatus($foundItem);
                }
            }
        }
        return $result;
    }

    public static function addEditedBrief($result) {
        if (is_object($result)) {
            $addEditedBrief = function($record) {
                $strLengthLimit = 1100;
                if (isset($record->description)) {
                    $record->editedDescription = $record->description;
                    if (mb_strlen($record->description, 'UTF-8') > $strLengthLimit) {
                        $record->editedDescription = mb_substr($record->description, 0, $strLengthLimit, 'UTF-8') . '...</p>';
                    }
                }
                return $record;
            };
            if (get_class($result) == 'lithium\data\entity\Record') {
                $result = $addEditedBrief($result);
            } else {
                foreach ($result as $foundItem) {
                    $foundItem = $addEditedBrief($foundItem);
                }
            }
        }
        return $result;
    }

    public static function addProlong($addon) {
        if ($pitch = self::first($addon->pitch_id)) {
            $sumProlong = 1000 * $addon->{'prolong-days'};
            $pitch->price += $sumProlong;
            $timeProlong = strtotime($pitch->finishDate) + ($addon->{'prolong-days'} * DAY);
            $pitch->finishDate = date('Y-m-d H:i:s', $timeProlong);
            if ($pitch->save()) {
                Comment::createComment(array(
                    'pitch_id' => $pitch->id,
                    'user_id' => User::getAdmin(),
                    'text' => 'Дорогие друзья! Обратите внимание, что срок питча продлен до ' . date('d.m.Y', strtotime($pitch->finishDate)) . ', а размер вознаграждения увеличен.',
                    'public' => 1,
                ));
                return true;
            }
        }

        return false;
    }

    /**
     * Add Expert when Addon Activated
     */
    public static function addExpert($addon) {
        if ($pitch = self::first($addon->pitch_id)) {
            $expertsPitch = array();
            if ($pitch->expert == 1) {
                $expertsPitch = unserialize($pitch->{'expert-ids'});
            }
            $expertsAddon = unserialize($addon->{'expert-ids'});
            foreach ($expertsAddon as $expertId) {
                if (!in_array($expertId, $expertsPitch)) {
                    $expertsPitch[] = (int) $expertId;
                }
            }
            $pitch->expert = 1;
            $pitch->{'expert-ids'} = serialize($expertsPitch);
            $pitch->save();
        }
        return true;
    }

    /**
     * Add Brief when Addon Activated
     */
    public static function addBrief($addon) {
        if ($pitch = self::first($addon->pitch_id)) {
            $pitch->brief = 1;
            $pitch->save();
        }
        return true;
    }

    public static function finishPitch($pitchId) {
        $solutions = Solution::all(array(
                    'conditions' => array('pitch_id' => $pitchId, 'nominated' => 1, 'awarded' => 0),
        ));
        $pitch = Pitch::first($pitchId);
        if (count($solutions) == 1) {
            $params = compact('pitch', 'solutions');
            return static::_filter(__FUNCTION__, $params, function($self, $params) {
                        extract($params);
                        $pitch->status = 2;
                        $pitch->awarded = $solutions->first()->id;
                        $pitch->totalFinishDate = date('Y-m-d H:i:s');
                        $pitch->save();
                        return true;
                    });
        }
        return false;
    }

    public static function increaseIdeasCountOne($pitchId) {
        $pitch = Pitch::first($pitchId);
        $pitch->ideas_count += 1;
        return $pitch->save();
    }

    public static function decreaseIdeasCountOne($pitchId) {
        $pitch = Pitch::first($pitchId);
        $pitch->ideas_count -= 1;
        return $pitch->save();
    }

    public static function apiGetPitch($categoryId = null) {
        $fetch = false;
        $catConditions = array();
        if (!is_null($categoryId)) {
            if (preg_match('@,@', $categoryId)) {
                $ids = (explode(',', $categoryId));
                foreach ($ids as &$id) {
                    $id = trim($id);
                }
            }
            $categories = Category::all();
            $catIds = array_keys($categories->data());

            if (isset($ids)) {
                foreach ($ids as $checkId) {
                    if (!in_array($checkId, $catIds)) {
                        $categoryId = null;
                    }
                }
                if (!is_null($categoryId)) {
                    $categoryId = array();
                    foreach ($ids as $searchId) {
                        $categoryId[] = $searchId;
                    }
                }
            } else {
                if (!in_array($categoryId, $catIds)) {
                    $categoryId = null;
                }
            }
        }
        if ($categoryId) {
            $catConditions = array('category_id' => $categoryId);
        }

        $count = 0;
        while ($fetch == false):

            $pitch = Pitch::first(array(
                        'conditions' => array_merge(array('status' => 0, 'category_id' => array('!=' => 7), 'published' => 1, 'private' => 0), $catConditions),
                        'order' => array('RAND()'),
                        'with' => array('Solution')
            ));

            if (($pitch) && (!is_null($pitch->solutions->first()->id))) {
                $fetch = true;
            }
            $count ++;
            if ($count > 10) {
                $solution = Solution::first(array('order' => array('created' => 'desc')));
                $pitch = Pitch::first(array(
                            'conditions' => array('status' => 0, 'published' => 1, 'private' => 0, 'Pitch.id' => $solution->pitch_id),
                            'with' => array('Solution')
                ));
                $pitch->latestSolution = $solution;
                return $pitch->data();
            }
        endwhile;
        $highestId = 0;
        $latestSolution = null;
        foreach ($pitch->solutions as $solution) {
            if ($highestId < (int) $solution->id) {
                $highestId = $solution->id;
                $latestSolution = $solution;
            }
        }
        $pitch->latestSolution = Solution::first($latestSolution->id);
        return $pitch->data();
    }

    public static function promospam() {
        $pitches = Pitch::all(array('conditions' => array('published' => 1, 'status' => 2, 'started' => array('>' => '2013-01-01 00:00::00'))));
        $count = 0;
        foreach ($pitches as $pitch) {
            $promoted = Promoted::first(array('conditions' => array('pitch_id' => $pitch->id)));
            if (!$promoted) {
                $grade = Grade::first(array('conditions' => array('pitch_id' => $pitch->id, 'user_id' => $pitch->user_id)));
                if (($grade) && (($grade->site_rating == 4) || ($grade->site_rating == 5))) {
                    $promocode = Promocode::first(array('conditions' => array('pitch_id' => $pitch->id)));
                    if (!$promocode) {
                        $count ++;
                        User::sendPromoCode($pitch->user_id);
                        $promoted = Promoted::create();
                        $promoted->set(array('pitch_id' => $pitch->id));
                        $promoted->save();
                    }
                }
            }
        }
        return $count;
    }

    public static function dailypitch() {
        $pitches = Pitch::all(array('conditions' => array('published' => 1, 'started' => array('>=' => date('Y-m-d H:i:s', time() - DAY)))));
        if (count($pitches) > 0) {
            $users = User::all(array('conditions' => array('email_newpitchonce' => 1, 'User.email' => array('!=' => ''))));
            foreach ($users as $user) {
                User::sendDailyPitch($user, $pitches);
            }
        } else {
            $users = array();
        }
        return count($users);
    }

    public static function openLetter() {
        $pitches = Pitch::all(array(
                    'conditions' => array(
                        'published' => 1,
                        'started' => array(
                            '>=' => date('Y-m-d H:i:s', time() - DAY - HOUR),
                            '<=' => date('Y-m-d H:i:s', time() - DAY),
                        ),
                    ),
                    'with' => array('User'),
        ));
        $res = array(
            'all' => count($pitches),
            'sent' => 0,
        );
        if ($res['all'] > 0) {
            foreach ($pitches as $pitch) {
                if (User::sendOpenLetter($pitch)) {
                    $res['sent'] ++;
                }
            }
        }

        return $res;
    }

    public static function addonBriefLetter($time) {
        $conditions = array(
            'brief' => 0,
        );
        $conditions += self::getAddonConditions($time);
        $pitches = self::all(array(
                    'conditions' => $conditions,
                    'with' => array('User'),
        ));
        $res = 0;
        if (count($pitches)) {
            foreach ($pitches as $pitch) {
                if (Addon::first(array('conditions' => array('pitch_id' => $pitch->id, 'brief' => 1)))) {
                    continue;
                }
                if (User::sendAddonBrief($pitch)) {
                    $res++;
                }
            }
        }
        return $res;
    }

    public static function addonProlongLetter($time) {
        $conditions = self::getAddonConditions($time);
        $pitches = self::all(array(
                    'conditions' => $conditions,
                    'with' => array('User'),
        ));
        $res = 0;
        if (count($pitches)) {
            foreach ($pitches as $pitch) {
                if (User::sendAddonProlong($pitch)) {
                    $res++;
                }
            }
        }
        return $res;
    }

    public static function addonExpertLetter($time) {
        $conditions = array(
            'expert' => 0,
        );
        $conditions += self::getAddonConditions($time);
        $pitches = self::all(array(
                    'conditions' => $conditions,
                    'with' => array('User'),
        ));
        $res = 0;
        if (count($pitches)) {
            foreach ($pitches as $pitch) {
                if (Addon::first(array('conditions' => array('pitch_id' => $pitch->id, 'experts' => 1)))) {
                    continue;
                }
                if (User::sendAddonExpert($pitch)) {
                    $res++;
                }
            }
        }
        return $res;
    }

    public static function ExpertReminder() {
        $conditions = array(
            'expert' => 1,
            'status' => 1,
            'awarded' => 0,
            'finishDate' => array(
                '>=' => date('Y-m-d H:i:s', time() - 62 * HOUR),
                '<' => date('Y-m-d H:i:s', time() - 61 * HOUR),
            ),
        );
        $pitches = self::all(array(
                    'conditions' => $conditions,
        ));
        $res = 0;
        if (count($pitches)) {
            foreach ($pitches as $pitch) {
                if (User::sendExpertReminder($pitch)) {
                    $res++;
                }
            }
        }
        return $res;
    }

    protected static function getAddonConditions($time) {
        if ((0 < $time) && ($time < 1)) {
            $timeCond = array(
                'TIMESTAMPADD(SECOND,(TIMESTAMPDIFF(SECOND,started,finishDate) * ' . $time . '),started)' => array(
                    '>=' => date('Y-m-d H:i:s', time() - HOUR),
                    '<' => date('Y-m-d H:i:s', time()),
                ),
            );
        }

        if ($time >= 1) {
            $timeCond = array(
                'started' => array(
                    '>=' => date('Y-m-d H:i:s', time() - DAY * $time - HOUR),
                    '<' => date('Y-m-d H:i:s', time() - DAY * $time),
                ),
            );
        }

        if ($time < 0) {
            $timeCond = array(
                'finishDate' => array(
                    '>=' => date('Y-m-d H:i:s', time() + DAY * abs($time) - HOUR),
                    '<' => date('Y-m-d H:i:s', time() + DAY * abs($time)),
                ),
            );
        }

        $conditions = array(
            'published' => 1,
            'status' => 0,
        );
        $conditions += $timeCond;
        return $conditions;
    }

    public static function generatePdfAct($options) {
        $destination = PdfGetter::findPdfDestination($options['destination']);
        $path = ($destination == 'f') ? LITHIUM_APP_PATH . '/' . 'libraries' . '/' . 'MPDF54/MPDF54/tmp/' : '';
        require_once(LITHIUM_APP_PATH . '/' . 'libraries' . '/' . 'MPDF54/MPDF54/mpdf.php');
        $mpdf = new \mPDF();
        $mpdf->WriteHTML(PdfGetter::get('Act', $options));
        return $mpdf->Output($path . 'godesigner-act-' . $options['pitch']->id . '.pdf', $destination);
    }

    public static function generatePdfReport($options) {
        $destination = PdfGetter::findPdfDestination($options['destination']);
        $path = ($destination == 'f') ? LITHIUM_APP_PATH . '/' . 'libraries' . '/' . 'MPDF54/MPDF54/tmp/' : '';
        $layout = ($options['bill']->individual == 1) ? 'Report-fiz' : 'Report-yur';
        $options['transaction'] = Transaction::first(array(
                    'conditions' => array(
                        'ORDER' => $options['pitch']->id,
                        'TRTYPE' => 21,
                    ),
        ));
        $receipt = Receipt::all(array(
                    'conditions' => array(
                        'pitch_id' => $options['pitch']->id,
                    ),
        ));
        $totalfees = 0;
        $prolongfees = 0;
        if ($addon = Addon::first(array(
                    'conditions' => array(
                        'pitch_id' => $options['pitch']->id,
                        'billed' => 1,
                    ),
                ))) {
            $totalfees = $addon->total;
            $prolongfees = ($addon->prolong == 1) ? $addon->{'prolong-days'} * 1000 : $prolongfees;
        }
        foreach ($receipt as $option) {
            if ($option->name == 'Сбор GoDesigner') {
                $options['commission'] = $option->value;
            }
            if (($option->name != 'Награда Дизайнеру') && ($option->name != 'Сбор GoDesigner')) {
                $totalfees += $option->value;
            }
        }
        $options['totalfees'] = $totalfees;
        $options['prolongfees'] = $prolongfees;
        require_once(LITHIUM_APP_PATH . '/' . 'libraries' . '/' . 'MPDF54/MPDF54/mpdf.php');
        $mpdf = new \mPDF();
        $mpdf->WriteHTML(PdfGetter::get($layout, $options));
        $mpdf->Output($path . 'godesigner-report-' . $options['pitch']->id . '.pdf', $destination);
    }

    public static function sendReports() {
        $query = array(
            'conditions' => array(
                'status' => 2,
                'totalFinishDate' => array(
                    '>=' => date('Y-m-d H:i:s', time() - 5 * MINUTE),
                ),
            ),
        );
        $res = 0;
        if ($pitches = Pitch::all($query)) {
            foreach ($pitches as $pitch) {
                if ($bill = Bill::first($pitch->id)) {
                    $destination = 'File';
                    $options = compact('pitch', 'bill', 'destination');
                    self::generatePdfReport($options);
                    if ($bill->individual != 1) {
                        self::generatePdfAct($options);
                    }
                    User::sendFinishReports($pitch);
                    $res++;
                } else {
                    echo 'No Bill Data for Pitch ' . $pitch->id . PHP_EOL;
                }
            }
        }
        return $res;
    }

    public static function isReferalAllowed($pitch) {
        if ((strtotime($pitch->started) + 60 * DAY) >= time()) {
            return true;
        }
        return false;
    }

    public static function getTransactions($pitchId) {
        $transMaster = Transaction::all(array('conditions' => array('ORDER' => $pitchId)));
        $transPay = Paymaster::all(array('conditions' => array('LMI_PAYMENT_NO' => $pitchId)));
        return compact('transMaster', 'transPay');
    }

    public static function getMultiple($category, $specifics) {
        $specifics = unserialize($specifics);
        if (!empty($specifics['site-sub'])) {
            $numInflector = new NumInflector();
            $res = '';
            switch ($category) {
                case 2:
                    //$res = (int) $specifics['site-sub'] . ' ' . $numInflector->formatString($specifics['site-sub'], array('first' => 'макет', 'second' => 'макета', 'third' => 'макетов'));
                    break;
                case 3:
                    $res = (int) $specifics['site-sub'] . ' ' . $numInflector->formatString($specifics['site-sub'], array('first' => 'страница', 'second' => 'страницы', 'third' => 'страниц'));
                    break;
                case 4:
                    //$res = (int) $specifics['site-sub'] . ' ' . $numInflector->formatString($specifics['site-sub'], array('first' => 'макет', 'second' => 'макета', 'third' => 'макетов'));
                    break;
                case 6:
                    //$res = (int) $specifics['site-sub'] . ' ' . $numInflector->formatString($specifics['site-sub'], array('first' => 'страница', 'second' => 'страницы', 'third' => 'страниц'));
                    break;
                case 9:
                    //$res = (int) $specifics['site-sub'] . ' ' . $numInflector->formatString($specifics['site-sub'], array('first' => 'иллюстрация', 'second' => 'иллюстрации', 'third' => 'иллюстраций'));
                    break;
                case 10:
                    //$res = (int) $specifics['site-sub'] . ' ' . $numInflector->formatString($specifics['site-sub'], array('first' => 'макет', 'second' => 'макета', 'third' => 'макетов'));
                    break;
                case 12:
                    //$res = (int) $specifics['site-sub'] . ' ' . $numInflector->formatString($specifics['site-sub'], array('first' => 'макет', 'second' => 'макета', 'third' => 'макетов'));
                    break;
                default:
                    $res = null;
                    break;
            }
            return $res;
        }
        return null;
    }

    // Check if Low Rating Popup needed
    public function ratingPopup($pitch, $avgArray) {
        $twoDayAvg = 0;
        if (count($avgArray) >= 3) {
            $twoDayArray = array_slice($avgArray, -3, 2);
            $twoDayAvg = round(array_sum($twoDayArray) / 2, 1);
        }
        if (($pitch->guaranteed == 0) && (Session::read('user.id') == $pitch->user_id) && ($twoDayAvg < 3) && ($twoDayAvg != 0) && (!isset($_COOKIE['ratPop_' . $pitch->id]) || $_COOKIE['ratPop_' . $pitch->id] == '')) {
            setcookie('ratPop_' . $pitch->id, 'true', strtotime('+2 day'), '/');
            return true;
        }
        return false;
    }

    // Check if private Pitch Popup needed
    public function winnerPopup($pitch) {
        if (($pitch->private == 1) && (Session::read('user.id') != $pitch->user_id) && ($pitch->category_id != 7)) {
            // For Winner
            if ((User::getAwardedSolutionNum(Session::read('user.id')) >= WINS_FOR_VIEW) && (!isset($_COOKIE['winPop']) || $_COOKIE['winPop'] != 'win')) {
                setcookie('winPop', 'win', time() + YEAR, '/');
                return 'win';
            }
            // For Loser
            if ((User::getAwardedSolutionNum(Session::read('user.id')) == 0) && (!isset($_COOKIE['winPop']) || $_COOKIE['winPop'] != 'los')) {
                setcookie('winPop', 'los', time() + YEAR, '/');
                return 'los';
            }
        }
        return false;
    }

    /**
     * Метод возвращяет объект пользователя-владельца питча по номеру питча $pitchId
     *
     * @param $pitchId
     * @return mixed
     */
    public static function getOwnerOfPitch($pitchId) {
        if ($pitchData = self::first(array('fields' => array('user_id'), 'conditions' => array('id' => $pitchId)))) {
            return User::first($pitchData->user_id);
        }
    }

    /**
     * Метод возвращает массив для запроса сортировки решений, применяется для объекта $pitch
     *
     * @param $pitch
     * @sorting array|string массив с ключем sorting или строка
     * @return array
     */
    public function getSolutionsSortingOrder($pitch, $type = null) {
        if ($result = $this->__getSortingString($type)) {
            switch ($result) {
                case 'rating':
                    $array = array('awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created' => 'desc');
                    break;
                case 'created':
                    $array = array('awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc');
                    break;
                case 'likes':
                    $array = array('awarded' => 'desc', 'nominated' => 'desc', 'likes' => 'desc', 'created' => 'desc');
                    break;
                default:
                    $array = array('awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc');
                    break;
            }
            if (Session::read('user.id') == $pitch->user_id) {
                $array = array_merge($array, array('awarded' => 'desc', 'hidden' => 'asc'));
                $array = array_slice($array, 0, 1, true) +
                        array('hidden' => 'asc') +
                        array_slice($array, 1, null, true);
            }
            return $array;
        } else {
            if ((Session::read('user.id') == $pitch->user_id) && (strtotime($pitch->finishDate) > time()) && ($pitch->status == 0)) {
                return array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc');
            } elseif ((Session::read('user.id') == $pitch->user_id) || ($pitch->status > 0)) {
                return array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created' => 'desc');
            } else {
                return array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc');
            }
        }
    }

    public function getDesignersSortingOrder($pitch, $type = null) {
        if ($result = $this->__getSortingString($type)) {
            switch ($result) {
                case 'rating':
                    $array = array('rating' => 'desc', 'created' => 'desc');
                    break;
                case 'created':
                    $array = array('created' => 'desc');
                    break;
                case 'number':
                    $array = array('Num' => 'desc', 'created' => 'desc');
                    break;
            }
            return $array;
        } else {
            if ((Session::read('user.id') == $pitch->user_id) && (strtotime($pitch->finishDate) > time()) && ($pitch->status == 0)) {
                return array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc');
            } elseif ((Session::read('user.id') == $pitch->user_id) || ($pitch->status > 0)) {
                return array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created' => 'desc');
            } else {
                return array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc');
            }
        }
    }

    /**
     * Метод возвращает названия сортировки для текущего пользователя и питча.
     *
     * @param $pitch
     * @param array|string $type - массив с ключем sorting или строка
     * @return null|string
     */
    public function getSolutionsSortName($pitch, $type = null) {
        if ($result = $this->__getSortingString($type)) {
            return $result;
        } else {
            if ((Session::read('user.id') == $pitch->user_id) && (strtotime($pitch->finishDate) > time()) && ($pitch->status == 0)) {
                return 'created';
            } elseif ((Session::read('user.id') == $pitch->user_id) || ($pitch->status > 0)) {
                return 'rating';
            } else {
                return 'created';
            }
        }
    }

    private function __getSortingString($param) {
        if ($param and is_array($param) and isset($param['sorting'])) {
            $param = $param['sorting'];
        }
        if (($param) and ( is_string($param)) and ( in_array($param, $this->validSorts))) {
            return $param;
        }
        return false;
    }

    public function pitchData($pitch) {
        $pitchId = $pitch->id;
        $award = $pitch->price;
        $category = $pitch->category;
        $money = 3;
        if ($award >= $category->normalAward) {
            $money = 4;
        } else if ($award >= $category->goodAward) {
            $money = 5;
        }
        $begin = new \DateTime($pitch->started);
        if (strtotime($pitch->finishDate) > time()) {
            $end = new \DateTime(date('Y-m-d', time() + DAY));
        } else {
            $end = new \DateTime(date('Y-m-d', strtotime($pitch->finishDate)));
        }
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        $ratingArray = array();
        $moneyArray = array();
        $commentArray = array();
        $dates = array();
        $pitch->firstSolution = Solution::first(array('conditions' => array('pitch_id' => $pitchId), 'order' => array('created' => 'asc')));
        if ($pitch->firstSolution) {
            $pitch->firstSolutionTime = strtotime($pitch->firstSolution->created);
        }
        foreach ($period as $dt) {
            $time = strtotime($dt->format('Y-m-d'));
            $plusDay = date('Y-m-d H:i:s', $time + DAY);
            if (strtotime($pitch->created) > strtotime('2013-03-25 00-00-00')) {
                $solutions = Historysolution::all(array('conditions' => array('pitch_id' => $pitchId, 'date(created)' => array('<' => $plusDay))));
            } else {
                $solutions = Solution::all(array('conditions' => array('pitch_id' => $pitchId, 'date(created)' => array('<' => $plusDay))));
            }
            $ids = array();
            foreach ($solutions as $solution) {
                $ids[] = $solution->id;
            }
            $dates[] = $dt->format('d/m');
            $moneyArray[] = $money;
            $ratingArray[] = $this->calcRating($ids, $pitch, $plusDay, $dt);
            $commentArray[] = $this->calcComments($ids, $pitch, $plusDay, $dt);
        }

        $ratingAverage = (empty($ratingArray)) ? 0 : round(array_sum($ratingArray) / count($ratingArray), 1);
        $moneyAverage = (empty($moneyArray)) ? 0 : round(array_sum($moneyArray) / count($moneyArray), 1);
        $commentAverage = (empty($commentArray)) ? 0 : round(array_sum($commentArray) / count($commentArray), 1);
        $percentages = array(
            'rating' => round(($ratingAverage / 15) * 100),
            'money' => round(($moneyAverage / 15) * 100),
            'comment' => round(($commentAverage / 15) * 100),
        );
        $total = 0;
        foreach ($percentages as $key => $value) {
            $total += $value;
        }
        $percentages['empty'] = 100 - $total;
        $avgArray = $this->calcAvg($ratingArray, $moneyArray, $commentArray);
        $avgNum = (empty($avgArray)) ? 0 : round(array_sum($avgArray) / count($avgArray), 1);
        $guaranteed = $pitch->guaranteed;

        return compact('guaranteed', 'dates', 'ratingArray', 'moneyArray', 'commentArray', 'avgArray', 'avgNum', 'percentages', 'commentsNum');
    }

    private function calcAvg($first, $second, $third) {
        $avgArray = array();
        for ($i = 0; $i < count($first); $i++) {
            $avg = round((($first[$i] + $second[$i] + $third[$i]) / 3), 1);
            $avgArray[] = $avg;
        }
        return $avgArray;
    }

    public function calcRating($ids, $pitch, $plusDay, $dt) {
        if (!empty($ids)) {
            $ratingsNum = Ratingchange::all(array('conditions' => array('solution_id' => $ids, 'user_id' => $pitch->user_id, 'date(created)' => array('<' => $plusDay))));
        } else {
            $ratingsNum = array();
        }
        $rating = 0;
        $percents = 0;
        if (count($ids) > 0) {
            $percents = (count($ratingsNum) / count($ids)) * 100;
        }
        if ($percents > 100) {
            $percents = 100;
        }
        switch ($percents) {
            case $percents < 50: $rating = 1;
                break;
            case $percents < 63: $rating = 2;
                break;
            case $percents < 79: $rating = 3;
                break;
            case $percents < 89: $rating = 4;
                break;
            case $percents <= 100: $rating = 5;
                break;
        }
        $diff = strtotime(date('Y-m-d', $pitch->firstSolutionTime)) + DAY - $pitch->firstSolutionTime;
        if ((!$pitch->firstSolution) || (($pitch->firstSolution) && ($pitch->firstSolutionTime > strtotime($dt->format('Y-m-d H:i:s')) + $diff))) {
            $rating = 3;
        }

        return $rating;
    }

    public function calcComments($ids, $pitch, $plusDay, $dt) {
        if (!empty($ids)) {
            if (strtotime($pitch->created) > strtotime('2013-03-24 18:00:00')) {
                $commentsNum = Historycomment::all(array('conditions' => array('pitch_id' => $pitch->id, 'user_id' => $pitch->user_id, 'date(created)' => array('<' => $plusDay))));
            } else {
                $commentsNum = Comment::all(array('conditions' => array('pitch_id' => $pitch->id, 'user_id' => $pitch->user_id, 'date(created)' => array('<' => $plusDay))));
            }
        } else {
            $commentsNum = array();
        }

        $comments = 0;
        $percents = 0;
        if (count($ids) > 0) {
            $percents = (count($commentsNum) / count($ids)) * 100;
        }

        if ($percents > 100) {
            $percents = 100;
        }
        switch ($percents) {
            case $percents < 50: $comments = 1;
                break;
            case $percents < 63: $comments = 2;
                break;
            case $percents < 79: $comments = 3;
                break;
            case $percents < 89: $comments = 4;
                break;
            case $percents <= 100: $comments = 5;
                break;
        }
        $diff = strtotime(date('Y-m-d', $pitch->firstSolutionTime)) + DAY - $pitch->firstSolutionTime;
        if ((!$pitch->firstSolution) || (($pitch->firstSolution) && ($pitch->firstSolutionTime > strtotime($dt->format('Y-m-d H:i:s')) + $diff))) {
            $comments = 3;
        }
        return $comments;
    }

    /**
     * Метод возвращает номер страницы
     *
     * @param $page
     * @return integer
     */
    public static function getQueryPageNum($page = 1) {
        $page = abs(intval($page));
        if ($page == 0)
            $page = 1;
        return $page;
    }

    /**
     * Метод возвращает ценовой диапазон
     *
     * @param $priceFilter
     * @return array
     */
    public static function getQueryPriceFilter($priceFilter = 0) {
        switch ($priceFilter) {
            case 1:
                $result = array('price' => array('>' => 3000, '<=' => 10000));
                break;
            case 2:
                $result = array('price' => array('>' => 10000, '<=' => 20000));
                break;
            case 3:
                $result = array('price' => array('>' => 20000));
                break;
            default:
                $result = array();
        }
        return $result;
    }

    /**
     * Метод возвращает время размещения питча
     *
     * @param $timeframe
     * @return array
     */
    public static function getQueryTimeframe($timeframe = 0) {
        switch ($timeframe) {
            case 1:
                $result = array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 3))));
                break;
            case 2:
                $result = array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 7))));
                break;
            case 3:
                $result = array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 10))));
                break;
            case 4:
                $result = array('finishDate' => array('=>' => date('Y-m-d H:i:s', time() + (DAY * 14))));
                break;
            default:
                $result = array();
        }
        return $result;
    }

    /**
     * Метод возвращает массив ключевых слов для поиска
     *
     * @param $search
     * @return array
     */
    public static function getQuerySearchTerm($search = '') {
        if ((is_string($search) && !empty($search)) && $search != 'НАЙТИ ПИТЧ ПО КЛЮЧЕВОМУ СЛОВУ ИЛИ ТИПУ') {
            $word = urldecode(filter_var($search, FILTER_SANITIZE_STRING));
            $firstLetter = mb_substr($word, 0, 1, 'utf-8');
            $firstUpper = (mb_strtoupper($firstLetter, 'utf-8'));
            $firstLower = (mb_strtolower($firstLetter, 'utf-8'));
            $string = $firstLower . mb_substr($word, 1, mb_strlen($word, 'utf-8'), 'utf-8') . '|' . $firstUpper . mb_substr($word, 1, mb_strlen($word, 'utf-8'), 'utf-8') . '|' . mb_strtoupper($word, 'utf-8');
            $search = array('Pitch.title' => array('REGEXP' => $string));
        } else {
            $search = array();
        }
        return $search;
    }

    /**
     * Метод возвращает массив для сортировки полей
     *
     * @param $order
     * @return array
     */
    public static function getQueryOrder($order) {
        $allowedOrder = array('price', 'finishDate', 'ideas_count', 'title', 'category', 'started');
        $allowedSortDirections = array('asc', 'desc');
        $trigger = is_array($order);
        $field = $trigger ? key($order) : '';
        $dir = $trigger ? current($order) : '';
        if ($trigger && ((in_array($field, $allowedOrder)) && (in_array($dir, $allowedSortDirections)))) {
            switch ($field) {
                case 'category':
                    $order = array('category_id' => $dir, 'started' => 'desc');
                    break;
                case 'finishDate':
                    $order = array('(finishDate - \'' . date('Y-m-d H:i:s') . '\')' => $dir);
                    break;
                case 'price':
                    $order = array('free' => 'desc', $field => $dir, 'started' => 'desc');
                    break;
                default:
                    $order = array($field => $dir, 'started' => 'desc');
            }
        } else {
            $order = array('free' => 'desc', 'price' => 'desc', 'started' => 'desc');
        }
        return $order;
    }

    /**
     * Метод возвращает id категории
     *
     * @param $category
     * @return array
     */
    public static function getQueryCategory($category) {
        $categories = Category::all();
        foreach ($categories as $cat) {
            $allowedCategories[] = $cat->id;
        }
        if (!empty($category) && in_array($category, $allowedCategories)) {
            $category = array('category_id' => $category);
        } else {
            $category = array();
        }
        return $category;
    }

    /**
     * Метод возвращает данные для поиска по типу питча
     *
     * @param $types
     * @return array
     */
    public static function getQueryType($types) {
        switch ($types) {
            case 'finished':
                $result = array('OR' => array(array('status = 2'), array('(status = 1 AND awarded > 0)')));
                break;
            case 'current':
                $result = array('status' => array('<' => 2), 'awarded' => 0);
                break;
            case 'all':
                $result = array();
                break;
            default:
                $result = array(
                    'OR' => array(
                        array('awardedDate >= \'' . date('Y-m-d H:i:s', time() - DAY) . '\''),
                        array('status < 2 AND awarded = 0'),
                ));
        }
        return $result;
    }

    /**
     * Метод возвращает питчи для главной страницы
     *
     * @return array
     */
    public static function getPitchesForHomePage() {
        return Pitch::all(array(
                    'order' => array(
                        'pinned' => 'desc',
                        'ideas_count' => 'desc',
                        'price' => 'desc'
                    ),
                    'conditions' => array('status' => array('<' => 1), 'published' => 1),
                    'limit' => 3,
                    'page' => 1,
        ));
    }

    public static function createNewWinner($solutionId) {
        if ($solution = Solution::first(array(
                    'conditions' => array('Solution.id' => $solutionId),
                    'with' => array('Pitch'),
                ))) {
            $copyPitch = Pitch::create();
            $data = $solution->pitch->data();
            $data['billed'] = 0;
            $data['published'] = 0;
            $data['multiwinner'] = 1;
            unset($data['id']);
            $copyPitch->set($data);
            if ($copyPitch->save()) {
                $copyPitch->awarded = $copyPitch->id;
                return $copyPitch->save() ? true : false;
            }
        } else {
            return false;
        }
    }

    public static function activateNewWinner($pitchId) {
        if ($pitch = self::first(array('conditions' => array('Pitch.id' => $pitchId), 'with' => array('Solution'),))) {
            $pitch->billed = 1;
            $pitch->published = 1;
            if ($pitch->save()) {
                Task::createNewTask($pitch->solutions[0]->id, 'victoryNotification');
                return true;
            }
        } else {
            return false;
        }
    }

}
