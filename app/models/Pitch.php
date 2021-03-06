<?php

namespace app\models;

use app\extensions\billing\Payture;
use app\extensions\mailers\NotificationsMailer;
use app\extensions\mailers\UserMailer;
use app\extensions\smsfeedback\SmsUslugi;
use app\extensions\social\TwitterAPI;
use app\extensions\storage\Rcache;
use \lithium\storage\Session;
use \app\models\Addon;
use \app\models\Category;
use \app\models\Comment;
use \app\models\Event;
use \app\models\Expert;
use \app\models\User;
use \app\models\Task;
use \app\models\Note;
use \app\models\Promocode;
use \app\models\Grade;
use \app\models\Transaction;
use \app\models\Paymaster;
use \app\models\Receipt;
use \app\models\Wincomment;
use \app\models\Historysolution;
use \app\extensions\helper\NumInflector;
use \app\extensions\helper\NameInflector;
use \app\extensions\helper\MoneyFormatter;
use \app\extensions\mailers\SpamMailer;
use \app\extensions\helper\PdfGetter;
use app\extensions\mailers\SolutionsMailer;
use \app\extensions\social\FacebookAPI;
use app\extensions\social\SocialMediaManager;
use lithium\data\collection\RecordSet;
use lithium\data\entity\Record;

/**
 * Class Pitch
 * @package app\models
 * @method Record|null first(int|array $conditions) static
 * @method int count(array $conditions) static
 * @method RecordSet|null all(array $conditions) static
 */
class Pitch extends AppModel
{
    public $belongsTo = ['Category', 'User'];
    public $hasMany = ['Solution'];

    /**
     * @var array Валидные строчки для определения типа сортировки решений
     */
    public $validSorts = ['rating', 'created', 'likes', 'number'];
    public static $attaches = ['files' => [
            'validateFile' => [
                'extensionForbid' => ['php', 'exe', 'sh', 'js'],
            ],
            'moveFile' => ['preserveFileName' => false, 'path' => '/webroot/pitchfiles/'],
            'setPermission' => ['mode' => 0600],
    ]];

