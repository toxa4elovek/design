<?php

namespace app\models;

use app\extensions\storage\Rcache;

class News extends \app\models\AppModel {

    private static $news;

    public static function getPost($newsDate = 0) {
        $post = 0;
        if ($newsDate < 1) {
            $post = Rcache::read('middle-post');
            self::$news = self::all(array('conditions' => array('created' => array('>' => $newsDate), 'toggle' => 0), 'order' => array('created' => 'desc')));
            if (self::$news && !$post) {
                $all_views = 0;
                foreach (self::$news as $n) {
                    if ($n->middle) {
                        $post = $n;
                        break;
                    }
                    $all_views += $n->views;
                }
                if (!$post) {
                    $av_views = round($all_views / count(self::$news));
                    $max = 0;
                    foreach (self::$news as $n) {
                        if (($n->views > $av_views * 2 && $max < $n->views) || $max < $n->views) {
                            $max = $n->views;
                            $host = parse_url($n->link);
                            $n->host = $host['host'];
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
        return self::all(array('conditions' => array('created' => array('>' => $newsDate), 'toggle' => 0), 'limit' => 25, 'page' => $page, 'order' => array('created' => 'desc')));
    }

}
