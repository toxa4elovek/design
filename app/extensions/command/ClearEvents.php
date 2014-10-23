<?php

namespace app\extensions\command;

use app\models\Event;

class ClearEvents extends \app\extensions\command\CronJob {

    public function run() {
        $events = Event::all(array('order' => array('created' => 'asc'), 'limit' => 100));
        foreach ($events as $event) {
            if ($event->type == 'SolutionAdded' && !$event->solution) {
                $event->delete();
            } elseif ($event->type == 'CommentAdded' && !$event->comment) {
                $event->delete();
            } elseif ($event->type == 'LikeAdded' && !$event->solution) {
                $event->delete();
            }
        }
    }

}
