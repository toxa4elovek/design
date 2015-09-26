<?php

namespace app\controllers;

use \lithium\storage\Session;
use app\models\News;
use app\models\User;
use app\models\Event;

class NewsController extends \app\controllers\AppController {

    public function like() {
        $likes = News::increaseLike($this->request->id, Session::read('user.id'));
        $result = $likes['result'];
        $likes = $likes['likes'];
        return compact('likes', 'result');
    }

    public function unlike() {
        $likes = News::decreaseLike($this->request->id, Session::read('user.id'));
        $result = $likes['result'];
        $likes = $likes['likes'];
        return compact('likes', 'result');
    }

    public function hide() {
        $result = false;
        if((Session::read('user.isAdmin') == 1 || (in_array(Session::read('user.id'), User::$admins)))) {
            $result = News::hideNews($this->request->id);
        }
        return compact('result');
    }

}
