<?php

namespace app\controllers;

use app\models\Promocode;
use \lithium\storage\Session;

class PromocodesController extends \lithium\action\Controller {

    public $publicActions = array('check');

    public function check() {
        return Promocode::checkPromocode($this->request->data['code']);
    }
}

?>