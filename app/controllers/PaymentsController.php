<?php

namespace app\controllers;

use \app\models\Pitch;
use app\extensions\storage\Rcache;
use app\extensions\billing\Payture;

class PaymentsController extends \app\controllers\AppController {

    public $publicActions = array('payture_callback');

    /**
     * Обработка платежных уведомлений от Payture
     *
     */
    public function payture_callback() {
        $result = Pitch::activateLogoSalePitch(102936);
        var_dump($result);
        if (!empty($this->request->data)) {
            Logger::write('info', serialize($this->request->data), array('name' => 'payture'));
        }
        //header("HTTP/1.0 200 OK");
        die();
    }

}