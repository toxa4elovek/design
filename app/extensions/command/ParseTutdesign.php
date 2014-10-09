<?php

namespace app\extensions\command;

use \app\models\News;

class ParseGodesigner extends \app\extensions\command\CronJob {

    public function run() {
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
                News::create(array(
                    'title' => $item->title,
                    'tags' => $item->category,
                    'created' => $date->format('Y-m-d H:i:s'),
                    'link' => $item->link,
                    'imageurl' => $item->imageurl
                ))->save();
            }
        }
    }

}
