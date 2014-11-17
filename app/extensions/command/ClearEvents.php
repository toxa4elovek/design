<?php

namespace app\extensions\command;

use app\models\Event;
use app\extensions\storage\Rcache;

class ClearEvents extends \app\extensions\command\CronJob {

    public function run() {
        Rcache::init();
        $events = Event::all(array('order' => array('created' => 'desc'), 'limit' => 100));
        foreach ($events as $event) {
            if ($event->type == 'SolutionAdded' && !$event->solution) {
                $event->delete();
            } elseif ($event->type == 'CommentAdded' && !$event->comment) {
                $event->delete();
            } elseif ($event->type == 'LikeAdded' && !$event->solution) {
                $event->delete();
            }
        }
        $old_events = Event::all(array('conditions' => array('type' => array('!=' => 'newsAdded')), 'order' => array('created' => array('<' => date('Y-m-d H:i:s', strtotime('-3 month', time())))), 'limit' => 100));
        if ($old_events) {
            $old_events->delete();
        }
    }

}
