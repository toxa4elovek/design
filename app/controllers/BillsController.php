<?php

namespace app\controllers;

use \app\models\Bill;
use \app\models\Pitch;
use \lithium\storage\Session;
use app\models\User;
use lithium\analysis\Logger;

class BillsController extends \app\controllers\AppController {

    public function save() {
        if (!$this->request->is('json')
         || !($currentUser = Session::read('user.id'))
         || !isset($this->request->data['id'])
         || empty($this->request->data['id'])
         || !($pitch = Pitch::first($this->request->data['id']))) {
            return $this->redirect('/pitches');
        }

        if ($pitch->user_id != $currentUser) {
            return array('error' => 'wrongUser');
        }

        if (!($bill = Bill::first($pitch->id))) {
            $bill = Bill::create();
        }
            $bill->id = $pitch->id;
            $bill->user_id = $currentUser;
            $bill->set($this->request->data);
            $bill->save();
            return $bill->data();
    }
}
