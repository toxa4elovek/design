<?php

namespace app\controllers;

use \app\models\User;
use \app\models\Event;
use \lithium\storage\Session;
use \app\extensions\helper\Stream;

class EventsController extends \app\controllers\AppController {

    public function updates() {
        if (!isset($this->request->query['page'])) {
            $this->request->query['page'] = 1;
        }
        if (!isset($this->request->query['created'])) {
            $this->request->query['created'] = 0;
        }
        $updates = Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), $this->request->query['page'], $this->request->query['created']);
        $nextUpdates = count(Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), $this->request->query['page'] + 1, null));
        $count = count($updates);
        $news = \app\models\News::all(array('conditions' => array('created' => array('>' => $this->request->query['created'])), 'limit' => 8, 'order' => array('created' => 'desc')));
        if ($news) {
            $all_views = 0;
            foreach ($news as $n) {
                $host = parse_url($n->link);
                $all_views += $n->views;
                $n->host = $host['host'];
            }
            $av_views = round($all_views / count($news));
            foreach ($news as $n) {
                if ($n->views > $av_views * 2 && $post->views < $n->views) {
                    $post = $n;
                    $post->created = date('Y-m-d H:i:s', strtotime($post->created));
                }
            }
        }
        $twitter = Stream::renderStream(10,$this->request->query['created']);
        return compact('updates', 'count', 'nextUpdates', 'post', 'news', 'twitter');
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