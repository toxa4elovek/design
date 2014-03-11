<?php

namespace app\models;

class Wp_term_relationship extends \app\models\AppModel {

    public $_meta = array('connection' => 'tutdesign');

    /* public $hasOne = array(
        'Wp_term' => array(
            'key' => array(
                'term_taxonomy_id' => 'term_id',
            ),
        ),
    ); */

    protected $_schema = array(
        'object_id' => array(
            'type' => 'id',
        ),
        'term_taxonomy_id' => array(
            'type' => 'integer',
            'length' => 20,
            'null' => false,
            'default' => '0',
        ),
        'term_order' => array(
            'type' => 'integer',
            'length' => 11,
            'null' => false,
            'default' => '0',
        ),
    );
}
