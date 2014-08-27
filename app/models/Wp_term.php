<?php

namespace app\models;

class Wp_term extends \app\models\AppModel {

    public $_meta = array('connection' => 'tutdesign');

    /* public $hasMany = array(
        'Wp_term_relationship' => array(
            'key' => array(
                'term_id' => 'term_taxonomy_id',
            ),
        ),
    ); */

    protected $_schema = array(
        'term_id' => array(
            'type' => 'id',
        ),
        'name' => array(
            'type' => 'string',
            'length' => 200,
            'null' => false,
            'default' => '',
        ),
        'slug' => array(
            'type' => 'string',
            'length' => 200,
            'null' => false,
            'default' => '',
        ),
        'term_group' => array(
            'type' => 'integer',
            'length' => 10,
            'null' => false,
            'default' => '0',
        ),
    );
}
