<?php

namespace app\models;

class Wp_postmeta extends \app\models\AppModel
{

    public $_meta = [
        'connection' => 'tutdesign',
        'source' => 'wp_postmeta',
    ];

    protected $_schema = [
        'meta_id' => [
            'type' => 'id',
        ],
        'post_id' => [
            'type' => 'integer',
            'length' => 20,
            'null' => false,
            'default' => '0',
        ],
        'meta_key' => [
            'type' => 'string',
            'length' => 255,
            'null' => false,
            'default' => null,
        ],
        'meta_value' => [
            'type' => 'text',
            'null' => false,
            'default' => null,
        ],
    ];
}
