<?php

namespace app\controllers;

use \app\models\User;
use \app\models\Event;
use \app\models\Pitch;
use \app\models\Solution;
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
        }

        if (!isset($this->request->query['page'])) {
            $this->request->query['page'] = 1;
        }

        if (!empty($this->request->query['created'])) {
            $pitches = Pitch::all(array('fields' => array('title', 'price', 'started'), 'conditions' => array('status' => 0, 'published' => 1, 'multiwinner' => 0, 'started' => array('>' => $this->request->query['created'])), 'order' => array('started' => 'desc'), 'limit' => 5));
            $updates = Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), $this->request->query['page'], $this->request->query['created']);
        } elseif (!isset($this->request->query['created'])) {
            $this->request->query['created'] = 0;
        }
        if (!empty($this->request->query['twitterDate'])) {
            $twitter = Stream::renderStreamFeed(10, $this->request->query['twitterDate']);
        }
        if (!empty($this->request->query['solutionDate'])) {
            $solutions = Event::all(array('conditions' => array('type' => 'SolutionAdded', 'created' => array('>' => $this->request->query['solutionDate'])), 'order' => array('created' => 'desc'), 'limit' => 10));
        }
        if (!empty($this->request->query['newsDate'])) {
            $news = \app\models\News::all(array('conditions' => array('created' => array('>' => $this->request->query['newsDate'])), 'limit' => 8, 'order' => array('created' => 'desc')));
            if ($news) {
                $all_views = 0;
                foreach ($news as $n) {
                    $host = parse_url($n->link);
                    $all_views += $n->views;
                    $n->host = $host['host'];
                }
                $av_views = round($all_views / count($news));
                $max = 0;
                foreach ($news as $n) {
                    if ($n->views > $av_views * 2 && $max < $n->views) {
                        $max = $n->views;
                        $post = $n;
                        $post->created = date('Y-m-d H:i:s', strtotime($post->created));
                    }
                }
            }
        }
        // $solutions = Solution::all((array('fields' => array('likes', 'created', 'id', 'user_id', 'pitch_id', 'first_name','last_name'), 'conditions' => array('multiwinner' => 0, 'Solution.created' => array('>' => $this->request->query['created'])), 'order' => array('created' => 'desc'), 'limit' => 10, 'with' => array('User'))));
        $nextUpdates = count(Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), $this->request->query['page'] + 1, null));
        $count = count($updates);
        return compact('updates', 'count', 'nextUpdates', 'post', 'news', 'twitter', 'pitches', 'solutions');
    }

    public function sidebar() {
        $limit = null;
        if (!isset($this->request->query['created'])) {
            $this->request->query['created'] = false;
            $limit = 10;
        }
        //$updates = Event::getSidebarEvents($this->request->query['created'], $limit);
        $updates = array();
        $count = count($updates);
        return compact('updates', 'count');
    }

}

?>