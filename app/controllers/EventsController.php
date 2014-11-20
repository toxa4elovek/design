<?php

namespace app\controllers;

use \app\models\User;
use \app\models\Event;
use \app\models\Pitch;
use app\models\News;
use \lithium\storage\Session;
use \app\extensions\helper\Stream;

class EventsController extends \app\controllers\AppController {

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

    public function feed() {
        if (isset($this->request->query['page'])) {
            $updates = Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), $this->request->query['page']);
            $nextUpdates = count(Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), $this->request->query['page'] + 1, null));
        }
        if (!isset($this->request->query['page'])) {
            $this->request->query['page'] = 1;
        }
        if (!empty($this->request->query['pitchDate'])) {
            $pitches = Pitch::all(array('fields' => array('title', 'price', 'started'), 'conditions' => array('status' => 0, 'published' => 1, 'multiwinner' => 0, 'started' => array('>' => $this->request->query['pitchDate'])), 'order' => array('started' => 'desc'), 'limit' => 5));
        }
        if (!empty($this->request->query['created'])) {
            $updates = Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), $this->request->query['page'], $this->request->query['created']);
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
        return compact('updates', 'count', 'nextUpdates', 'post', 'news', 'twitter', 'pitches', 'solutions');
    }

    public function getsol() {
        $solpages = Event::all(array('conditions' => array('type' => 'SolutionAdded', 'private' => 0, 'category_id' => array('!=' => 7), 'multiwinner' => 0), 'order' => array('Event.created' => 'desc'), 'limit' => 10, 'page' => $this->request->query['page'], 'with' => array('Pitch')));
        return compact('solpages');
    }
    
    public function job() {
        $job = \app\models\Tweet::all(array('limit' => 10, 'page' => $this->request->data['page']));
        return compact('job');
    }

}

?>