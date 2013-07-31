<?php

namespace app\controllers;

use \app\models\User;
use \app\models\Event;
use \lithium\storage\Session;

class EventsController extends \app\controllers\AppController {

    public function updates() {
        if(!isset($this->request->query['page'])) {
            $this->request->query['page'] = 1;
        }
        if(!isset($this->request->query['created'])) {
            $this->request->query['created'] = null;
        }
        $updates = Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), $this->request->query['page'], $this->request->query['created']);
        $count = count($updates);
        return compact('updates', 'count');
    }

    public function sidebar() {
        $limit = null;
        if(!isset($this->request->query['created'])) {
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