<?php

namespace app\extensions\command;

use app\models\Event;

class FacebookSharer extends \app\extensions\command\CronJob {

    public function run() {
        $this->out(date('Y-m-d H:i', time()));
        $events = Event::all(array(
            'conditions' => array(
                'type' => array('SolutionAdded', 'newsAdded'),
                'created' => array('>' => date('Y-m-d H:i', time()))
            ),
            'order' => array('created' => 'desc'),
            'limit' => 5)
        );
        foreach ($events as $event) {
            $this->out($event->created);

            if($event->type == 'newsAdded') {
                $id = 'http://www.godesigner.ru/news?event=' . $event->id;
            }elseif($event->type == 'SolutionAdded') {
                $id = 'http://www.godesigner.ru/pitches/viewsolution/' . $event->solution->id;
            }
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
