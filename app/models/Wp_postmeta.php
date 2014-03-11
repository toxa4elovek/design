<?php

namespace app\models;

class Wp_postmeta extends \app\models\AppModel {

    public $_meta = array(
        'connection' => 'tutdesign',
        'source' => 'wp_postmeta',
    );

    protected $_schema = array(
        'meta_id' => array(
            'type' => 'id',
        ),
        'post_id' => array(
            'type' => 'integer',
            'length' => 20,
            'null' => false,
            'default' => '0',
        ),
        'meta_key' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false,
            'default' => NULL,
        ),
        'meta_value' => array(
            'type' => 'text',
            'null' => false,
            'default' => NULL,
        ),
    );
}
