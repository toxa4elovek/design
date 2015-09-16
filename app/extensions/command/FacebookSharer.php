<?php

namespace app\extensions\command;

use app\models\Event;

class FacebookSharer extends \app\extensions\command\CronJob {

    public function run() {
        $this->out(date('Y-m-d H:i', time() - HOUR));
        $events = Event::all(array(
            'conditions' => array(
                'Event.type' => array('newsAdded'),
                'created' => array('>' => date('Y-m-d H:i', time() - 5 * HOUR))
            ),
            'order' => array('created' => 'desc'),
            'limit' => 100)
        );
        foreach ($events as $event) {
            $this->out($event->created);
            $id = 'http://www.godesigner.ru/news?event=' . $event->id;
            $this->out($id);
            $url = 'https://graph.facebook.com';
            $data = array('id' => $id, 'scrape' => 'true');
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                ),
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
        }

    }

}
