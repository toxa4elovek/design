<?php

namespace app\controllers;

use \app\models\Url;

/**
 * Class UrlsController
 *
 * Класс отвечает за работу с короткими адресами
 *
 * @package app\controllers
 */
class UrlsController extends AppController {

    /**
     * @var array публичные методы контроллера
     */
    public $publicActions = array('view');

    /**
     * Метод перенаправляет пользователя на страницу соответствующую
     * коду $this->request->params['short'] или на главную, если
     * код не действителен
     *
     */
    public function view() {
        if(!$url = Url::get($this->request->params['short'])) {
            $url = '/';
        }
        return $this->redirect($url);
    }

}