<?php

namespace app\controllers;

use \app\models\Favourite;
use \lithium\storage\Session;

class FavouritesController extends \app\controllers\AppController
{

    public function add()
    {
        $result = Favourite::add(Session::read('user.id'), $this->request->data['pitch_id']);
        return compact('result');
    }

    public function addUser()
    {
        $result = Favourite::addUser(Session::read('user.id'), $this->request->id);
        return compact('result');
    }
    
    public function removeUser()
    {
        $result = Favourite::unfavUser(Session::read('user.id'), $this->request->id);
        return compact('result');
    }
    
    public function remove()
    {
        $result = Favourite::unfav(Session::read('user.id'), $this->request->data['pitch_id']);
        return compact('result');
    }
}
