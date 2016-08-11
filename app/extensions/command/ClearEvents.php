<?php

namespace app\extensions\command;

use app\models\Event;
use app\extensions\storage\Rcache;

class ClearEvents extends CronJob
{

    public function run()
    {
        Rcache::init();
        $events = Event::all(['order' => ['created' => 'desc'], 'limit' => 100]);
        foreach ($events as $event) {
            if ($event->type === 'SolutionAdded' && !$event->solution) {
                $event->delete();
            } elseif ($event->type === 'CommentAdded' && !$event->comment) {
                $event->delete();
            } elseif ($event->type === 'LikeAdded' && !$event->solution && $event->news_id == 0) {
                $event->delete();
            }
        }
        $old_events = Event::all(
            [
                'conditions' =>
                    [
                        'Event.type' => ['!=' => 'newsAdded'],
                        'Event.created' => ['<' => date(MYSQL_DATETIME_FORMAT, strtotime('-1 month', time()))]
                    ],
                'limit' => 100
            ]
        );
        if ($old_events) {
            $old_events->delete();
        }
    }
}
