<?php

namespace app\models;

use \app\extensions\helper\NameInflector;
use \app\models\Pitch;
use \app\models\User;
use \app\models\Comment;
use \app\models\Category;
use \app\models\Solution;
use \app\models\Favourite;
use \app\extensions\helper\MoneyFormatter;
use \lithium\storage\Session;
use app\extensions\storage\Rcache;

class Event extends AppModel
{

    public $belongsTo = ['Pitch', 'User', 'Comment', 'Solution', 'News'];

    public static function __init()
    {
        parent::__init();

        self::applyFilter('find', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if (is_object($result)) {
                $addUpdateText = function ($record) {
                    $record->updateText = '';
                    if ($record->type == 'SolutionAdded') {
                        $record->updateText = '';
                    }
                    if ($record->type == 'SolutionPicked') {
                        $moneyFormatter = new MoneyFormatter();
                        $solution = Solution::first($record->solution_id);
                        $record->updateText = '#' . $solution->num . ' стал победителем проекта. Поздравляем! Вознаграждение ' . $moneyFormatter->formatMoney($record->pitch->price, ['suffix' => ' Р.-']);
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
                $addBindings = function ($record) {
                    if ((isset($record->solution_id)) && ($record->solution_id > 0)) {
                        $record->solution = Solution::first(['with' => ['Pitch'], 'conditions' => ['Solution.id' => $record->solution_id, 'category_id' => ['!=' => 7]]]);
                        if ($record->type == 'SolutionAdded') {
                            $record->pitchesCount = Pitch::getCountBilledMultiwinner($record->pitch_id);
                            $selectedsolution = false;
                            $nominatedSolutionOfThisPitch = Solution::first([
                                        'conditions' => ['nominated' => 1, 'pitch_id' => $record->solution->pitch->id]
                            ]);
                            if ($nominatedSolutionOfThisPitch) {
                                $selectedsolution = true;
                            }
                            $record->selectedSolutions = $selectedsolution;
                            $allowLike = 0;
                            if (Session::read('user.id') && (!$like = Like::first('first', ['conditions' => ['solution_id' => $record->solution->id, 'user_id' => Session::read('user.id')]]))) {
                                $allowLike = 1;
                            }
                            $record->allowLike = $allowLike;
                            $record->likes = Event::all(['conditions' => ['Event.type' => 'LikeAdded', 'solution_id' => $record->solution_id], 'order' => ['Event.created' => 'desc']]);
                        }
                    } else {
                        //$record->solution = Solution::getBestSolution($record->pitch_id);
                        $record->solution = null;
                    }
                    if ($record->solution && ($record->solution->pitch->private == 1) || ($record->solution && $record->solution->pitch->category_id == 7)) {
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
                        if (Session::read('user.id') && (!$like = Like::first('first', ['conditions' => ['solution_id' => $record->solution->id, 'user_id' => Session::read('user.id')]]))) {
                            $allowLike = 1;
                        }
                        $record->allowLike = $allowLike;
                    }
                    if ((isset($record->user_id)) && ($record->user_id > 0)) {
                        $record->user = User::first(['conditions' => ['id' => $record->user_id], 'fields' => ['id', 'first_name', 'last_name', 'isAdmin', 'gender']]);
                    }
                    if (($record->type == 'newsAdded') && ((int) $record->news_id > 0)) {
                        $news = News::first($record->news_id);
                        $news->likes = Event::all(['conditions' => ['Event.type' => 'LikeAdded', 'news_id' => $record->news_id], 'order' => ['Event.created' => 'asc']]);
                        $allowLike = 0;
                        if (Session::read('user.id') && (!$like = Like::first('first', ['conditions' => ['news_id' => $record->news_id, 'user_id' => Session::read('user.id')]]))) {
                            $allowLike = 1;
                        }
                        $record->allowLike = $allowLike;
                        $str = strpos($news->tags, '|');
                        if ($str) {
                            $news->tags = substr($news->tags, 0, $str);
                        }
                        $host = parse_url($news->link);
                        $record->host = '';
                        if (isset($host['host'])) {
                            $record->host = $host['host'];
                        }
                        if ($news->admin == 0) {
                            $news->short = html_entity_decode($news->short, ENT_COMPAT, 'UTF-8');
                            $news->short = mb_strimwidth($news->short, 0, 250, '...', 'UTF-8');
                        }
                        $record->news = $news;
                    } elseif ($record->type == 'FavUserAdded') {
                        $record->user_fav = User::first(['conditions' => ['id' => $record->fav_user_id], 'fields' => ['id', 'first_name', 'last_name', 'isAdmin', 'gender']]);
                    } elseif ($record->type == 'RetweetAdded') {
                        $cache = \app\extensions\storage\Rcache::read('RetweetsFeed');
                        if (is_array($cache)) {
                            foreach ($cache as $k => $v) {
                                if ($k == $record->tweet_id) {
                                    $record->html = $v;
                                    break;
                                }
                            }
                        }
                    }

                    return $record;
                };
                $addHumanDate = function ($record) {
                    if (isset($record->created)) {
                        $record->humanCreated = date('d.m.Y H:i', strtotime($record->created));
                        $record->humanCreatedShort = date('d.m.Y', strtotime($record->created));
                    }
                    return $record;
                };
                $addJSDate = function ($record) {
                    if (isset($record->created)) {
                        $record->jsCreated = date('Y/m/d H:i:s', strtotime($record->created));
                    }
                    return $record;
                };
                $addCreator = function ($record) {
                    $autoCreators = ['SolutionPicked', 'PitchFinished'];
                    if (in_array($record->type, $autoCreators)) {
                        $record->creator = 'Go Designer';
                    } else {
                        if ($record->user) {
                            $nameInflector = new NameInflector();
                            $record->creator = $nameInflector->renderName($record->user->first_name, $record->user->last_name);
                            if ($record->type == 'FavUserAdded') {
                                $record->creator_fav = $nameInflector->renderName($record->user_fav->first_name, $record->user_fav->last_name);
                            }
                        }
                    }
                    return $record;
                };
                $addHumanType = function ($record) {
                    $record->humanType = '';
                    $typesMap = [
                        'SolutionPicked' => 'Выбран победитель',
                        'CommentAdded' => 'Добавлен комментарий',
                        'CommentCreated' => 'Добавлен комментарий',
                        'PitchFinished' => 'Проект завершён',
                        'SolutionAdded' => 'Добавлено решение',
                        'PitchCreated' => 'Новый проект',
                        'newsAdded' => 'Добавлена новость',
                        'RatingAdded' => 'Добавлен рейтинг'
                    ];
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

    public static function createEvent($pitchId, $type, $userId, $solutionId = 0, $commentId = 0, $news_id = 0)
    {
        $newEvent = self::create();
        $newEvent->created = date('Y-m-d H:i:s');
        $newEvent->pitch_id = $pitchId;
        $newEvent->user_id = $userId;
        $newEvent->solution_id = $solutionId;
        $newEvent->comment_id = $commentId;
        $newEvent->type = $type;
        $newEvent->news_id = $news_id;
        $newEvent->fav_user_id = 0;
        $newEvent->tweet_id = 0;
        return $newEvent->save();
    }

    public static function createEventNewsAdded($news_id, $pitch_id, $created)
    {
        $newEvent = Event::create();
        $newEvent->created = $created;
        $newEvent->pitch_id = $pitch_id;
        $newEvent->type = 'newsAdded';
        $newEvent->news_id = $news_id;
        $result =  $newEvent->save();
        if ($result) {
            $id = 'https://www.godesigner.ru/news?event=' . $newEvent->id;
            try {
                $url = 'https://graph.facebook.com';
                $data = ['id' => $id, 'scrape' => 'true'];
                $options = [
                    'http' => [
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    ],
                ];
                $context  = stream_context_create($options);
                $postResult = file_get_contents($url, false, $context);
            } catch (Exception $e) {
            }
        }
        return $result;
    }

    public static function getEvents($pitchIds, $page = 1, $created = null, $user = false, $tag = null)
    {
        $eventList = $conditions = [];
        $limit = 14;
        $with = [];
        if (isset($created)) {
            $page = 1;
            $limit = 100;
            $conditions = ['Event.created' => ['>' => $created]];
        }
        if ((!empty($pitchIds)) && (empty($tag))) {
            $conditions += Event::createConditions($pitchIds, $user);
        } elseif (!empty($tag)) {
            $with = ['News'];
            $conditions += ['type' => 'newsAdded', 'News.tags' => $tag];
        } else {
            $conditions += ['type' => ['RetweetAdded', 'newsAdded']];
        }
        $events = Event::find('all', [
                'conditions' => $conditions,
                'order' => ['Event.created' => 'desc'],
                'limit' => $limit,
                'page' => $page,
                'with' => $with
            ]
        );
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
                    if (Comment::find('count', ['conditions' => ['question_id' => $event->comment->id, ['public = 1 OR user_id = ' . Session::read('user.id')]]]) == 0) {
                        continue;
                    }
                }

                // Child
                if (($event->comment->question_id != 0) && ($event->comment->public != 1) && ($event->comment->reply_to != Session::read('user.id'))) {
                    if (Comment::find('count', ['conditions' => ['id' => $event->comment->question_id, ['public = 1 OR user_id = ' . Session::read('user.id')]]]) == 0) {
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

    public static function getEventSolutions($user = null, $page = 1)
    {
        if ($user) {
            return Event::all(['conditions' => ['Event.type' => 'SolutionAdded', 'private' => 0, 'category_id' => ['!=' => 7], 'multiwinner' => 0], 'order' => ['Event.created' => 'desc'], 'limit' => 10, 'page' => $page, 'with' => ['Pitch']]);
        } else {
            $solutions = Solution::all([
                'conditions' => [
                    'Pitch.private' => 0,
                    'Pitch.category_id' => ['!=' => 7],
                    'Pitch.multiwinner' => 0,
                    'Solution.created' => ['>' => date('Y-m-d H:i:s', time() - 2 * DAY)]
                ],
                'order' => ['Solution.likes' => 'desc', 'Solution.views' => 'desc'],
                'limit' => 10,
                'page' => $page,
                'with' => ['Pitch']
            ]);
            if ($solutions) {
                $solutionHolder = [];
                foreach ($solutions as $solution) {
                    $solutionHolder[$solution->id] = $solution->images;
                }
                $data = $solutions->data();
                if (count($data) > 0) {
                    $keys = array_keys($data);
                    $cacheKey = 'geteventssolutionguest_' .serialize($keys);
                    if (!$solpages = Rcache::read($cacheKey)) {
                        $solpages = Event::all([
                            'conditions' => [
                                'Event.type' => 'SolutionAdded',
                                'Event.solution_id' => $keys
                            ],
                            'order' => ['Solution.likes' => 'desc', 'Solution.views' => 'desc'],
                            'with' => ['Pitch', 'Solution']
                        ]);
                        foreach ($solpages as $event) {
                            $event->solution->images = $solutionHolder[$event->solution->id];
                        }
                        Rcache::write($cacheKey, $solpages, [], '+1 hour');
                    }
                } else {
                    $solpages = [];
                }
            } else {
                $solpages = [];
            }
            return $solpages;
        }
    }

    public static function getSidebarEvents($created, $limit = null)
    {
        $eventList = $conditions = [];
        if ($created == false) {
            $conditions = ['Event.type' => ['PitchCreated', 'PitchFinished']];
        } else {
            $conditions = ['created' => ['>' => $created], 'Event.type' => ['PitchCreated', 'PitchFinished']];
        }
        $events = Event::all([
                    'conditions' => $conditions,
                    'order' => ['created' => 'desc'],
                    'limit' => $limit,
                    'with' => ['Pitch']
        ]);
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

    public static function createConditions($input, $user = false)
    {
        $list = [];
        foreach ($input as $pitchId => $created) {
            //if($user != '32') {
                $list[] = ['AND' => ['type' => ['CommentAdded', 'CommentCreated', 'SolutionAdded', 'RatingAdded'], 'pitch_id' => $pitchId, 'created' => ['>=' => $created]]];
            //}
        }
        //if($user != '32') {
            $list[] = ['AND' => ['type' => ['PitchCreated', 'newsAdded', 'RetweetAdded']]];
        //}
        if ($user) {
            // заволловили меня или я кого-то
            // смотрим, кого зафоловили те, кого зафоловил я

            $favourites = Favourite::all(['conditions' => ['pitch_id' => 0, 'user_id' => $user]]);
            $idsOfFollowing = [];
            foreach ($favourites as $favourite) {
                $idsOfFollowing[] = $favourite->fav_user_id;
            }
            $idsOfFollowing[] = $user;
            //$idsOfFollowing[] = '108';
            $list[] = ['type' => 'FavUserAdded', 'user_id' => $idsOfFollowing];
            $list[] = ['AND' => ['type' => ['FavUserAdded'], 'OR' => [['user_id' => $idsOfFollowing], ['fav_user_id' => $user]]]];
            $list[] = ['AND' => ['type' => ['CommentAdded', 'CommentCreated', 'SolutionAdded'], 'user_id' => $idsOfFollowing]];
            $list[] = ['AND' => ['type' => 'LikeAdded', 'user_id' => $idsOfFollowing, 'news_id' => 0]];
        }
        $output = ['OR' => $list];
        return $output;
    }

    public static function getBingAccessToken()
    {
        $accesstoken = null;
        if (!$accesstoken = Rcache::read('bingaccesstoken')) {
            $url = 'https://www.godesigner.ru/microsoft.php';
            $response = file_get_contents($url, false);
            $decoded = json_decode($response, true);
            $accesstoken = $decoded['access_token'];
            if (isset($decoded['expires_in']) and (isset($accesstoken))) {
                Rcache::write('bingaccesstoken', $accesstoken, '+' . ($decoded['expires_in'] - 100) . ' seconds');
            }
        }
        return $accesstoken;
    }
}
