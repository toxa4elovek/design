<?php

namespace app\controllers;

use \lithium\storage\Session;
use app\models\News;

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

}
