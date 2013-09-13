<?php

namespace app\controllers;

use \app\models\Bill;
use \app\models\Pitch;
use \lithium\storage\Session;
use app\models\User;
use lithium\analysis\Logger;

class BillsController extends \app\controllers\AppController {

    public function save() {
        $res = array(
            'error' => false,
        );
        if (!$this->request->is('json')
         || !($currentUser = Session::read('user.id'))
         || !isset($this->request->data['id'])
         || empty($this->request->data['id'])
         || !($pitch = Pitch::first($this->request->data['id']))) {
            return $this->redirect('/pitches');
        }

        if ($pitch->user_id != $currentUser) {
            $res['error'] = 'wrongUser';
            return $res;
        }

        if (!($bill = Bill::first($pitch->id))) {
            $bill = Bill::create();
        }
            $bill->id = $pitch->id;
            $bill->user_id = $currentUser;
            $bill->set($this->request->data);
            $bill->save();
            $res['result'] = $bill->data();
            return $res;
    }
}
