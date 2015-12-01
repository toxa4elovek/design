<?php

namespace app\controllers;

use \app\models\Receipt;
use \app\models\Pitch;

/**
 * Class ReceiptsController
 *
 * Контроллер для удаленной работы с чеками
 * @package app\controllers
 */
class ReceiptsController extends AppController {

	/**
	 * @var array публичные методы
	 */
    public $publicActions = array(
        'view'
    );

	/**
	 * @return mixed просмотр чека для проекта
	 */
	public function view() {
		if($pitch = Pitch::first($this->request->id)) {
			$receipt = Receipt::all(array('conditions' => array('pitch_id' => $this->request->id)));
			return $receipt->data();
		}
	}

}