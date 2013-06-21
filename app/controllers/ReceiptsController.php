<?php

namespace app\controllers;

use \app\models\Receipt;
use \app\models\Pitch;

class ReceiptsController extends AppController {

    public $publicActions = array(
        'view'
    );
	
	public function view() {
		if($pitch = Pitch::first($this->request->id)) {
			$receipt = Receipt::all(array('conditions' => array('pitch_id' => $this->request->id)));
			return $receipt->data();
		}
	}

}