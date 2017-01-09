<?php

namespace app\models;

use \app\models\Wp_postmeta;
use \app\models\Wp_term;

class Wp_post extends \app\models\AppModel
{

    public $_meta = ['connection' => 'tutdesign'];

    public $hasMany = [
        'Wp_term_relationship' => ['key' => ['ID' => 'object_id']],
        'Wp_postmeta' => ['key' => ['ID' => 'post_id']],
    ];

    protected $_schema = [
        'ID'  => [
            'type' => 'id',
        ],
        'post_author' => [
            'type' => 'integer',
            'length' => 20,
            'null' => false,
            'default' => '0',
        ],
        'post_date' => [
            'type' => 'datetime',
            'null' => false,
            'default' => '0000-00-00 00:00:00',
        ],
        'post_date_gmt' => [
            'type' => 'datetime',
            'null' => false,
            'default' => '0000-00-00 00:00:00',
        ],
        'post_content' => [
            'type' => 'text',
            'null' => false,
            'default' => null,
        ],
        'post_title' => [
            'type' => 'text',
            'null' => false,
            'default' => null,
        ],
        'post_excerpt' => [
            'type' => 'text',
            'null' => false,
            'default' => null,
        ],
        'post_status' => [
            'type' => 'string',
            'length' => 20,
            'null' => false,
            'default' => 'publish',
        ],
        'comment_status' => [
            'type' => 'string',
            'length' => 20,
            'null' => false,
            'default' => 'open',
        ],
        'ping_status' => [
            'type' => 'string',
            'length' => 20,
            'null' => false,
            'default' => 'open',
        ],
        'post_password' => [
            'type' => 'string',
            'length' => 20,
            'null' => false,
            'default' => '',
        ],
        'post_name' => [
            'type' => 'string',
            'length' => 200,
            'null' => false,
            'default' => '',
        ],
        'to_ping' => [
            'type' => 'text',
            'null' => false,
            'default' => null,
        ],
        'pinged' => [
            'type' => 'text',
            'null' => false,
            'default' => null,
        ],
        'post_modified' => [
            'type' => 'datetime',
            'null' => false,
            'default' => '0000-00-00 00:00:00',
        ],
        'post_modified_gmt' => [
            'type' => 'datetime',
            'null' => false,
            'default' => '0000-00-00 00:00:00',
        ],
        'post_content_filtered' => [
            'type' => 'text',
            'null' => false,
            'default' => null,
        ],
        'post_parent' => [
            'type' => 'integer',
            'length' => 20,
            'null' => false,
            'default' => '0',
        ],
        'guid' => [
            'type' => 'string',
            'length' => 255,
            'null' => false,
            'default' => '',
        ],
        'menu_order' => [
            'type' => 'integer',
            'length' => 11,
            'null' => false,
            'default' => '0',
        ],
        'post_type' => [
            'type' => 'string',
            'length' => 20,
            'null' => false,
            'default' => 'post',
        ],
        'post_mime_type' => [
            'type' => 'string',
            'length' => 100,
            'null' => false,
            'default' => '',
            ],
        'comment_count' => [
            'type' => 'integer',
            'length' => 20,
            'null' => false,
            'default' => '0',
        ],
    ];

    public static function __init()
    {
        parent::__init();

        self::applyFilter('find', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if (is_object($result)) {
                $addCategory = function ($record) {
                    $record->category = 'art';
                    if (isset($record->wp_term_relationships[0]->term_taxonomy_id)) {
                        $category = Wp_term::first([
                            'fields' => ['term_id', 'slug', 'name'],
                            'conditions' => ['term_id' => $record->wp_term_relationships[0]->term_taxonomy_id]]);
                        $record->category = $category->slug;
                        $record->category_name = $category->name;
                    }
                    if (isset($record->wp_postmeta[0]->meta_value)) {
                        $thumbnail = Wp_postmeta::first([
                            'conditions' => [
                                'post_id' => $record->wp_postmeta[0]->meta_value,
                                'meta_key' => '_wp_attachment_metadata',
                            ]]);
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

    public static function getPostsForStream($timestamp)
    {
        $time = date('Y-m-d H:i:s', $timestamp);
        $posts = self::all([
            'conditions' => [
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_date' => [
                    '>=' => $time,
                ],
                'Wp_postmeta.meta_key' => '_thumbnail_id',
            ],
            'with' => ['Wp_term_relationship', 'Wp_postmeta']
        ]);
        return $posts;
    }

    public static function extractThumbnail($data, $id)
    {
        $data = unserialize($data);
        if (!empty($data['sizes'])) {
            $path = preg_replace('#[^/]*$#', '', $data['file']);
            if (isset($data['sizes']['tut_small']['file'])) {
                $file = $path . $data['sizes']['tut_small']['file'];
            } else {
                $file = $path . $data['sizes']['thumbnail']['file'];
            }
        } else {
            $thumbnail = Wp_postmeta::first([
                'conditions' => [
                    'post_id' => $id,
                    'meta_key' => '_wp_attached_file',
                ],
            ]);
            $file = $thumbnail->meta_value;
        }
        return $file;
    }
}
