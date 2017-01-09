<?php

namespace app\controllers;

use \app\models\User;
use \app\models\Event;
use \app\models\Pitch;
use app\models\News;
use \app\models\Favourite;
use \lithium\storage\Session;
use \app\extensions\helper\Stream;

/**
 * Class EventsController
 *
 * Класс для работы с событиями
 *
 * @package app\controllers
 */
class EventsController extends AppController
{

    /**
     * @var array публичные методы
     */
    public $publicActions = ['feed', 'getsol', 'autolikes', 'getaccesstoken', 'access'];

    /**
     *
     *
     * @return array
     */
    public function updates()
    {
        if (!isset($this->request->query['page'])) {
            $this->request->query['page'] = 1;
        }
        if (!isset($this->request->query['created'])) {
            $this->request->query['created'] = null;
        }
        $updates = Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), $this->request->query['page'], $this->request->query['created']);
        $nextUpdates = count(Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), $this->request->query['page'] + 1, null));
        $count = count($updates);
        return compact('updates', 'count', 'nextUpdates');
    }

    /**
     * Метод для получения информации о новых проставленных лайках
     *
     * @return array
     */
    public function autolikes()
    {
        if (isset($this->request->query['created'])) {
            $offsetDate = date('Y-m-d H:i:s', strtotime($this->request->query['created']) + (2 * HOUR));
            $events = Event::all(['conditions' => [
                'Event.type' => 'LikeAdded',
                'created' => ['>=' => $offsetDate],

            ], 'order' => ['created' => 'desc']]);
            $eventsCount = 0;
            if ($events) {
                $newLatestDate = $this->request->query['created'];
                $eventsCount = (int) count($events->data());
                foreach ($events as $event) {
                    $newLatestDate = date('Y-m-d H:i:s', (strtotime($event->created) - (2 * HOUR) +  SECOND));
                    break;
                }
            } else {
                $newLatestDate = $this->request->query['created'];
            }
            return compact('events', 'offsetDate', 'newLatestDate', 'eventsCount', 'first');
        } else {
            $pong = 'pong';
            return compact($pong);
        }
    }

    /**
     * Метод для вывода всех новых событий и новостей
     *
     * @return array
     */
    public function feed()
    {
        $tag = null;
        if (isset($this->request->query['tag'])) {
            $tag = $this->request->query['tag'];
        }
        if (isset($this->request->query['page'])) {
            $subscribed = User::getSubscribedPitches(Session::read('user.id'));
            $updates = Event::getEvents($subscribed, $this->request->query['page'], null, $this->userHelper->getId(), $tag);
            $nextUpdates = count(Event::getEvents($subscribed, $this->request->query['page'] + 1, null, $this->userHelper->getId(), $tag));
        }
        if (!isset($this->request->query['page'])) {
            $this->request->query['page'] = 1;
        }
        if (!empty($this->request->query['pitchDate'])) {
            $pitches = Pitch::all(['fields' => ['title', 'price', 'started'], 'conditions' => ['status' => 0, 'published' => 1, 'multiwinner' => 0, 'started' => ['>' => $this->request->query['pitchDate']]], 'order' => ['started' => 'desc'], 'limit' => 5]);
        }
        if (!empty($this->request->query['created'])) {
            $updates = Event::getEvents(User::getSubscribedPitches($this->userHelper->getId()), $this->request->query['page'], $this->request->query['created'], $this->userHelper->getId(), $tag);
        } elseif (!isset($this->request->query['created'])) {
            $this->request->query['created'] = 0;
        }
        if (!empty($this->request->query['twitterDate'])) {
            $streamHelper = new Stream();
            $twitter = $streamHelper->renderStreamFeed(10, $this->request->query['twitterDate']);
        }
        if (!empty($this->request->query['solutionDate'])) {
            $solutions = Event::all(['conditions' => ['Event.type' => 'SolutionAdded', 'private' => 0, 'category_id' => ['!=' => 7], 'multiwinner' => 0, 'created' => ['>' => $this->request->query['solutionDate']]], 'order' => ['Event.created' => 'desc'], 'limit' => 10, 'with' => ['Pitch']]);
        }
        if (!empty($this->request->query['newsDate'])) {
            $post = News::getPost($this->request->query['newsDate']);
            $news = News::getNews($this->request->query['newsDate']);
        }
        $count = count($updates);
        return compact('subscribed', 'updates', 'count', 'nextUpdates', 'post', 'news', 'twitter', 'pitches', 'solutions');
    }

    public function getsol()
    {
        $solpages = Event::getEventSolutions(Session::read('user'), $this->request->query['page']);
        return compact('solpages');
    }

    /**
     * Метод для получения информации о новых твитах про работу для дизайнеров
     *
     * @return array
     */
    public function job()
    {
        $job = \app\models\Tweet::all(['limit' => 10, 'page' => $this->request->data['page']]);
        $count = count(\app\models\Tweet::all(['limit' => 10, 'page' => $this->request->data['page'] + 1]));
        return compact('job', 'count');
    }

    /**
     * Метод для получения информции о новых проектах
     *
     * @return array
     */
    public function pitches()
    {
        $pitches = Pitch::all(['fields' => ['id', 'title', 'price', 'started'], 'conditions' => ['status' => 0, 'published' => 1, 'multiwinner' => 0], 'order' => ['started' => 'desc'], 'limit' => 5, 'page' => $this->request->data['page']]);
        $count = 0;
        if ($pitches) {
            $count = count(Pitch::all(['conditions' => ['status' => 0, 'published' => 1, 'multiwinner' => 0], 'order' => ['started' => 'desc'], 'limit' => 5, 'page' => $this->request->data['page'] + 1]));
        }
        return compact('pitches', 'count');
    }

    /**
     * Метод для получения информации о последних новостях
     *
     * @return array
     */
    public function news()
    {
        $news = News::getNews(0, $this->request->data['page']);
        $count = count(News::getNews(0, $this->request->data['page'] + 1));
        return compact('news', 'count');
    }

    /**
     * Метод возвращяет события лайков
     *
     * @return array
     */
    public function liked()
    {
        $likes = [];
        $temp = [];
        $fav = [];
        if ($this->request->id) {
            $likes = Event::all(['conditions' => [
                'Event.type' => 'LikeAdded',
                'solution_id' => $this->request->id
            ], 'order' => ['Event.created' => 'desc']]);
            foreach ($likes as $like) {
                $temp[] = $like->user->id;
            }
            if (!empty($temp)) {
                $fav = Favourite::all(['conditions' => ['pitch_id' => 0, 'fav_user_id' => $temp]]);
            }
        }
        return compact('likes', 'fav');
    }

    /**
     * Метод возвращяет теги для новостей
     *
     * @return string
     */
    public function newstags()
    {
        if (isset($this->request->query['name']) && strlen($this->request->query['name']) > 0) {
            $tags = News::all(['fields' => ['tags'], 'conditions' => ['tags' => ['LIKE' => ['%' . $this->request->query['name'] . '%']]]]);
            return json_encode($tags->data());
        }
    }

    /**
     * Метод сохраняет новост
     *
     * @todo переместить в контроллер "News"
     * @return array
     */
    public function add()
    {
        $result = false;
        if ($this->request->data) {
            $result = News::saveNewsByAdmin($this->request->data);
        }
        return compact('result');
    }

    /**
     * Получение токена доступа для перевода
     *
     * @return bool|mixed|null
     */
    public function getaccesstoken()
    {
        return Event::getBingAccessToken();
    }
}
