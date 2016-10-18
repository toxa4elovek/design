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
        $response = [
            'error' => false,
        ];
        if (!$this->request->is('json')
         || !isset($requestId)
         || empty($requestId)
         || !($project = Pitch::first($requestId))) {
            return $this->redirect('/pitches');
        }
        $currentUser = (int) $this->userHelper->getId();
        if ((int) $project->user_id !== $currentUser) {
            $response['error'] = 'wrongUser';
            return $response;
        }
        if (!($bill = Bill::first($project->id))) {
            $bill = Bill::create();
        }
        $bill->id = $project->id;
        $bill->user_id = $currentUser;
        $bill->set($this->request->data);
        $bill->save();
        $response['result'] = $bill->data();
        return $response;
    }
}
