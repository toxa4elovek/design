<?php

namespace app\controllers;

use \app\models\Pitch;
use \app\models\Request;
use \lithium\storage\Session;

class RequestsController extends \app\controllers\AppController {

    public function sign() {
        if($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) {
            if($pitch->private == 1) {
                return compact('pitch');
            }else {
                return $this->redirect('/pitches/view/' . $this->request->id);
            }
        }
    }

    public function create() {
        if(($this->request->data['first_name'] == '') || ($this->request->data['last_name'] == '') || (!isset($this->request->data['tos']))) {
            return $this->redirect('/requests/sign/' . $this->request->data['pitch_id']);
        }
        $personalData = array(
            'first_name' => $this->request->data['first_name'],
            'last_name' => $this->request->data['last_name']
        );
        $data = array(
            'pitch_id' => $this->request->data['pitch_id'],
            'user_id' => Session::read('user.id'),
            'data' => serialize($personalData),
            'created' => date('Y-m-d H:i:s'),
            'active' => 1
        );
        $request = Request::create();
        $request->set($data);



        if($request->save()) {
            return $this->redirect('/pitches/details/' . $this->request->data['pitch_id']);
        }else{
            return $this->redirect('/requests/sign/' . $this->request->data['pitch_id']);
        }
    }

}

?>