    public static function __init()
    {
        parent::__init();
        self::applyFilter('find', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            $result = $self::addHumanisedTimeleft($result);
            $result = $self::addEditedBrief($result);
            $result = $self::addNoveltyStatus($result);
            return $result;
        });
        self::applyFilter('activate', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if ($result) {
                $params['pitch'] = Pitch::first($params['id']);
                $project = $params['pitch'];
                if ($params['pitch']->referal > 0) {
                    User::fillBalance((int) $params['pitch']->referal, 500);
                }
                if ($project->type === '1on1') {
                    $details = unserialize($project->specifics);
                    $data = [
                        'user_id' => $details['designer_id'],
                        'pitch_id' => $project->id,
                        'created' => date('Y-m-d H:i:s'),
                        'description' => '1on1',
                        'awarded' => 1,
                        'nominated' => 1
                    ];
                    Solution::create($data)->save();
                    $solution = Solution::first(['conditions' => [
                        'user_id' => $details['designer_id'],
                        'pitch_id' => $project->id,
                        'description' => '1on1'
                    ]]);
                    $project->awarded = $solution->id;
                    $project->save();
                    $designer = User::first($details['designer_id']);
                    if (($designer->phone !== '') && ((int) $designer->phone_valid === 1)) {
                        $smsService = new SmsUslugi();
                        $shortUrl = 'https://godesigner.ru/urls/' . Url::getShortUrlFor("https://godesigner.ru/users/hireDesigner/$designer->id/?project=$project->id");
                        $message =  "$designer->first_name, вас хотят нанять для индивидуальной работы «1на1» $shortUrl. GoDesigner";
                        $params = [
                            'text' => $message
                        ];
                        $phones = [$designer->phone];
                        $respond = $smsService->send($params, $phones);
                        if (!isset($respond['smsid'])) {
                            $smsId = 0;
                        } else {
                            $smsId = $respond['smsid'];
                        }
                        $data = [
                            'user_id' => $designer->id,
                            'created' => date('Y-m-d H:i:s'),
                            'phone' => $designer->phone,
                            'text' => $message,
                            'status' => $respond['descr'],
                            'text_id' => $smsId
                        ];
                        TextMessage::create($data)->save();
                    }
                    $emailData = ['user' => $designer, 'pitch' => $project];
                    UserMailer::new1on1Project($emailData);
                }
                if (($params['pitch']->status == 0) && ($params['pitch']->brief == 0)) {
                    Event::createEvent($params['id'], 'PitchCreated', $params['user_id']);
                    // Send messages for Public Pitch only
                    if (((int) $params['pitch']->private === 0) && ($project->type !== '1on1') && ((int) $params['pitch']->category_id !== 22)) {
                        $mediaManager = new SocialMediaManager;
                        $mediaManager->postNewProjectMessage($params['pitch']);
                    }
                    if ($project->type !== '1on1') {
                        Task::createNewTask($params['pitch']->id, 'newpitch');
                    }
                } elseif (($params['pitch']->status == 0) && ($params['pitch']->brief == 1)) {
                    User::sendAdminBriefPitch($params);
                }
                User::sendClientSpamNewPitch($params);
                if ($params['pitch']->expert == 1) {
                    Pitch::sendExpertMail($params);
                }
                if ($params['pitch']->type === '') {
                    Lead::resetLeadForUser($params['pitch']->user_id);
                }
                $project = $params['pitch'];
                if (($project->category_id != 20) && (!empty($project->ga_id))) {
                    $options = ['client_id' => $project->ga_id, 'user_id' => $project->user_id];
                    $tracking = new \Racecore\GATracking\GATracking('UA-9235854-5', $options);

                    $transaction = $tracking->createTracking('Ecommerce\Transaction');
                    $transaction->setID($project->id);
                    $transaction->setRevenue($project->total);
                    $transaction->setCurrency('RUB');
                    $result = $tracking->sendTracking($transaction);

                    $item = $tracking->createTracking('Ecommerce\Item');
                    $item->setTransactionID($project->id);
                    $item->setName($project->title);
                    $item->setPrice($project->total);
                    $item->setQuantity(1);
                    $item->setSku($project->id . '_1');
                    $item->setCategory('Проект');
                    $item->setCurrency('RUB');
                    $result = $tracking->sendTracking($item);
                }
            }
            return $result;
        });
        self::applyFilter('finishPitch', function ($self, $params, $chain) {
            Event::createEvent($params['pitch']->id, 'PitchFinished', $params['pitch']->user_id, $params['solution']->id);
            if (Session::read('user.isAdmin') == 1) {
                $newComment = Wincomment::create();
                $newComment->user_id = Session::read('user.id');
                $newComment->created = date('Y-m-d H:i:s');
                $newComment->solution_id = $params['solution']->id;
                $newComment->text = 'Проект завершен по правилам сервиса GoDesigner.';
                $newComment->step = 3;
                $newComment->save();

                $recipient = User::first($params['pitch']->user_id);
                User::sendSpamWincomment($newComment, $recipient);
            }
            $result = $chain->next($self, $params, $chain);
            return $result;
        });
        self::applyFilter('timeoutPitch', function ($self, $params, $chain) {
            $admin = User::getAdmin();
            $client = User::first($params['pitch']->user_id);
            $nameInflector = new nameInflector();
            if ($params['pitch']->expert == 0):
                $message = Comment::getWinnerSelectionCommentForClient($nameInflector->renderName($client->first_name, $client->last_name), Pitch::getDaysForWinnerSelection($params['pitch']->id), $params['pitch']); else:
                $message = '@' . $nameInflector->renderName($client->first_name, $client->last_name) . ', проект завершен и ожидает мнения эксперта, который в течение 2 рабочих дней выберет 3 идеи, которые лучше всего отвечают поставленной задаче. Дизайнеры больше не могут предлагать решения и оставлять комментарии!';
            endif;
            $data = ['pitch_id' => $params['pitch']->id, 'reply_to' => $client->id, 'user_id' => $admin, 'text' => $message, 'public' => 1];
            Comment::createComment($data);
            if ($params['pitch']->expert == 1) {
                Pitch::sendExpertTimeoutMail($params);
            } elseif (($params['pitch']->category_id != 20) && ($params['pitch']->type !== '1on1')) {
                $project = $params['pitch'];
                if (($project->guaranteed == 0) && ($project->pitchData()['avgNum'] >= 3.0)) {
                    NotificationsMailer::sendChooseWinnerNotificationForNonGuarantee($project);
                } else {
                    NotificationsMailer::sendChooseWinnerNotificationForGuarantee($project);
                }
            }
            $result = $chain->next($self, $params, $chain);
            return $result;
        });
        self::applyFilter('save', function ($self, $params, $chain) {
            $params['entity']->title = preg_replace('/"(.*)"/U', '«\1»', $params['entity']->title);
            $result = $chain->next($self, $params, $chain);
            return $result;
        });
    }

    public static function sendExpertMail($params)
    {
        $experts = unserialize($params['pitch']->{'expert-ids'});
        foreach ($experts as $expert):
            $expert = Expert::first(['conditions' => ['id' => $expert]]);
        $user = User::first($expert->user_id);
        $params['user'] = $user;
        SpamMailer::expertselected($params);
        endforeach;
        return true;
    }

    public static function isCohortClientForMonth($userId, $date)
    {
        $result = false;
        $countPreviousProjects = Pitch::count(['conditions' => [
            'Pitch.user_id' => $userId,
            'Pitch.billed' => 1,
            'Pitch.billed_date' => ['<' => "$date"]
        ]]);
        if ($countPreviousProjects === 0) {
            $result = true;
        }
        return $result;
    }

    public static function sendExpertTimeoutMail($params)
    {
        $experts = unserialize($params['pitch']->{'expert-ids'});
        foreach ($experts as $expert):
            $expert = Expert::first(['conditions' => ['id' => $expert]]);
        $user = User::first($expert->user_id);
        $params['user'] = $user;
        SpamMailer::expertneedpostcomment($params);
        endforeach;
        return true;
    }

    public static function convertToTimeleftFormat($finishDate, $currentTime = null)
    {
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
            $hourWord = $numInflector->formatString($hours, [
                'string' => "час",
                'first' => '',
                'second' => 'а',
                'third' => 'ов'
            ]);
            $hoursString .= ' ' . $hourWord;
        }

        if ($days > 0) {
            $dayWord = $numInflector->formatString($days, ['string' => ['first' => 'день', 'second' => 'дня', 'third' => 'дней']]);
            $dayString = $days . ' ' . $dayWord;
        }
        if (($hours == 0) && ($days == 0)) {
            $dayString = '';
            $hoursString = '';
            $minutes = floor($diff / MINUTE);
            $minuteWord = $numInflector->formatString($minutes, ['string' => ['first' => 'минута', 'second' => 'минуты', 'third' => 'минут']]);
            $dayString = $minutes . ' ' . $minuteWord;
        }


        $string = $dayString . $hoursString . $minutesString;
        return trim($string);
    }

    public static function getNumOfSolutionsPerProject()
    {
        $result = self::find('all', [
                    'fields' => ['AVG(ideas_count) as averageCount'],
                    'conditions' => ['published' => 1]
        ]);
        return ceil($result->first()->averageCount);
    }

    public static function getNumOfSolutionsPerProjectOfCategory($category_id)
    {
        $result = self::find('all', [
                    'fields' => ['AVG(ideas_count) as averageCount'],
                    'conditions' => [
                        'published' => 1,
                        'category_id' => $category_id,
                        'started' => ['>' => date('Y-m-d H:i:s', time() - (365 * DAY))]
                    ]
        ]);
        return ceil($result->first()->averageCount);
    }

    public static function getNumOfCurrentPitches()
    {
        $result = self::all([
                    'conditions' => [
                        'published' => 1,
                        'status' => ['<' => 2],
                    ],
                    'fields' => ['id', 'Solution.nominated'],
                    'with' => ['Solution'],
        ]);
        $result = $result->data();
        foreach ($result as $key => $pitch) {
            $delete = false;
            array_walk_recursive($pitch, function ($item, $idx) use (&$delete) {
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

    /**
     * Метод возвращяет количество завершенных проектов
     * @return integer
     */
    public static function getNumOfCompletedProjects()
    {
        return self::count([
            'conditions' => [
                'published' => 1,
                'blank' => '0',
                'multiwinner' => '0',
                'status' => 2,
            ],
            'fields' => ['id'],
        ]);
    }

    public static function getTotalAwards()
    {
        $result = self::find('all', [
                    'fields' => ['SUM(price) as total'],
                    'conditions' => ['published' => 1, 'status' => 2]]
        );
        return round($result->first()->total);
    }

    public static function getTotalWaitingForClaim()
    {
        $result = self::find('all', [
                    'fields' => ['SUM(price) as total'],
                    'conditions' => ['published' => 1, 'status' => ['<' => 2]]]
        );
        return round($result->first()->total);
    }

    public static function getTotalAwardsValue()
    {
        $result = self::find('all', [
                    'fields' => ['SUM(price) as total'],
                    'conditions' => ['published' => 1]]
        );
        return round($result->first()->total);
    }

    public static function activate($id)
    {
        if ($pitch = self::first($id)) {
            $user_id = $pitch->user_id;
            $params = compact('id', 'user_id', 'pitch');
            return static::_filter(__FUNCTION__, $params, function ($self, $params) {
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
                        if ($pitch->type != 'company_project') {
                            $pitch->finishDate = date('Y-m-d H:i:s', time() + (DAY * $modifier));
                        }
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
                $pitch->billed_date = date('Y-m-d H:i:s');
                return $pitch->save();
            });
        }
        return false;
    }

    /**
     * Метод находит все проекты, где приём работ должен быть окончен,
     * завершает их и отправляет письма заказчикам
     *
     * @return int
     */
    public static function timeoutPitches()
    {
        $projects = self::all(['conditions' => [
                        'status' => 0,
                        'finishDate' => ['<' => date('Y-m-d H:i:s')],
                        'published' => 1
        ]]);
        $count = 0;
        foreach ($projects as $project) {
            self::timeoutPitch($project);
            $count++;
        }
        return $count;
    }

    public static function timeoutPitch($pitch)
    {
        $params = compact('pitch');
        return static::_filter(__FUNCTION__, $params, function ($self, $params) {
            extract($params);
            if ((int) $pitch->category_id === 22) {
                $count = Solution::count([
                            'conditions' => ['Solution.pitch_id' => $pitch->id]
                        ]);
                if ($count) {
                    Payture::charge($pitch->payture_id);
                } else {
                    Payture::unblock($pitch->payture_id, (int) $pitch->total * 100);
                }
            }
            $pitch->status = 1;
            $pitch->save();
        });
    }

    public static function addHumanisedTimeleft($result)
    {
        if (is_object($result)) {
            $addHumanDate = function ($record) {
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

    public static function addNoveltyStatus($result)
    {
        if (is_object($result)) {
            $addNoveltyStatus = function ($record) {
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

    public static function addEditedBrief($result)
    {
        if (is_object($result)) {
            $addEditedBrief = function ($record) {
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

    /**
     * Метод добавляет дни продления для проекта и увеличивает награду
     *
     * @param $addon
     * @return bool
     */
    public static function addProlong($addon)
    {
        if ($pitch = self::first($addon->pitch_id)) {
            $sumProlong = 1000 * $addon->{'prolong-days'};
            $pitch->price += $sumProlong;
            $daysAdded = $addon->{'prolong-days'} * DAY;
            $timeProlong = strtotime($pitch->finishDate) + $daysAdded;
            $pitch->finishDate = date('Y-m-d H:i:s', $timeProlong);
            if ((int) $pitch->category_id === 20) {
                $pitch->chooseWinnerFinishDate = date(MYSQL_DATETIME_FORMAT, strtotime($pitch->chooseWinnerFinishDate) + $daysAdded);
            }
            if ((int)$pitch->status === 1) {
                $pitch->status = 0;
                $timeProlong = strtotime(date(MYSQL_DATETIME_FORMAT)) + $daysAdded;
                $pitch->finishDate = date('Y-m-d H:i:s', $timeProlong);
            }
            if ($pitch->save()) {
                Comment::createComment([
                    'pitch_id' => $pitch->id,
                    'user_id' => User::getAdmin(),
                    'text' => 'Дорогие друзья! Обратите внимание, что срок проекта продлен до ' . date('d.m.Y', strtotime($pitch->finishDate)) . ', а размер вознаграждения увеличен.',
                    'public' => 1,
                ]);
                return true;
            }
        }
        return false;
    }

    /**
     * Add Expert when Addon Activated
     */
    public static function addExpert($addon)
    {
        if ($pitch = self::first($addon->pitch_id)) {
            $expertsPitch = [];
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
    public static function addBrief($addon)
    {
        if ($pitch = self::first($addon->pitch_id)) {
            $pitch->brief = 1;
            $pitch->save();
        }
        return true;
    }

    /**
     * Add Pinned when Addon Activated
     */
    public static function addPinned($addon)
    {
        if ($pitch = self::first($addon->pitch_id)) {
            $pitch->pinned = 1;
            $pitch->save();
        }
        return true;
    }

    /**
     * Add Guaranteed when Addon Activated
     */
    public static function addGuaranteed($addon)
    {
        if ($pitch = self::first($addon->pitch_id)) {
            $pitch->guaranteed = 1;
            $pitch->save();
        }
        return true;
    }

    /**
     * Add Private when Addon Activated
     */
    public static function addPrivate($addon)
    {
        if ($pitch = self::first($addon->pitch_id)) {
            $pitch->private = 1;
            $pitch->save();
        }
        return true;
    }

    /**
     * Метод для завершения проекта
     *
     * @param $pitchId
     * @return bool|object
     */
    public static function finishPitch($pitchId)
    {
        $pitch = Pitch::first($pitchId);
        $solution = Solution::first($pitch->awarded);
        if ($solution) {
            $params = compact('pitch', 'solution');
            return static::_filter(__FUNCTION__, $params, function ($self, $params) {
                extract($params);
                $pitch->status = 2;
                $pitch->totalFinishDate = date('Y-m-d H:i:s');
                $pitch->save();
                return true;
            });
        }
        return false;
    }

    public static function increaseIdeasCountOne($pitchId)
    {
        $pitch = Pitch::first($pitchId);
        $pitch->ideas_count += 1;
        return $pitch->save();
    }

    public static function decreaseIdeasCountOne($pitchId)
    {
        $pitch = Pitch::first($pitchId);
        $pitch->ideas_count -= 1;
        return $pitch->save();
    }

    public static function apiGetPitch($categoryId = null)
    {
        $fetch = false;
        $catConditions = [];
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
                    $categoryId = [];
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
            $catConditions = ['category_id' => $categoryId];
        }

        $count = 0;
        while ($fetch == false):

            $pitch = Pitch::first([
                        'conditions' => array_merge(['status' => 0, 'category_id' => ['!=' => 7], 'published' => 1, 'private' => 0], $catConditions),
                        'order' => ['RAND()'],
                        'with' => ['Solution']
            ]);

        if (($pitch) && (!is_null($pitch->solutions->first()->id))) {
            $fetch = true;
        }
        $count ++;
        if ($count > 10) {
            $solution = Solution::first(['order' => ['created' => 'desc']]);
            $pitch = Pitch::first([
                            'conditions' => ['status' => 0, 'published' => 1, 'private' => 0, 'Pitch.id' => $solution->pitch_id],
                            'with' => ['Solution']
                ]);
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

    public static function dailypitch()
    {
        $pitches = Pitch::all(['conditions' => ['type' => ['!=' => '1on1'], 'published' => 1, 'blank' => 0, 'status' => 0, 'started' => ['>=' => date('Y-m-d H:i:s', time() - DAY)]]]);
        if (count($pitches) > 0) {
            $users = User::all(['conditions' => ['email_newpitchonce' => 1, 'confirmed_email' => 1, 'User.email' => ['!=' => '']]]);
            foreach ($users as $user) {
                User::sendDailyPitch($user, $pitches);
            }
        } else {
            $users = [];
        }
        return count($users);
    }

    public static function openLetter()
    {
        $pitches = Pitch::all([
                    'conditions' => [
                        'published' => 1,
                        'blank' => 0,
                        'started' => [
                            '>=' => date('Y-m-d H:i:s', time() - DAY - HOUR),
                            '<=' => date('Y-m-d H:i:s', time() - DAY),
                        ],
                    ],
                    'with' => ['User'],
        ]);
        $res = [
            'all' => count($pitches),
            'sent' => 0,
        ];
        if ($res['all'] > 0) {
            foreach ($pitches as $pitch) {
                if (User::sendOpenLetter($pitch)) {
                    $res['sent'] ++;
                }
            }
        }

        return $res;
    }

    public static function addonBriefLetter($time)
    {
        $conditions = [
            'brief' => 0,
        ];
        $conditions += self::getAddonConditions($time);
        $pitches = self::all([
                    'conditions' => $conditions,
                    'with' => ['User'],
        ]);
        $res = 0;
        if (count($pitches)) {
            foreach ($pitches as $pitch) {
                if (Addon::first(['conditions' => ['pitch_id' => $pitch->id, 'brief' => 1]])) {
                    continue;
                }
                if (User::sendAddonBrief($pitch)) {
                    $res++;
                }
            }
        }
        return $res;
    }

    public static function addonProlongLetter($time)
    {
        $conditions = self::getAddonConditions($time);
        $pitches = self::all([
                    'conditions' => $conditions,
                    'with' => ['User'],
        ]);
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

    public static function addonExpertLetter($time)
    {
        $conditions = [
            'expert' => 0,
        ];
        $conditions += self::getAddonConditions($time);
        $pitches = self::all([
                    'conditions' => $conditions,
                    'with' => ['User'],
        ]);
        $res = 0;
        if (count($pitches)) {
            foreach ($pitches as $pitch) {
                if (Addon::first(['conditions' => ['pitch_id' => $pitch->id, 'experts' => 1]])) {
                    continue;
                }
                if (User::sendAddonExpert($pitch)) {
                    $res++;
                }
            }
        }
        return $res;
    }

    public static function ExpertReminder()
    {
        $conditions = [
            'expert' => 1,
            'status' => 1,
            'awarded' => 0,
            'finishDate' => [
                '>=' => date('Y-m-d H:i:s', time() - 62 * HOUR),
                '<' => date('Y-m-d H:i:s', time() - 61 * HOUR),
            ],
        ];
        $pitches = self::all([
                    'conditions' => $conditions,
        ]);
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

    protected static function getAddonConditions($time)
    {
        if ((0 < $time) && ($time < 1)) {
            $timeCond = [
                'TIMESTAMPADD(SECOND,(TIMESTAMPDIFF(SECOND,started,finishDate) * ' . $time . '),started)' => [
                    '>=' => date('Y-m-d H:i:s', time() - HOUR),
                    '<' => date('Y-m-d H:i:s', time()),
                ],
            ];
        }

        if ($time >= 1) {
            $timeCond = [
                'started' => [
                    '>=' => date('Y-m-d H:i:s', time() - DAY * $time - HOUR),
                    '<' => date('Y-m-d H:i:s', time() - DAY * $time),
                ],
            ];
        }

        if ($time < 0) {
            $timeCond = [
                'finishDate' => [
                    '>=' => date('Y-m-d H:i:s', time() + DAY * abs($time) - HOUR),
                    '<' => date('Y-m-d H:i:s', time() + DAY * abs($time)),
                ],
            ];
        }

        $conditions = [
            'published' => 1,
            'blank' => 0,
            'status' => 0,
        ];
        $conditions += $timeCond;
        return $conditions;
    }

    public static function generatePdfAct($options)
    {
        $destination = PdfGetter::findPdfDestination($options['destination']);
        $path = ($destination == 'f') ? LITHIUM_APP_PATH . '/' . 'resources' . '/' . 'tmp/' : '';
        $options['pitch']->moneyback = self::isMoneyBack($options['pitch']->id);
        if ($options['pitch']->type == 'plan-payment') {
            $planId = SubscriptionPlan::getPlanForPayment($options['pitch']->id);
            $plan = SubscriptionPlan::getPlan($planId);
            $total = $plan['price'];
            if (!isset($options['bill'])) {
                $user = User::first($options['pitch']->user_id);
                if ($companyData = unserialize($user->companydata)) {
                    $bill = new \stdClass();
                    $bill->name = $companyData['company_name'];
                    $bill->address = $companyData['address'];
                    $bill->inn = $companyData['inn'];
                    $bill->kpp = $companyData['kpp'];
                    if ($bill->kpp == '') {
                        $bill->individual = 1;
                    } else {
                        $bill->individual = 0;
                    }
                    $options['bill'] = $bill;
                }
            }
            if (User::hasActiveSubscriptionDiscount($options['pitch']->user_id)) {
                $discount = User::getSubscriptionDiscount($options['pitch']->user_id);
                $total = (int) $total - ($total * ($discount * 0.01));
            }
            $options['pitch']->total = $total;
        }
        if (!($options['bill'])) {
            $user = User::first($options['pitch']->user_id);
            if ($companyData = unserialize($user->companydata)) {
                $bill = new \stdClass();
                $bill->name = $companyData['company_name'];
                $bill->address = $companyData['address'];
                $bill->inn = $companyData['inn'];
                $bill->kpp = $companyData['kpp'];
                if ($bill->kpp == '') {
                    $bill->individual = 1;
                } else {
                    $bill->individual = 0;
                }
                $options['bill'] = $bill;
            }
        }
        require_once(LITHIUM_APP_PATH . '/' . 'libraries' . '/' . 'MPDF54/MPDF54/mpdf.php');
        $mpdf = new \mPDF();
        $mpdf->WriteHTML(PdfGetter::get('Act', $options));
        return $mpdf->Output($path . 'godesigner-act-' . $options['pitch']->id . '.pdf', $destination);
    }

    public static function generatePdfReport($options)
    {
        $destination = PdfGetter::findPdfDestination($options['destination']);
        $path = ($destination == 'f') ? LITHIUM_APP_PATH . '/' . 'resources' . '/' . 'tmp/' : '';
        $layout = ($options['bill']->individual == 1) ? 'Report-fiz' : 'Report-yur';
        $options['transaction_id'] = self::getPaymentId($options['pitch']->id);
        $receipt = Receipt::all([
                    'conditions' => [
                        'pitch_id' => $options['pitch']->id,
                    ],
        ]);
        $totalfees = 0;
        $prolongfees = 0;
        if ($addon = Addon::first([
                    'conditions' => [
                        'pitch_id' => $options['pitch']->id,
                        'billed' => 1,
                    ],
                ])) {
            $totalfees = $addon->total;
            $prolongfees = ($addon->prolong == 1) ? $addon->{'prolong-days'} * 1000 : $prolongfees;
        }
        foreach ($receipt as $option) {
            if (preg_match('/Сбор GoDesigner/', $option->name)) {
                $options['commission'] = $option->value;
            }
            if (($option->name != 'Награда копирайтеру') &&  ($option->name != 'Награда Дизайнеру') && (!preg_match('/Сбор GoDesigner/', $option->name))) {
                $totalfees += $option->value;
            }
        }
        if ($options['pitch']->type == 'plan-payment') {
            $planId = SubscriptionPlan::getPlanForPayment($options['pitch']->id);
            $plan = SubscriptionPlan::getPlan($planId);
            $total = $plan['price'];
            if (!isset($options['bill'])) {
                $user = User::first($options['pitch']->user_id);
                if ($companyData = unserialize($user->companydata)) {
                    $bill = new \stdClass();
                    $bill->name = $companyData['company_name'];
                    $bill->address = $companyData['address'];
                    $bill->inn = $companyData['inn'];
                    $bill->kpp = $companyData['kpp'];
                    if ($bill->kpp == '') {
                        $bill->individual = 1;
                    } else {
                        $bill->individual = 0;
                    }
                    $options['bill'] = $bill;
                }
            }
            if (User::hasActiveSubscriptionDiscount($options['pitch']->user_id)) {
                $discount = User::getSubscriptionDiscount($options['pitch']->user_id);
                $total = (int) $total - ($total * ($discount * 0.01));
            }
            $options['pitch']->total = $total;
            $totalfees = 0;
            $prolongfees = 0;
        }
        if (!($options['bill'])) {
            $user = User::first($options['pitch']->user_id);
            if ($companyData = unserialize($user->companydata)) {
                $bill = new \stdClass();
                $bill->name = $companyData['company_name'];
                $bill->address = $companyData['address'];
                $bill->inn = $companyData['inn'];
                $bill->kpp = $companyData['kpp'];
                if ($bill->kpp == '') {
                    $bill->individual = 1;
                } else {
                    $bill->individual = 0;
                }
                $options['bill'] = $bill;
            }
        }
        $options['totalfees'] = $totalfees;
        $options['prolongfees'] = $prolongfees;
        $options['pitch']->moneyback = self::isMoneyBack($options['pitch']->id);
        require_once(LITHIUM_APP_PATH . '/' . 'libraries' . '/' . 'MPDF54/MPDF54/mpdf.php');
        $mpdf = new \mPDF();
        $mpdf->WriteHTML(PdfGetter::get($layout, $options));
        $mpdf->Output($path . 'godesigner-report-' . $options['pitch']->id . '.pdf', $destination);
    }

    public static function sendReports()
    {
        $query = [
            'conditions' => [
                'status' => 2,
                'totalFinishDate' => [
                    '>=' => date('Y-m-d H:i:s', time() - 1 * DAY),
                ],
            ],
        ];
        $res = 0;
        if ($pitches = Pitch::all($query)) {
            foreach ($pitches as $pitch) {
                if ($bill = Bill::first($pitch->id)) {
                    $destination = 'File';
                    $addons = Addon::all(['conditions' => [
                        'pitch_id' => $pitch->id,
                        'billed' => 1,
                        'prolong' => ['>' => 0],
                    ]]);
                    $options = compact('pitch', 'bill', 'addons', 'destination');
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

    public static function isReferalAllowed($pitch)
    {
        if ((strtotime($pitch->started) + 60 * DAY) >= time()) {
            return true;
        }
        return false;
    }

    public static function getTransactions($pitchId)
    {
        $transMaster = Transaction::all(['conditions' => ['ORDER' => $pitchId]]);
        $transPay = Paymaster::all(['conditions' => ['LMI_PAYMENT_NO' => $pitchId]]);
        return compact('transMaster', 'transPay');
    }

    public static function getMultiple($category, $specifics)
    {
        $specifics = unserialize($specifics);
        if (!empty($specifics['site-sub'])) {
            $numInflector = new NumInflector();
            $res = '';
            switch ($category) {
                case 2:
                    //$res = (int) $specifics['site-sub'] . ' ' . $numInflector->formatString($specifics['site-sub'], array('first' => 'макет', 'second' => 'макета', 'third' => 'макетов'));
                    break;
                case 3:
                    $res = (int) $specifics['site-sub'] . ' ' . $numInflector->formatString($specifics['site-sub'], ['first' => 'страница', 'second' => 'страницы', 'third' => 'страниц']);
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
    public function ratingPopup($pitch, $avgArray)
    {
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
    public function winnerPopup($pitch)
    {
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
    public static function getOwnerOfPitch($pitchId)
    {
        if ($pitchData = self::first(['fields' => ['user_id'], 'conditions' => ['id' => $pitchId]])) {
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
    public function getSolutionsSortingOrder($pitch, $type = null)
    {
        if ($result = $this->__getSortingString($type)) {
            switch ($result) {
                case 'rating':
                    $array = ['awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created' => 'desc'];
                    break;
                case 'created':
                    $array = ['awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'];
                    break;
                case 'likes':
                    $array = ['awarded' => 'desc', 'nominated' => 'desc', 'likes' => 'desc', 'created' => 'desc'];
                    break;
                default:
                    $array = ['awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'];
                    break;
            }
            if (Session::read('user.id') == $pitch->user_id) {
                $array = array_merge($array, ['awarded' => 'desc', 'hidden' => 'asc']);
                $array = array_slice($array, 0, 1, true) +
                        ['hidden' => 'asc'] +
                        array_slice($array, 1, null, true);
            }
            return $array;
        } else {
            if ((Session::read('user.id') == $pitch->user_id) && (strtotime($pitch->finishDate) > time()) && ($pitch->status == 0)) {
                return ['hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'];
            } elseif ((Session::read('user.id') == $pitch->user_id) || ($pitch->status > 0)) {
                return ['awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created' => 'desc', 'hidden' => 'asc',];
            } else {
                return ['awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'];
            }
        }
    }

    public function getDesignersSortingOrder($pitch, $type = null)
    {
        if ($result = $this->__getSortingString($type)) {
            switch ($result) {
                case 'rating':
                    $array = ['rating' => 'desc', 'created' => 'desc'];
                    break;
                case 'created':
                    $array = ['created' => 'desc'];
                    break;
                case 'number':
                    $array = ['Num' => 'desc', 'created' => 'desc'];
                    break;
            }
            return $array;
        } else {
            if ((Session::read('user.id') == $pitch->user_id) && (strtotime($pitch->finishDate) > time()) && ($pitch->status == 0)) {
                return ['hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'];
            } elseif ((Session::read('user.id') == $pitch->user_id) || ($pitch->status > 0)) {
                return ['hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created' => 'desc'];
            } else {
                return ['hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'];
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
    public function getSolutionsSortName($pitch, $type = null)
    {
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

    private function __getSortingString($param)
    {
        if ($param and is_array($param) and isset($param['sorting'])) {
            $param = $param['sorting'];
        }
        if (($param) and (is_string($param)) and (in_array($param, $this->validSorts))) {
            return $param;
        }
        return false;
    }

    public function pitchData($pitch)
    {
        set_time_limit(120);
        $award = $pitch->price;
        $category = $pitch->category;
        $money = 3;
        if ($award >= $category->normalAward) {
            $money = 4;
        } elseif ($award >= $category->goodAward) {
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

        $ratingArray = [];
        $moneyArray = [];
        $commentArray = [];
        $dates = [];
        $pitch->firstSolutionTime = self::__getFirstSolutionTime($pitch);
        foreach ($period as $dt) {
            $time = strtotime($dt->format('Y-m-d'));
            $plusDay = date('Y-m-d H:i:s', $time + DAY);
            $dates[] = $dt->format('d/m');
            $ids = self::__getSolutionIds($pitch, $plusDay);
            $moneyArray[] = $money;
            $ratingArray[] = $this->calcRating($ids, $pitch, $plusDay, $dt);
            $commentArray[] = $this->calcComments($ids, $pitch, $plusDay, $dt);
        }
        $ratingAverage = (empty($ratingArray)) ? 0 : round(array_sum($ratingArray) / count($ratingArray), 1);
        $moneyAverage = (empty($moneyArray)) ? 0 : round(array_sum($moneyArray) / count($moneyArray), 1);
        $commentAverage = (empty($commentArray)) ? 0 : round(array_sum($commentArray) / count($commentArray), 1);
        $percentages = [
            'rating' => round(($ratingAverage / 15) * 100),
            'money' => round(($moneyAverage / 15) * 100),
            'comment' => round(($commentAverage / 15) * 100),
        ];
        $total = 0;
        foreach ($percentages as $key => $value) {
            $total += $value;
        }
        $percentages['empty'] = 100 - $total;
        $avgArray = $this->calcAvg($ratingArray, $moneyArray, $commentArray);
        $avgNum = (empty($avgArray)) ? 0 : round(array_sum($avgArray) / count($avgArray), 1);
        $guaranteed = $pitch->guaranteed;
        $firstSolutionTime = $pitch->firstSolutionTime;
        return compact('guaranteed', 'dates', 'ratingArray', 'moneyArray', 'commentArray', 'avgArray', 'avgNum', 'percentages', 'commentsNum', 'firstSolutionTime');
    }

    /**
     * Возвращает айдишники решений из питча до определенной даты
     *
     * @param $pitch
     * @param $plusDay
     * @return array|bool|mixed
     */
    private function __getSolutionIds($pitch, $plusDay)
    {
        $cacheKey = 'calc_ids_' . $pitch->id . '_' . date('Y-m-d_H_i_s', strtotime($plusDay));
        if (!$ids = Rcache::read($cacheKey)) {
            if (strtotime($pitch->started) > strtotime('2013-03-25 00:00:00')) {
                $solutions = Historysolution::all(['conditions' => ['pitch_id' => $pitch->id, 'date(created)' => ['<' => $plusDay]]]);
            } else {
                $solutions = Solution::all(['conditions' => ['pitch_id' => $pitch->id, 'date(created)' => ['<' => $plusDay]]]);
            }
            $ids = [];
            foreach ($solutions as $solution) {
                $ids[] = $solution->id;
            }
            if (strtotime($plusDay) < time()) {
                Rcache::write($cacheKey, $ids, [], '+2 hours');
            }
        }
        return $ids;
    }

    /**
     * Метод возвращает время создания самого первого решения в питча, если есть
     *
     * @param $pitch - объект питча
     * @return bool|int|mixed|null
     */
    private function __getFirstSolutionTime($pitch)
    {
        $cacheKey = 'calc_firstSolutionTime_' . $pitch->id;
        $time = null;
        //if (!$time = Rcache::read($cacheKey)) {
            $pitch->firstSolution = Historysolution::first([
                'conditions' => [
                    'pitch_id' => $pitch->id],
                'order' => [
                    'created' => 'asc']
            ]);
        if ($pitch->firstSolution) {
            $time = strtotime($pitch->firstSolution->created);
                //Rcache::write($cacheKey, $time);
        }
        //}
        return $time;
    }

    private function calcAvg($first, $second, $third)
    {
        $avgArray = [];
        for ($i = 0; $i < count($first); $i++) {
            $avg = round((($first[$i] + $second[$i] + $third[$i]) / 3), 1);
            $avgArray[] = $avg;
        }
        return $avgArray;
    }

    public function calcRating($ids, $pitch, $plusDay, $dt)
    {
        $cacheKey = 'calc_rating_' . $pitch->id . '_' . date('Y-m-d_H_i_s', strtotime($plusDay));
        //if (!$rating = Rcache::read($cacheKey)) {
            if (!empty($ids)) {
                $ratingsNum = Ratingchange::all(['conditions' => ['solution_id' => $ids, 'user_id' => $pitch->user_id, 'date(created)' => ['<' => $plusDay]]]);
            } else {
                $ratingsNum = [];
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
                case $percents < 50:
                    $rating = 1;
                    break;
                case $percents < 63:
                    $rating = 2;
                    break;
                case $percents < 79:
                    $rating = 3;
                    break;
                case $percents < 89:
                    $rating = 4;
                    break;
                case $percents <= 100:
                    $rating = 5;
                    break;
            }
            //$diff = strtotime(date('Y-m-d', $pitch->firstSolutionTime)) + DAY - $pitch->firstSolutionTime;
        if (($pitch->firstSolution) && ($pitch->firstSolutionTime < strtotime($dt->format('Y-m-d')) + DAY)) {
            $wasSolutionPostedOnThisDateOrBefore = true;
        } else {
            $wasSolutionPostedOnThisDateOrBefore = false;
        }
        if ((!$pitch->firstSolution) || !$wasSolutionPostedOnThisDateOrBefore) {
            //if ((!$pitch->firstSolution) || (($pitch->firstSolution) && ($pitch->firstSolutionTime > strtotime($dt->format('Y-m-d H:i:s')) + $diff))) {
            $rating = 3;
        }
        return $rating;
    }

    public function calcComments($ids, $pitch, $plusDay, $dt)
    {
        $cacheKey = 'calc_comments_' . $pitch->id . '_' . date('Y-m-d_H_i_s', strtotime($plusDay));
        //if (!$comments = Rcache::read($cacheKey)) {
            if (!empty($ids)) {
                if (strtotime($pitch->created) > strtotime('2013-03-24 18:00:00')) {
                    $commentsNum = Historycomment::all(['conditions' => ['pitch_id' => $pitch->id, 'user_id' => $pitch->user_id, 'date(created)' => ['<' => $plusDay]]]);
                } else {
                    $commentsNum = Comment::all(['nofilters' => true, 'conditions' => ['pitch_id' => $pitch->id, 'user_id' => $pitch->user_id, 'date(created)' => ['<' => $plusDay]]]);
                }
            } else {
                $commentsNum = [];
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
        if (($pitch->firstSolution) && ($pitch->firstSolutionTime < strtotime($dt->format('Y-m-d')) + DAY)) {
            $wasSolutionPostedOnThisDateOrBefore = true;
        } else {
            $wasSolutionPostedOnThisDateOrBefore = false;
        }
            //$diff = strtotime(date('Y-m-d', $pitch->firstSolutionTime)) + DAY - $pitch->firstSolutionTime;
            if ((!$pitch->firstSolution) || !$wasSolutionPostedOnThisDateOrBefore) {
                //if ((!$pitch->firstSolution) || (($pitch->firstSolution) && ($pitch->firstSolutionTime > strtotime($dt->format('Y-m-d H:i:s')) + $diff))) {
                $comments = 3;
            }
        if (strtotime($plusDay) < time()) {
            //Rcache::write($cacheKey, $comments, [], '+2 hours');
        }
        //}
        return $comments;
    }

    /**
     * Метод возвращает номер страницы
     *
     * @param $page
     * @return integer
     */
    public static function getQueryPageNum($page = 1)
    {
        $page = abs(intval($page));
        if ($page == 0) {
            $page = 1;
        }
        return $page;
    }

    /**
     * Метод возвращает ценовой диапазон
     *
     * @param $priceFilter
     * @return array
     */
    public static function getQueryPriceFilter($priceFilter = 0)
    {
        switch ($priceFilter) {
            case 1:
                $result = ['price' => ['>' => 5000, '<=' => 10000]];
                break;
            case 2:
                $result = ['price' => ['>' => 10000, '<=' => 20000]];
                break;
            case 3:
                $result = ['price' => ['>' => 20000]];
                break;
            case 4:
                $result = ['price' => 0];
                break;
            default:
                $result = [];
        }
        return $result;
    }

    /**
     * Метод возвращает время размещения питча
     *
     * @param $timeframe
     * @return array
     */
    public static function getQueryTimeframe($timeframe = 0)
    {
        switch ($timeframe) {
            case 1:
                $result = ['finishDate' => ['<=' => date('Y-m-d H:i:s', time() + (DAY * 3))]];
                break;
            case 2:
                $result = ['finishDate' => ['<=' => date('Y-m-d H:i:s', time() + (DAY * 7))]];
                break;
            case 3:
                $result = ['finishDate' => ['<=' => date('Y-m-d H:i:s', time() + (DAY * 10))]];
                break;
            case 4:
                $result = ['finishDate' => ['=>' => date('Y-m-d H:i:s', time() + (DAY * 14))]];
                break;
            default:
                $result = [];
        }
        return $result;
    }

    /**
     * Метод возвращает массив ключевых слов для поиска
     *
     * @param $search
     * @return array
     */
    public static function getQuerySearchTerm($search = '')
    {
        if ((is_string($search) && !empty($search)) && $search != 'НАЙТИ ПРОЕКТ ПО КЛЮЧЕВОМУ СЛОВУ ИЛИ ТИПУ') {
            $word = urldecode(filter_var($search, FILTER_SANITIZE_STRING));
            $firstLetter = mb_substr($word, 0, 1, 'utf-8');
            $firstUpper = (mb_strtoupper($firstLetter, 'utf-8'));
            $firstLower = (mb_strtolower($firstLetter, 'utf-8'));
            $string = $firstLower . mb_substr($word, 1, mb_strlen($word, 'utf-8'), 'utf-8') . '|' . $firstUpper . mb_substr($word, 1, mb_strlen($word, 'utf-8'), 'utf-8') . '|' . mb_strtoupper($word, 'utf-8') . '|' . str_replace('ё', 'е', $word);
            $search = ['LOWER(Pitch.title)' => ['REGEXP' => $string]];
            if (strlen($word) > 3) {
                $search['Pitch.description'] = ['LIKE' => '%' . $word . '%'];
                $search['Pitch.business-description'] = ['LIKE' => '%' . $word . '%'];
            }
            $search = ['OR' => [
                ["LOWER(Pitch.title) REGEXP '" . $string . "'"],
                ["Pitch.description LIKE '%$word%'"],
                ["'Pitch.business-description' LIKE '%$word%'"],
            ]];
        } else {
            $search = [];
        }
        return $search;
    }

    /**
     * Метод возвращает id категории
     *
     * @param $category
     * @return array
     */
    public static function getQueryCategory($category)
    {
        $categories = Category::all();
        foreach ($categories as $cat) {
            $allowedCategories[] = $cat->id;
        }
        if (!empty($category) && in_array($category, $allowedCategories)) {
            $category = ['category_id' => $category];
        } else {
            $category = [];
        }
        return $category;
    }
    
    /**
    * Метод возвращает массив для сортировки полей
    *
    * @param $order
    * @return array
    */
    public static function getQueryOrder($order, $type = 'current')
    {
        $allowedOrder = ['price', 'finishDate', 'ideas_count', 'title', 'category', 'started'];
        $allowedSortDirections = ['asc', 'desc'];
        $trigger = is_array($order);
        $field = $trigger ? key($order) : '';
        $dir = $trigger ? current($order) : '';
        if ($trigger && ((in_array($field, $allowedOrder)) && (in_array($dir, $allowedSortDirections)))) {
            switch ($field) {
                case 'category':
                    $order = ['category_id' => $dir,'started' => 'desc'];
                    break;
                case 'finishDate':
                    $order = ['(finishDate - \'' . date('Y-m-d H:i:s') . '\')' => $dir];
                    break;
                case 'price':
                    if ($type == 'current') {
                        $order = ['free' => 'desc', $field => $dir,'started' => 'desc'];
                    } else {
                        $order = [$field => $dir,'started' => 'desc'];
                    }
                    break;
                default:
                    $order = [$field => $dir,'started' => 'desc'];
            }
        } else {
            $order = ['free' => 'desc','price' => 'desc','started' => 'desc'];
        }
        return $order;
    }

    /**
    * Метод возвращает данные для поиска по типу питча
    *
    * @param $types
    * @return array
    */
    public static function getQueryType($types)
    {
        switch ($types) {
            case 'finished':
                $result = ['OR' => [['status = 2'], ['(status = 1 AND awarded > 0)']]];
                break;
            case 'current':
                $result = ['status' => ['<' => 2], 'awarded' => 0];
                break;
            case 'all':
                $result = [];
                break;
                        case 'completion-stage':
                            $result = ['status' => 1, 'awarded' => ['>' => 0]];
                            break;
                        case 'awarded':
                            $result = ['status' => 2];
                            break;
            default:
                $result = [
                    'OR' => [
                        ['awardedDate >= \'' . date('Y-m-d H:i:s', time() - DAY) . '\''],
                        ['status < 2 AND awarded = 0'],
                    ]];
        }
        return $result;
    }

    /**
    * Метод возвращает питчи для главной страницы
    *
    * @return array
    */
    public static function getPitchesForHomePage()
    {
        return Pitch::all([
                'Pitch.order' => [
                'Pitch.pinned' => 'desc',
                'Pitch.ideas_count' => 'desc',
                'Pitch.price' => 'desc'
            ],
                'conditions' => [
                    'Pitch.status' => 0,
                    'Pitch.published' => 1,
                    'Pitch.multiwinner' => 0],
            'limit' => 3,
            'page' => 1,
        ]);
    }

    public static function getFreePitch()
    {
        return Pitch::first(['conditions' => ['status' => 0, 'published' => 1, 'free' => 1], 'order' => ['RAND()']]);
    }

    public static function createNewWinner($solutionId)
    {
        if (($solution = Solution::first([
                    'conditions' => ['Solution.id' => $solutionId],
                    'with' => ['Pitch'],
                ])) && count(self::all(['conditions' => ['user_id' => $solution->pitch->user_id, 'billed' => 0, 'multiwinner' => $solution->pitch->id]])) == 0) {
            $copyPitch = Pitch::create();
            $data = $solution->pitch->data();
            $data['type'] = 'multiwinner';
            $data['billed'] = 0;
            $data['published'] = 0;
            $data['status'] = 1;
            $data['multiwinner'] = $data['id'];
            $count = self::getCountBilledMultiwinner($data['id']) + 2;
            $data['title'] = $count . '. ' . $data['title'];
            //  $data['total'] = $data['price'] + ($data['price']*0);
            unset($data['id']);
            $copyPitch->set($data);
            if ($copyPitch->save()) {
                $copyPitch->awarded = Solution::copy($copyPitch->id, $solution->id);
                $receiptData = [
                    'features' => [
                        'award' => $copyPitch->price],
                    'commonPitchData' => [
                        'id' => $copyPitch->id,
                        'category_id' => $copyPitch->category_id,
                        'promocode' => $copyPitch->promocode]];
                Receipt::createReceipt($receiptData);
                $commission = Receipt::getCommissionForProject($copyPitch->id);
                $copyPitch->total = $commission + $copyPitch->price;
                $copyPitch->save();
                return $copyPitch->id;
            }
        } else {
            return false;
        }
    }

    public static function activateNewWinner($pitchId)
    {
        if ($pitch = self::first(['conditions' => ['Pitch.id' => $pitchId], 'with' => ['Solution'], ])) {
            $pitch->billed = 1;
            $pitch->published = 1;
            $pitch->finished = date('Y-m-d H:i:s');
            $pitch->totalFinishDate = '0000-00-00 00:00:00';
            $pitch->awardedDate = date('Y-m-d H:i:s');
            $pitch->billed_date = date('Y-m-d H:i:s');
            Solution::awardCopy($pitch->awarded);
            $count = self::getCountBilledMultiwinner($pitch->multiwinner);
            if ($count == 0) {
                $mainPitch = self::first($pitch->multiwinner);
                $mainPitch->title = '1. ' . $mainPitch->title;
                $mainPitch->save();
            }
            if ($pitch->save()) {
                Task::createNewTask($pitch->awarded, 'victoryNotification');
                $solution = $pitch->solutions[0];
                $solution = Solution::first($solution->multiwinner);
                $solution->awarded = 1;
                $solution->nominated = 1;
                $solution->save();
                User::sendWinnerComment($solution);
                //User::sendTweetWinner($solution);
                Task::createNewTask($solution->id, 'victoryNotificationTwitter');
                Task::createNewTask($solution->id, 'victoryNotification');
                $project = $pitch;
                if (($project->category_id != 20) && (!empty($project->ga_id))) {
                    $options = ['client_id' => $project->ga_id, 'user_id' => $project->user_id];
                    $tracking = new \Racecore\GATracking\GATracking('UA-9235854-5', $options);

                    $transaction = $tracking->createTracking('Ecommerce\Transaction');
                    $transaction->setID($project->id);
                    $transaction->setRevenue($project->total);
                    $transaction->setCurrency('RUB');
                    $result = $tracking->sendTracking($transaction);

                    $item = $tracking->createTracking('Ecommerce\Item');
                    $item->setTransactionID($project->id);
                    $item->setName($project->title);
                    $item->setPrice($project->total);
                    $item->setQuantity(1);
                    $item->setSku($project->id . '_1');
                    $item->setCategory('Дополнительный победитель');
                    $item->setCurrency('RUB');
                    $result = $tracking->sendTracking($item);
                }
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Метод активирует купленный логотип на распродаже с айди $pitchId
     *
     * @param $pitchId
     * @return bool
     */
    public static function activateLogoSalePitch($pitchId)
    {
        if ($pitch = self::first(['conditions' => ['Pitch.id' => $pitchId, 'Pitch.blank' => 1], 'with' => ['Solution']])) {
            if ($pitch->awarded == 0) {
                return false;
            } else {
                if ($originalSolution = Solution::first($pitch->awarded)) {
                    $copyId = Solution::copy($pitch->id, $originalSolution->id);
                    Solution::awardCopy($copyId);
                    $originalPitch = Pitch::first($originalSolution->pitch_id);
                    $pitch->awarded = $copyId;
                    $pitch->billed = 1;
                    $pitch->published = 1;
                    $pitch->status = 1;
                    $pitch->confirmed = 0;
                    $pitch->title = $originalPitch->title;
                    $pitch->started = date(MYSQL_DATETIME_FORMAT);
                    $pitch->awardedDate = date(MYSQL_DATETIME_FORMAT);
                    $pitch->finishDate = date(MYSQL_DATETIME_FORMAT, time() + 10 * DAY);
                    $pitch->save();
                    SolutionsMailer::sendSolutionBoughtNotification($pitch->awarded);
                    SpamMailer::sendNewLogosaleProject($pitch);
                    $project = $pitch;
                    if (($project->category_id != 20) && (!empty($project->ga_id))) {
                        $options = ['client_id' => $project->ga_id, 'user_id' => $project->user_id];
                        $tracking = new \Racecore\GATracking\GATracking('UA-9235854-5', $options);

                        $transaction = $tracking->createTracking('Ecommerce\Transaction');
                        $transaction->setID($project->id);
                        $transaction->setRevenue($project->total);
                        $transaction->setCurrency('RUB');
                        $result = $tracking->sendTracking($transaction);

                        $item = $tracking->createTracking('Ecommerce\Item');
                        $item->setTransactionID($project->id);
                        $item->setName($project->title);
                        $item->setPrice($project->total);
                        $item->setQuantity(1);
                        $item->setSku($project->id . '_1');
                        $item->setCategory('Распродажа логотипов');
                        $item->setCurrency('RUB');
                        $result = $tracking->sendTracking($item);
                    }
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    /**
     * Метод активирует оплату записи штрафа и назначает победителя
     *
     * @param $penaltyId
     * @return mixed
     */
    public static function activatePenalty($penaltyId)
    {
        $penalty = self::first($penaltyId);
        $solution = Solution::first(['conditions' => ['Solution.id' => $penalty->awarded], 'with' => ['Pitch']]);
        Solution::selectSolution($solution);
        $data = [
            'billed' => 1,
            'status' => 2,
            'started' => date('Y-m-d H:i:s'),
            'finishDate' => date('Y-m-d H:i:s')
        ];
        $project = $penalty;
        if (($project->category_id != 20) && (!empty($project->ga_id))) {
            $options = ['client_id' => $project->ga_id, 'user_id' => $project->user_id];
            $tracking = new \Racecore\GATracking\GATracking('UA-9235854-5', $options);

            $transaction = $tracking->createTracking('Ecommerce\Transaction');
            $transaction->setID($project->id);
            $transaction->setRevenue($project->total);
            $transaction->setCurrency('RUB');
            $result = $tracking->sendTracking($transaction);

            $item = $tracking->createTracking('Ecommerce\Item');
            $item->setTransactionID($project->id);
            $item->setName($project->title);
            $item->setPrice($project->total);
            $item->setQuantity(1);
            $item->setSku($project->id . '_1');
            $item->setCategory('Штраф');
            $item->setCurrency('RUB');
            $result = $tracking->sendTracking($item);
        }
        return $penalty->save($data);
    }

    public static function declineLogosalePitch($pitchId, $designerId)
    {
        if ($pitch = self::first(['conditions' => ['Pitch.id' => $pitchId, 'Pitch.blank' => 1], 'with' => ['Solution']])) {
            $solutionCopy = Solution::first($pitch->awarded);
            if ($designerId == $solutionCopy->user_id) {
                $pitch->awarded = 0;
                $pitch->billed = 0;
                $pitch->published = 0;
                $pitch->status = 0;
                $pitch->confirmed = 0;
                $pitch->title = 'Logosale Pitch';
                $pitch->started = '0000-00-00 00:00:00';
                $pitch->finishDate = '0000-00-00 00:00:00';
                $pitch->save();
                return true;
            } else {
                return false;
            }
        }
    }

    public static function acceptLogosalePitch($pitchId, $designerId)
    {
        if ($pitch = self::first(['conditions' => ['Pitch.id' => $pitchId, 'Pitch.blank' => 1], 'with' => ['Solution']])) {
            $solutionCopy = Solution::first($pitch->awarded);
            if ($designerId == $solutionCopy->user_id) {
                $pitch->confirmed = 1;
                $pitch->save();
                return true;
            } else {
                return false;
            }
        }
    }


    public static function getCountBilledMultiwinner($pitchId)
    {
        if ($pitch = self::first($pitchId)) {
            return count(self::all(['conditions' => ['user_id' => $pitch->user_id, 'billed' => 1, 'multiwinner' => $pitch->id]]));
        }
    }

    /**
     * Метод возвращяет номер операции для проекта номер $projectId
     *
     * @param $projectId
     * @return null
     */
    public static function getPaymentId($projectId)
    {
        // есть запись с мастербанка
        if ($transaction = Transaction::first(['conditions' => [
            "`ORDER`" => $projectId,
            'TRTYPE' => 21,
        ]])) {
            return $transaction->RRN;
        }
        // Paymaster
        if ($transaction = Paymaster::first(['conditions' => [
            'LMI_PAYMENT_NO' => $projectId,
        ]])) {
            return $transaction->LMI_SYS_PAYMENT_ID;
        }
        // Payanyway
        if ($transaction = Payanyway::first(['conditions' => [
            'MNT_TRANSACTION_ID' => $projectId,
        ]])) {
            return $transaction->MNT_OPERATION_ID;
        }
        return null;
    }

    /**
     * Метод определяет, были ли возвращены заказчку деньги
     *
     * @param $projectId
     * @return bool
     */
    public static function isMoneyBack($projectId)
    {
        if ($note = Note::first(['conditions' => ['pitch_id' => $projectId]]) and $note->status == 2) {
            return true;
        }
        return false;
    }

    /**
     * Метод определяет, годится ли питч для распродажи
     *
     * @param $pitch
     * @return bool
     */
    public static function isReadyForLogosale($pitch)
    {
        if (is_object($pitch) && method_exists($pitch, 'data')) {
            $pitch = $pitch->data();
        }
        if (is_array($pitch)) {
            if ($pitch['id'] == '102537') {
                return false;
            }
            if (($pitch['status'] == 2) && ($pitch['category_id'] == 1) &&
                ($pitch['private'] == 0) && ($pitch['totalFinishDate'] !== '0000-00-00 00:00:00') && (strtotime($pitch['totalFinishDate']) < (time() - (30 * DAY)))) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Метод генерирует случайный идентификатор для проекта с $id
     *
     * @param $id
     * @return mixed
     */
    public static function generateNewPaytureId($id)
    {
        $idStrlen = strlen($id);
        $pitch = self::first($id);
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < (49 - $idStrlen); $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $pitch->payture_id = $randomString . '_' . $id;
        $pitch->save();
        return $pitch;
    }

    /**
     * Метод определяет, оставил ли дизайнер свой отзыва для проекта $pitchIdOrObject
     *
     * @param $projectIdOrObject айди или объект питча
     * @return bool
     */
    public static function hadDesignerLeftRating($projectIdOrObject)
    {
        if (is_object($projectIdOrObject)) {
            $projectIdOrObject = $projectIdOrObject->id;
        }
        if (($pitch = Pitch::first($projectIdOrObject)) and ($pitch->status == 2)) {
            if (Grade::isDesignerRatingExistsForProject($projectIdOrObject)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Метод показывает среднее количество решений для категории $categoryId и качества награды $type
     *
     * @param $categoryId
     * @param $type ('good'|'normal'|'minimal')
     * @return bool|float|int|mixed
     */
    public static function getStatisticalAverages($categoryId, $type)
    {
        $cacheKey = $categoryId . '_' . $type;
        if (!$result = Rcache::read($cacheKey)) {
            $category = Category::first($categoryId);
            $conditions = [
                'category_id' => $categoryId,
                'billed' => 1,
                'published' => 1,
                'ideas_count' => ['>' => 0],
                'started' => ['>' => '2015-01-01 00:00:00'],
            ];
            switch ($type) {
                case 'good':
                    $conditions += [
                        'price' => ['>=' => $category->goodAward]
                    ];
                    break;
                case 'normal':
                    $conditions += [
                        'price' => ['>=' => $category->normalAward, '<' => $category->goodAward]
                    ];
                    break;
                case 'minimal':
                    $conditions += [
                        'price' => ['<' => $category->normalAward]
                    ];
                    break;
            }
            $pitches = Pitch::all([
                'fields' => ['id', 'category_id', 'price', 'ideas_count'],
                'conditions' => $conditions
            ]);
            $count = count($pitches->data());
            if ($count > 0) {
                $total = 0;
                foreach ($pitches as $pitch) {
                    $total += $pitch->ideas_count;
                }
                $result = round($total / $count);
            } else {
                $result = 0;
            }
            Rcache::write($cacheKey, $result, [], '+1 month');
        }
        return $result;
    }

    public static function saveDraft($inputData)
    {
        $specificPitchData = $inputData['specificPitchData'];
        $featuresData = $inputData['features'];
        $commonPitchData = $inputData['commonPitchData'];
        if (!isset($featuresData['experts'])) {
            $expert = 0;
            $expertId = serialize([]);
        } else {
            $expert = 1;
            $expertId = serialize($featuresData['experts']);
        }
        if (!isset($commonPitchData['fileIds'])) {
            $commonPitchData['fileIds'] = [];
        }
        $type = 'company_project';
        $total = 0;
        if (isset($commonPitchData['type'])) {
            $type = $commonPitchData['type'];
        }
        if ($type !== 'company_project' && isset($featuresData['total'])) {
            $total = $featuresData['total'];
        }
        $categoryId = 20;
        if (isset($commonPitchData['category_id'])) {
            $categoryId = $commonPitchData['category_id'];
        }
        $data = [
            'user_id' => $commonPitchData['user_id'],
            'type' => $type,
            'category_id' => $categoryId,
            'title' => $commonPitchData['title'],
            'industry' => serialize($commonPitchData['jobTypes']),
            'started' => date('Y-m-d H:i:s'),
            'totalFinishDate' => '0000-00-00 00:00:00',
            'awardedDate' => '0000-00-00 00:00:00',
            'ideas_count' => 0,
            'price' => $featuresData['award'],
            'total' => $total,
            'fee' => 0,
            'awarded' => 0,
            'free' => 0,
            'fileDesc' => '',
            'moderated' => 0,
            'promocode' => '',
            'referal' => '',
            'referal_sum' => '',
            'last_solution' => 0,
            'multiwinner' => 0,
            'blank' => 0,
            'blank_id' => 0,
            'business-description' => '',
            'description' => $commonPitchData['description'],
            'status' => 0,
            'pinned' => (bool) $featuresData['pinned'],
            'expert' => $expert,
            'private' => (bool) $featuresData['private'],
            'social' => (bool) $featuresData['social'],
            'expert-ids' => $expertId,
            'email' => (bool) $featuresData['email'],
            'finishDate' => $commonPitchData['finishDate'],
            'chooseWinnerFinishDate' => $commonPitchData['chooseWinnerFinishDate'],
            'timelimit' => 0,
            'billed' => 0,
            'brief' => (bool) $featuresData['brief'],
            'phone-brief' => $commonPitchData['phone-brief'],
            'guaranteed' => 0,
            'materials' => $commonPitchData['materials'],
            'materials-limit' => $commonPitchData['materials-limit'],
            'fileFormats' => serialize($commonPitchData['fileFormats']),
            'fileFormatDesc' => $commonPitchData['fileFormatDesc'],
            'filesId' => serialize($commonPitchData['fileIds']),
            'specifics' => serialize($specificPitchData),
            'ga_id' => $commonPitchData['ga_id'],
            'premium' => (bool) $featuresData['premium'],
        ];
        if ((isset($commonPitchData['id'])) && (!empty($commonPitchData['id']))) {
            $pitch = Pitch::first((int) $commonPitchData['id']);
        } else {
            $pitch = Pitch::create();
        }
        $pitch->set($data);
        if ($pitch->save()) {
            return $pitch->id;
        } else {
            return null;
        }
    }

    /**
     * Метод определяет, является ли проект по подписке проектов на копирайтинг или нет
     *
     * @param $record
     * @return bool
     */
    public function isSubscriberProjectForCopyrighting($record)
    {
        if (($record->category_id == 20) && ($unSerialized = unserialize($record->specifics))) {
            if (is_bool($unSerialized['isCopyrighting'])) {
                $unSerialized['isCopyrighting'] = ($unSerialized['isCopyrighting']) ? 'true' : 'false';
            }
            if ((isset($unSerialized['isCopyrighting'])) && ($unSerialized['isCopyrighting'] === 'true')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Метод определяет, является ли проект проектом на копирайтинг или нет
     *
     * @param $record
     * @return bool
     */
    public function isCopyrighting($record)
    {
        if ((int) $record->category_id === 20) {
            return $record->isSubscriberProjectForCopyrighting();
        }
        return (int) $record->category_id === 7;
    }

    /**
     * Метод помечает проект как отказанный, создается заметку о возвравте, комментарий
     * и возвращяет деньги на кошелек
     *
     * @param $id
     * @return bool
     */
    public static function markAsRefunded($id)
    {
        if (($project = self::first($id)) && ($project->status != 2)) {
            if (!$note = Note::first(['conditions' => ['pitch_id' => $id]])) {
                $note = Note::create();
            }
            $note->set([
                'pitch_id' => $id,
                'status' => 2
            ]);
            $note->save();
            $data = [
                'user_id' => 108,
                'pitch_id' => $id,
                'public' => 1,
                'text' => 'Друзья, заказчик отказался от всех предложенных решений. К сожалению, такое случается. Мы благодарим всех за участие, и хотим напомнить, что права на свои идеи сохраняются за авторами, и вы можете адаптировать их для участия в другом питче!
Подробнее читайте тут: http://godesigner.ru/answers/view/51'];
            Comment::createComment($data);
            User::fillBalance($project->user_id, $project->price);
            $project->status = 2;
            $project->awarded = 0;
            return $project->save();
        }
        return false;
    }

    /**
     * Метод проверяет, нужно ли постить предупреждение в завершение проекта
     *
     * @param $projectId
     * @return bool
     */
    public static function isNeededToPostClosingWarning($projectId)
    {
        $project = Pitch::first($projectId);
        if (($project->status < 1) || ($project->awarded == 0)) {
            return false;
        }
        $daysBeforeAutoComment = 12;
        if (in_array($project->category_id, [3, 4])) {
            $daysBeforeAutoComment = 14;
        }
        $diff = (time() - strtotime($project->awardedDate)) / DAY;
        if (($diff  > $daysBeforeAutoComment) && (!self::isAutoClosingWarningPosted($projectId))) {
            return true;
        }
        return false;
    }

    /**
     * Метод генерирует текст авто предупреждения для завершающего этапа
     *
     * @param $projectId
     * @return string
     */
    public static function getAutoClosingWarningComment($projectId)
    {
        $project = Pitch::first(['conditions' => ['Pitch.id' => $projectId], 'with' => ['Category']]);
        $projectOwner = User::first($project->user_id);
        $solution = Solution::first($project->awarded);
        $designer = User::first($solution->user_id);
        $nameInflector = new NameInflector();
        $planDaysDefault = $project->category->default_timelimit;
        if (in_array($project->category_id, [3, 4])) {
            $planDaysDefault = 17;
        }
        $planDateToComplete = date('d.m.Y H:i', (strtotime(self::getPlannedDateToComplete($projectId))));
        $newPlanDateToComplete = date('d.m.Y H:i', (strtotime($project->awardedDate) + ($planDaysDefault + 2) * DAY));

        $ownerFormatted = $nameInflector->renderName($projectOwner->first_name, $projectOwner->last_name);
        $designerFormatted = $nameInflector->renderName($designer->first_name, $designer->last_name);
        $result = "@$ownerFormatted, cрок завершительного этапа длится $planDaysDefault дней, ваш проект должен был быть закрыт к $planDateToComplete.
        <br/><br/>Мы убедительно просим вас активизироваться на сайте, внести финальную правку, утвердить макеты и проверить исходники не позже $newPlanDateToComplete, в противном случае мы будем вынуждены инициировать завершение проекта согласно регламенту.
        <br/><br/>@$designerFormatted, мы просим вас выложить исходники в том виде, каком их последний раз утвердил заказчик, к $newPlanDateToComplete.
        <br/><br/>Спасибо за понимание и содействие!";
        return $result;
    }

    /**
     * Метод возвращяет плановую дату завершения завершающего этапа
     *
     * @param $projectId
     * @return false|string
     */
    public static function getPlannedDateToComplete($projectId)
    {
        $project = Pitch::first(['conditions' => ['Pitch.id' => $projectId], 'with' => ['Category']]);
        $planDaysDefault = $project->category->default_timelimit;
        if (in_array($project->category_id, [3, 4])) {
            $planDaysDefault = 17;
        }
        $planDateToComplete = date(MYSQL_DATETIME_FORMAT, (strtotime($project->awardedDate) + $planDaysDefault * DAY));
        return $planDateToComplete;
    }

    /**
     * Метод проверяет, было ли запощено в завершении автопредупреждение о необходимости
     * закончить проект
     *
     * @param $projectId
     * @return bool
     */
    public static function isAutoClosingWarningPosted($projectId)
    {
        $project = Pitch::first($projectId);
        $solution = Solution::first($project->awarded);
        $text = 'Мы убедительно просим вас активизироваться на сайте, внести финальную правку';
        if ($winComment = Wincomment::first(['conditions' => [
            'solution_id' => $solution->id,
            'text' => ['LIKE' => '%' . $text .  '%']
        ]])) {
            return true;
        }
        return false;
    }

    /**
     * Метод определяет, на каком шаге завершения находится проект
     *
     * @param $projectId
     * @return mixed
     */
    public static function getCurrentClosingStep($projectId)
    {
        $project = Pitch::first($projectId);
        $solution = Solution::first($project->awarded);
        return $solution->step;
    }

    /**
     * Метод возвращяет следующий зарезервированный айди для платежа за
     * просроченный выбор победителя
     *
     * @param $userId int пользователя
     * @return int
     */
    public static function getNextPenaltyId($userId, $solutionId)
    {
        if (!$payment = self::first([
            'conditions' => [
                'user_id' => $userId,
                'billed' => 0,
                'type' => 'penalty',
            ]
        ])) {
            $gatracking = new \Racecore\GATracking\GATracking('UA-9235854-5');
            $gaId = $gatracking->getClientId();
            $data = [
                'user_id' => $userId,
                'type' => 'penalty',
                'category' => 98,
                'title' => 'Оплата штрафа',
                'awarded' => $solutionId,
                'ga_id' => $gaId
            ];
            $payment = self::create($data);
            $payment->save();
            return $payment->id;
        }
        $payment->awarded = $solutionId;
        $payment->save();
        return $payment->id;
    }

    public function getPenaltyAmount($projectRecord)
    {
        $pitchHelper = new \app\extensions\helper\Pitch();
        $diff = time() - $pitchHelper->getChooseWinnerTime($projectRecord);
        return ceil($diff / 60 / 60) * 25;
    }

    /**
     * Метод проверяет, нужно ли к проекту применять штрафные санкции за долгий выбор победителя
     *
     * @param $projectId
     * @return bool
     */
    public static function isPenaltyNeededForProject($projectId)
    {
        $projectRecord = self::first($projectId);
        if (($projectRecord->category_id == 20) || (($projectRecord->status == 1) && ($projectRecord->awarded != 0))) {
            return false;
        }
        $time = strtotime($projectRecord->finishDate);
        if ($projectRecord->expert == 1) {
            $pitchHelper = new \app\extensions\helper\Pitch();
            $time = $pitchHelper->expertOpinion($projectRecord->id);
            if ($pitchHelper->isWaitingForExperts($projectRecord)) {
                return false;
            }
        }
        if (($time + 4 * DAY) < time()) {
            return true;
        }
        return false;
    }

    /**
     * Метод возвращает целое количество дней (округлённое вниз), отведенное на выбор победителей
     * нужно для отображения в уведомлениях для заказчиков
     *
     * @param $projectId
     * @return int
     */
    public static function getDaysForWinnerSelection($projectId)
    {
        $project = self::first($projectId);
        if ((int) $project->category_id === 20) {
            $finishDate = new \DateTime($project->finishDate);
            $chooseWinnerFinishDate = new \DateTime($project->chooseWinnerFinishDate);
            $interval = $finishDate->diff($chooseWinnerFinishDate);
            return (int) $interval->format('%a');
        }
        return 4;
    }

    /**
     * Метод возвращает дату, когда у заказчика заканчивается время на выбор победителя
     *
     * @param $record
     * @return \DateTime
     */
    public function getEndOfWinnerSelectionDateTime($record)
    {
        if ((int) $record->category_id === 20) {
            $finishDateTimeFormattedString = $record->chooseWinnerFinishDate;
        } else {
            $finishDateTimeFormattedString = date('Y-m-d H:i:s', strtotime($record->finishDate) + 4 * DAY);
        }
        return new \DateTime($finishDateTimeFormattedString);
    }

    /**
     * Метод определяет, допустимо ли отправлять смс клиенту с учетом сдвига в 4-5 часов.
     * Запретные часы 23-07
     *
     * @param $record
     * @return bool
     */
    public function isOkToSendSmsForFinishWinnerSelectionWarning($record)
    {
        $date = $record->getEndOfWinnerSelectionDateTime();
        $hour = $date->format('G');
        if (($hour >= 3) && ($hour < 11)) {
            return false;
        }
        return true;
    }

    /**
     * Метод возвращяет минимальную награду на указанную дату с учётом скидки на выходных
     *
     * @param $categoryId
     * @param \DateTime $dateTime
     * @return int
     */
    public static function getMinimalAwardForCategoryForDate($categoryId, \DateTime $dateTime)
    {
        $category = Category::first($categoryId);
        $defaultLow = ((int) $dateTime->format('N') > 5) ? $category->discountPrice : $category->minAward;
        return (int) $defaultLow;
    }

    /**
     * Метод определяет, выше или равна ли награда минимальной на указанной день (с учётом возможных скидок по выходным)
     *
     * @param $record
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isAwardValidForDate($record, \DateTime $dateTime)
    {
        return $record->price >= self::getMinimalAwardForCategoryForDate($record->category_id, $dateTime);
    }
}
