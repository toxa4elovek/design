<?php

namespace app\controllers;

use \app\models\Url;

class UrlsController extends \app\controllers\AppController {

    public $publicActions = array('view');

    public function view() {
        $result = Url::get($this->request->params['short']);
        if($result) {
            return $this->redirect($result->full);
        }else{
            return $this->redirect('/');
        }

    }

}

?>