<?php

namespace app\models;

class Wp_term extends \app\models\AppModel
{

    public $_meta = ['connection' => 'tutdesign'];

    /* public $hasMany = array(
        'Wp_term_relationship' => array(
            'key' => array(
                'term_id' => 'term_taxonomy_id',
            ),
        ),
    ); */

    protected $_schema = [
        'term_id' => [
            'type' => 'id',
        ],
        'name' => [
            'type' => 'string',
            'length' => 200,
            'null' => false,
            'default' => '',
        ],
        'slug' => [
            'type' => 'string',
            'length' => 200,
            'null' => false,
            'default' => '',
        ],
        'term_group' => [
            'type' => 'integer',
            'length' => 10,
            'null' => false,
            'default' => '0',
        ],
    ];
}
