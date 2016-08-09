<?php

namespace app\controllers;

use \app\models\Bill;
use \app\models\Pitch;
use \lithium\storage\Session;

class BillsController extends AppController
{

    public $publicActions = ['save'];

    public function save()
    {
        $requestId = $this->request->data['id'];
        $res = [
            'error' => false,
        ];
        if (!$this->request->is('json')
         || !isset($requestId)
         || empty($requestId)
         || !($pitch = Pitch::first($requestId))) {
            return $this->redirect('/pitches');
        }
        $currentUser = (int) $this->userHelper->getId();
        if ((int) $pitch->user_id !== $currentUser) {
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
