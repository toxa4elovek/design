<?php

namespace app\controllers;

use \app\models\Test;

class TestsController extends \app\controllers\AppController
{

    public $publicActions = ['activate'];

    /**
     *  Метод для активации результата теста
     */
    public function activate()
    {
        $result = Test::activate($this->request->id);
        return compact($result);
    }
}
