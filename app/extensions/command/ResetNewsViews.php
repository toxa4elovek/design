<?php

namespace app\extensions\command;

use \app\models\News;
use \app\models\Event;
use app\extensions\storage\Rcache;

class ResetNewsViews extends \app\extensions\command\CronJob {

    public function run() {
        Rcache::init();
        $news = News::all();
        $post = News::getPost();
        if ($post) {
            $event = Event::first(array('conditions' => array('Event.type' => 'newsAdded', 'news_id' => $post->id, 'created' => $post->created)));
            if ($event) {
                $this->out('Event for this news exists');
                $event->created = $post->created;
                $event->save();
            } else {
                $this->out('Event for this news not exists yet');
                Event::create(array(
                    'created' => date('Y-m-d H:i:s'),
                    'type' => 'newsAdded',
                    'news_id' => $post->id
                ))->save();
            }
        }
        foreach ($news as $n) {
            $n->views = 0;
        }
        $news->save();
        Rcache::delete('middle-post');
    }

}
