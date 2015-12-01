<?php

namespace app\controllers;

use \app\models\Pitchrating;

class RatingController extends AppController {

    public function save() {
	    if (!$this->request->is('json') || !($this->userHelper->getId())
         || !isset($this->request->data['id'])
         || empty($this->request->data['id']) || !isset($this->request->data['rating']) 
         || empty($this->request->data['rating'])) {
            return 'false';
        }

        if (Pitchrating::setRating($this->userHelper->getId(), $this->request->data['id'], $this->request->data['rating'])) {
            return 'true';
        } else {
            return 'false';
        }
    }
    
    public function takePart() {
        if ($this->request->is('json') || isset($this->request->data['id'])) {
            if (Pitchrating::takePart($this->userHelper->getId(), $this->request->data['id'])) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }

}