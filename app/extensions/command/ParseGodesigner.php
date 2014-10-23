<?php

namespace app\extensions\command;

use \app\models\Post;
use \app\models\News;

class ParseGodesigner extends \app\extensions\command\CronJob {

    public function run() {
        $posts = Post::all(array('conditions' => array('published' => 1, 'created' => array('<=' => date('Y-m-d H:i:s'))), 'limit' => 5, 'order' => array('created' => 'desc')));
        $news = News::all();
        foreach ($posts as $post) {
            $trigger = false;
            foreach ($news as $n) {
                if ((string) $post->title === (string) $n->title) {
                    $trigger = true;
                }
            }
            if (!$trigger) {
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $post->full, $matches);
                News::create(array(
                    'title' => $post->title,
                    'tags' => $post->tags,
                    'created' => $post->created,
                    'link' => 'http://www.godesigner.ru/posts/view/' . $post->id,
                    'imageurl' => $post->imageurl
                ))->save();
            }
        }
    }

}
