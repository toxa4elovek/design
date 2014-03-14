<?php

namespace app\models;

use \app\models\Wp_postmeta;
use \app\models\Wp_term;

class Wp_post extends \app\models\AppModel {

    public $_meta = array('connection' => 'tutdesign');

    public $hasMany = array(
        'Wp_term_relationship' => array('key' => array('ID' => 'object_id')),
        'Wp_postmeta' => array('key' => array('ID' => 'post_id')),
    );

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

    public static function __init() {
        parent::__init();

        self::applyFilter('find', function($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if (is_object($result)) {
                $addCategory = function($record) {
                    $record->category = 'art';
                    if (isset($record->wp_term_relationships[0]->term_taxonomy_id)) {
                        $category = Wp_term::first(array(
                            'fields' => array('term_id', 'slug'),
                            'conditions' => array('term_id' => $record->wp_term_relationships[0]->term_taxonomy_id)));
                        $record->category = $category->slug;
                    }
                    if (isset($record->wp_postmeta[0]->meta_value)) {
                        $thumbnail = Wp_postmeta::first(array(
                            'conditions' => array(
                                'post_id' => $record->wp_postmeta[0]->meta_value,
                                'meta_key' => '_wp_attachment_metadata',
                            )));
                        $record->thumbnail = Wp_post::extractThumbnail($thumbnail->meta_value, $record->wp_postmeta[0]->meta_value);
                    }
                    return $record;
                };

                if (get_class($result) == 'lithium\data\entity\Record') {
                    $result = $addCategory($result);
                } else {
                    foreach ($result as $foundItem) {
                        $foundItem = $addCategory($foundItem);
                    }
                }
            }
            return $result;
        });
    }

    public static function getPostsForStream($timestamp) {
        $time = date('Y-m-d H:i:s', $timestamp);
        $posts = self::all(array(
            'conditions' => array(
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_modified' => array(
                    '>=' => $time,
                ),
                'Wp_postmeta.meta_key' => '_thumbnail_id',
            ),
            'with' => array('Wp_term_relationship', 'Wp_postmeta')
        ));
        return $posts;
    }

    public static function extractThumbnail($data, $id) {
        $data = unserialize($data);
        if (!empty($data['sizes'])) {
            $path = preg_replace('#[^/]*$#', '', $data['file']);
            if (isset($data['sizes']['tut_small']['file'])) {
                $file = $path . $data['sizes']['tut_small']['file'];
            } else {
                $file = $path . $data['sizes']['thumbnail']['file'];
            }
        } else {
            $thumbnail = Wp_postmeta::first(array(
                'conditions' => array(
                    'post_id' => $id,
                    'meta_key' => '_wp_attached_file',
                ),
            ));
            $file = $thumbnail->meta_value;
        }
        return $file;
    }
}
