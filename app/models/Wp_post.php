<?php

namespace app\models;

class Wp_post extends \app\models\AppModel {

    public $_meta = array('connection' => 'tutdesign');

    protected $_schema = array(
        'ID'  => array(
            'type' => 'id',
        ),
        'post_author' => array(
            'type' => 'integer',
            'length' => 20,
            'null' => false,
            'default' => '0',
        ),
        'post_date' => array(
            'type' => 'datetime',
            'null' => false,
            'default' => '0000-00-00 00:00:00',
        ),
        'post_date_gmt' => array(
            'type' => 'datetime',
            'null' => false,
            'default' => '0000-00-00 00:00:00',
        ),
        'post_content' => array(
            'type' => 'text',
            'null' => false,
            'default' => NULL,
        ),
        'post_title' => array(
            'type' => 'text',
            'null' => false,
            'default' => NULL,
        ),
        'post_excerpt' => array(
            'type' => 'text',
            'null' => false,
            'default' => NULL,
        ),
        'post_status' => array(
            'type' => 'string',
            'length' => 20,
            'null' => false,
            'default' => 'publish',
        ),
        'comment_status' => array(
            'type' => 'string',
            'length' => 20,
            'null' => false,
            'default' => 'open',
        ),
        'ping_status' => array(
            'type' => 'string',
            'length' => 20,
            'null' => false,
            'default' => 'open',
        ),
        'post_password' => array(
            'type' => 'string',
            'length' => 20,
            'null' => false,
            'default' => '',
        ),
        'post_name' => array(
            'type' => 'string',
            'length' => 200,
            'null' => false,
            'default' => '',
        ),
        'to_ping' => array(
            'type' => 'text',
            'null' => false,
            'default' => NULL,
        ),
        'pinged' => array(
            'type' => 'text',
            'null' => false,
            'default' => NULL,
        ),
        'post_modified' => array(
            'type' => 'datetime',
            'null' => false,
            'default' => '0000-00-00 00:00:00',
        ),
        'post_modified_gmt' => array(
            'type' => 'datetime',
            'null' => false,
            'default' => '0000-00-00 00:00:00',
        ),
        'post_content_filtered' => array(
            'type' => 'text',
            'null' => false,
            'default' => NULL,
        ),
        'post_parent' => array(
            'type' => 'integer',
            'length' => 20,
            'null' => false,
            'default' => '0',
        ),
        'guid' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false,
            'default' => '',
        ),
        'menu_order' => array(
            'type' => 'integer',
            'length' => 11,
            'null' => false,
            'default' => '0',
        ),
        'post_type' => array(
            'type' => 'string',
            'length' => 20,
            'null' => false,
            'default' => 'post',
        ),
        'post_mime_type' => array(
            'type' => 'string',
            'length' => 100,
            'null' => false,
            'default' => '',
            ),
        'comment_count' => array(
            'type' => 'integer',
            'length' => 20,
            'null' => false,
            'default' => '0',
        ),
    );

    public static function getPostsForStream($timestamp) {
        $time = date('Y-m-d H:i:s', $timestamp);
        $posts = self::all(array(
            'conditions' => array(
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_modified' => array(
                    '>=' => $time,
                ),
            ),
        ));
        return $posts;
    }
}