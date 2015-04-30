<?php

namespace app\controllers;

use \app\models\User;
use \app\models\Event;
use \app\models\Pitch;
use app\models\News;
use \lithium\storage\Session;
use \app\extensions\helper\Stream;
use app\extensions\storage\Rcache;

class EventsController extends \app\controllers\AppController {

    public $publicActions = array('feed', 'getsol', 'autolikes', 'getaccesstoken', 'access');

    public function updates() {
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

    public function autolikes() {
        if(isset($this->request->query['created'])) {
            $offsetDate = date('Y-m-d H:i:s', strtotime($this->request->query['created']) + (2 * HOUR));
            $events = Event::all(array('conditions' => array(
                'type' => 'LikeAdded',
                'created' => array('>=' => $offsetDate),

            ), 'order' => array('created' => 'desc')));
            $eventsCount = 0;
            if($events) {
                $newLatestDate = $this->request->query['created'];
                $eventsCount = (int) count($events->data());
                foreach($events as $event) {
                    $newLatestDate = date('Y-m-d H:i:s', (strtotime($event->created) - (2 * HOUR) +  SECOND));
                    break;
                }
            }else {
                $newLatestDate = $this->request->query['created'];
            }
            return compact('events', 'offsetDate', 'newLatestDate', 'eventsCount', 'first');
        }else {
            $pong = 'pong';
            return compact($pong);
        }
    }

    public function feed() {
        if (isset($this->request->query['page'])) {
            $subscribed = User::getSubscribedPitches(Session::read('user.id'));
            $updates = Event::getEvents($subscribed, $this->request->query['page'], null, Session::read('user.id'));
            $nextUpdates = count(Event::getEvents($subscribed, $this->request->query['page'] + 1, null, Session::read('user.id')));
        }
        if (!isset($this->request->query['page'])) {
            $this->request->query['page'] = 1;
        }
        if (!empty($this->request->query['pitchDate'])) {
            $pitches = Pitch::all(array('fields' => array('title', 'price', 'started'), 'conditions' => array('status' => 0, 'published' => 1, 'multiwinner' => 0, 'started' => array('>' => $this->request->query['pitchDate'])), 'order' => array('started' => 'desc'), 'limit' => 5));
        }
        if (!empty($this->request->query['created'])) {
            $updates = Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), $this->request->query['page'], $this->request->query['created'], Session::read('user.id'));
        } elseif (!isset($this->request->query['created'])) {
            $this->request->query['created'] = 0;
        }
        if (!empty($this->request->query['twitterDate'])) {
            $twitter = Stream::renderStreamFeed(10, $this->request->query['twitterDate']);
        }
        if (!empty($this->request->query['solutionDate'])) {
            $solutions = Event::all(array('conditions' => array('type' => 'SolutionAdded', 'private' => 0, 'category_id' => array('!=' => 7), 'multiwinner' => 0, 'created' => array('>' => $this->request->query['solutionDate'])), 'order' => array('Event.created' => 'desc'), 'limit' => 10, 'with' => array('Pitch')));
        }
        if (!empty($this->request->query['newsDate'])) {
            $post = News::getPost($this->request->query['newsDate']);
            $news = News::getNews($this->request->query['newsDate']);
        }
        $count = count($updates);
        return compact('subscribed', 'updates', 'count', 'nextUpdates', 'post', 'news', 'twitter', 'pitches', 'solutions');
    }

    public function getsol() {
        $solpages = Event::getEventSolutions(Session::read('user'), $this->request->query['page']);
        return compact('solpages');
    }

    public function job() {
        $job = \app\models\Tweet::all(array('limit' => 10, 'page' => $this->request->data['page']));
        $count = count(\app\models\Tweet::all(array('limit' => 10, 'page' => $this->request->data['page'] + 1)));
        return compact('job', 'count');
    }

    public function pitches() {
        $pitches = Pitch::all(array('fields' => array('id', 'title', 'price', 'started'), 'conditions' => array('status' => 0, 'published' => 1, 'multiwinner' => 0), 'order' => array('started' => 'desc'), 'limit' => 5, 'page' => $this->request->data['page']));
        $count = 0;
        if ($pitches) {
            $count = count(Pitch::all(array('conditions' => array('status' => 0, 'published' => 1, 'multiwinner' => 0), 'order' => array('started' => 'desc'), 'limit' => 5, 'page' => $this->request->data['page'] + 1)));
        }
        return compact('pitches', 'count');
    }

    public function news() {
        $news = News::getNews(0, $this->request->data['page']);
        $count = count(News::getNews(0, $this->request->data['page'] + 1));
        return compact('news', 'count');
    }

    public function liked() {
        if ($this->request->id) {


            if(!$likes = Event::all(array('conditions' => array('type' => 'LikeAdded', 'solution_id' => $this->request->id), 'order' => array('Event.created' => 'desc')))) {

            }else {
                $likes = Event::all(array('conditions' => array('type' => 'LikeAdded', 'news_id' => $this->request->id), 'order' => array('Event.created' => 'desc')));
            }
            $temp = array();
            foreach ($likes as $like) {
                $temp[] = $like->user->id;
            }
            $fav = \app\models\Favourite::all(array('conditions' => array('pitch_id' => 0, 'fav_user_id' => $temp)));
        }
        return compact('likes', 'fav');
    }

    public function newstags() {
        if (isset($this->request->query['name']) && strlen($this->request->query['name']) > 0) {
            $tags = News::all(array('fields' => array('tags'), 'conditions' => array('tags' => array('LIKE' => array('%' . $this->request->query['name'] . '%')))));
            return json_encode($tags->data());
        }
    }

    public function add() {
        $result = false;
        if ($this->request->data) {
            $result = News::saveNewsByAdmin($this->request->data);
        }
        return compact('result');
    }

    public function getaccesstoken() {
        return Event::getBingAccessToken();
    }

}

?>