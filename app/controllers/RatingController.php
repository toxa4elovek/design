<?php

namespace app\controllers;

use \app\models\Pitch;
use \lithium\storage\Session;
use \app\models\User;
use \app\models\Pitchrating;

class RatingController extends \app\controllers\AppController {

    public function save() {
		if (!$this->request->is('json')
         || !(Session::read('user.id'))
         || !isset($this->request->data['id'])
         || empty($this->request->data['id'])
		 || !isset($this->request->data['rating'])
		 || empty($this->request->data['rating'])) {
            return 'false';
        }

		if(Pitchrating::setRating(Session::read('user.id'),$this->request->data['id'],$this->request->data['rating'])) {
			return 'true';
		} else return 'false';
    }
}
