<?php

namespace app\extensions\command;

use app\models\Event;

class FacebookSharer extends \app\extensions\command\CronJob
{

    public function run()
    {
        $this->out(date('Y-m-d H:i', time() - HOUR));
        $events = Event::all([
            'conditions' => [
                'Event.type' => ['newsAdded'],
                'created' => ['>' => date('Y-m-d H:i', time() - 5 * HOUR)]
            ],
            'order' => ['created' => 'desc'],
            'limit' => 100]
        );
        foreach ($events as $event) {
            $this->out($event->created);
            $id = 'https://godesigner.ru/news?event=' . $event->id;
            $this->out($id);
            $url = 'https://graph.facebook.com';
            $data = ['id' => $id, 'scrape' => 'true'];
            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                ],
            ];
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
        }
    }
}
