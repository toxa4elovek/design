<?php

namespace app\extensions\command;

use \app\models\Post;
use \app\models\News;

class ParsingSites extends \app\extensions\command\CronJob {

    public function run() {
        self::ParsingGodesigner();
        self::ParsingTutdesign();
        self::ParsingVozduhAfisha();
        self::ParsingColta();
    }

    private function ParsingGodesigner() {
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

    private function ParsingTutdesign() {
        $xml = simplexml_load_file('http://tutdesign.ru/feed');
        $news = News::all();
        foreach ($xml->channel->item as $item) {
            $trigger = false;
            foreach ($news as $n) {
                if ((string) $item->title === (string) $n->title) {
                    $trigger = true;
                }
            }
            if (!$trigger) {
                $date = new \DateTime($item->pubDate);
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->asXML(), $matches);
                News::create(array(
                    'title' => $item->title,
                    'tags' => $item->category,
                    'created' => $date->format('Y-m-d H:i:s'),
                    'link' => $item->link,
                    'imageurl' => $matches[1]
                ))->save();
            }
        }
    }

    private function ParsingVozduhAfisha() {
        $xml = file_get_contents('http://vozduh.afisha.ru/export/rss/');
        $xml = simplexml_load_string($xml);
        $news = News::all();
        foreach ($xml->channel->item as $item) {
            if (strpos($item->link, '/art/') !== false || strpos($item->link, '/cinema/') !== false) {
                $trigger = false;
                foreach ($news as $n) {
                    if ((string) $item->title === (string) $n->title) {
                        $trigger = true;
                    }
                }
                if (!$trigger) {
                    $content = file_get_contents($item->link);
                    preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content, $matches);
                    preg_match("/<a.*class=\"tag\".*>(.*)<\/a>/", $content, $match);
                    $date = new \DateTime($item->pubDate);
                    News::create(array(
                        'title' => $item->title,
                        'tags' => $match[1],
                        'created' => $date->format('Y-m-d H:i:s'),
                        'link' => $item->link,
                        'imageurl' => $matches[1]
                    ))->save();
                }
            }
        }
    }

    private function ParsingColta() {
        $xml = simplexml_load_file('http://www.colta.ru/feed');
        $news = News::all();
        foreach ($xml->channel->item as $item) {
            if ($item->category != 'Новости' && $item->category != 'Swiss Made') {
                $trigger = false;
                foreach ($news as $n) {
                    if ((string) $item->title === (string) $n->title) {
                        $trigger = true;
                    }
                }
                if (!$trigger) {
                    $content = file_get_contents($item->link);
                    $doc = new \DOMDocument();
                    libxml_use_internal_errors(true);
                    $doc->loadHTML($content);
                    $xml = simplexml_import_dom($doc);
                    libxml_clear_errors();
                    $images = $xml->xpath('//img');
                    $imgurl = '';
                    foreach ($images as $img) {
                        if (strpos($img['src'], '/storage') !== false && !strpos($img['src'], 'preview') && !strpos($img['src'], 'cover')) {
                            $imgurl = $img['src'];
                            break;
                        }
                    }
                    $date = new \DateTime($item->pubDate);
                    if (!empty($imgurl)) {
                        News::create(array(
                            'title' => $item->title,
                            'tags' => $item->category,
                            'created' => $date->format('Y-m-d H:i:s'),
                            'link' => $item->link,
                            'imageurl' => $imgurl
                        ))->save();
                    }
                }
            }
        }
    }

}
