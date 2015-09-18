<?php

namespace app\models;

class Request extends AppModel {

    public $validates = array(
        'first_name' => array(
            array('notEmpty', 'message' => 'Имя обязательно'),
        ),
        'last_name' => array(
            array('notEmpty', 'message' => 'Фамилия обязетальна'),
        ),
    );

}