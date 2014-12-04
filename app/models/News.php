<?php

namespace app\models;

use app\extensions\storage\Rcache;
use \image_manipulation\processor\Upload;

class News extends \app\models\AppModel {

    private static $news;
    protected static $processImage = array(
        'middleFeed' => array(
            'image_resize' => true,
            'image_x' => 600,
            'image_y' => 500,
            'image_ratio_crop' => 'T',
            'file_overwrite' => true
        ),
    );

    public static function getPost($newsDate = 0) {
        $post = 0;
        if ($newsDate < 1) {
            $post = Rcache::read('middle-post');
            self::$news = self::all(array('conditions' => array('created' => array('>' => $newsDate), 'isBanner' => 0, 'toggle' => 0), 'order' => array('created' => 'desc')));
            if (self::$news && !$post) {
                $all_views = 0;
                foreach (self::$news as $n) {
                    if ($n->middle) {
                        $post = $n;
                        $post->short = html_entity_decode($post->short, ENT_QUOTES, 'UTF-8');
                        $post->short = mb_strimwidth($post->short, 0, 250, '...');
                        break;
                    }
                    $all_views += $n->views;
                }
                if (!$post) {
                    $av_views = round($all_views / count(self::$news));
                    $max = 0;
                    foreach (self::$news as $n) {
                        if (strpos($n->link, 'tutdesign')) {
                            continue;
                        }
                        if (($n->views > $av_views * 2 && $max < $n->views) || $max < $n->views) {
                            $max = $n->views;
                            $host = parse_url($n->link);
                            $n->host = $host['host'];
                            $n->short = html_entity_decode($n->short, ENT_QUOTES, 'UTF-8');
                            $n->short = mb_strimwidth($n->short, 0, 250, '...');
                            $str = strpos($n->tags, '|');
                            if ($str) {
                                $n->tags = substr($n->tags, 0, $str);
                            }
                            $post = $n;
                        }
                    }
                }
                Rcache::write('middle-post', $post, '+1 hour');
            }
        }
        return $post;
    }

    public static function getNews($newsDate = 0, $page = 1) {
        return self::all(array('conditions' => array('created' => array('>' => $newsDate), 'toggle' => 0, 'isBanner' => 0, 'link' => array('NOT LIKE' => array('%http://tutdesign.ru/%', '%http://www.godesigner.ru/%'))), 'limit' => 25, 'page' => $page, 'order' => array('created' => 'desc')));
    }

    public static function getBanner() {
        return self::first(array('conditions' => array('isBanner' => 0), 'order' => array('created' => 'desc')));
    }

    public static function resize($file) {
        $options = self::$processImage;
        foreach ($options as $option => $imageParams) {
            $newfiledata = pathinfo($file['name']);
            $newfiledata['filename'] = md5(uniqid('', true));
            $newfiledata['dirname'] = 'events';
            $newfilename = $newfiledata['dirname'] . '/' . $newfiledata['filename'] . '_' . $option . '.' . $newfiledata['extension'];
            $imageProcessor = new Upload();
            $imageProcessor->uploadandinit($file['tmp_name']);
            $imageProcessor->uploaded = true;
            $imageProcessor->no_upload_check = true;
            $imageProcessor->file_src_pathname = $file['tmp_name'];
            $imageProcessor->file_src_name_ext = $newfiledata['extension'];
            $imageProcessor->file_new_name_body = $newfiledata['filename'] . '_' . $option;
            foreach ($imageParams as $param => $value) {
                $imageProcessor->{$param} = $value;
            }
            $imageProcessor->process($newfiledata['dirname']);

            return '/' . $newfilename;
        }

        return true;
    }

}
