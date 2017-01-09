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
class ReceiptsController extends AppController
{

    /**
     * @var array публичные методы
     */
    public $publicActions = [
        'view'
    ];

    /**
     * @return mixed просмотр чека для проекта
     */
    public function view()
    {
        if ($pitch = Pitch::first($this->request->id)) {
            $receipt = Receipt::all(['conditions' => ['pitch_id' => $this->request->id]]);
            return $receipt->data();
        }
    }
}
