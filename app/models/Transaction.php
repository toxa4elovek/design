<?php

namespace app\models;

class Transaction extends AppModel {

    public $hasMany = array(
        'Addon' => array(
            'key' => array(
                'ORDER' => 'id',
            ),
        ),
    );

}