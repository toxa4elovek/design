<?php
/**
 * Created by PhpStorm.
 * User: apuc0
 * Date: 01.09.2017
 * Time: 13:45
 */

namespace app\controllers;

class LpController extends AppController
{
    /**
     * @var array Массив экшенов, доступных не залогинненым пользователям
     */
    public $publicActions = ['index', 'logo', 'search'];

    public function _init()
    {
        parent::_init();
        $this->_render['layout'] = 'lp';
    }

    public function index()
    {
        //echo 123;
    }

    public function logo()
    {

    }

}