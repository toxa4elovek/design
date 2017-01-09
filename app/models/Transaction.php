<?php

namespace app\models;

class Transaction extends AppModel
{

    public $hasMany = [
        'Addon' => [
            'key' => [
                'ORDER' => 'id',
            ],
        ],
    ];
}
