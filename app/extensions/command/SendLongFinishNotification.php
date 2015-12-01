<?php

namespace app\extensions\command;

use \app\models\Pitch;
use app\extensions\mailers\NotificationsMailer;

class SendLongFinishNotification extends CronJob {

    public function run() {
        $this->header('Welcome to the SendLongFinishNotification command!');
        $firstDate = date('Y-m-d H:i:s', time() - 14 * DAY);
        $secondDate = date('Y-m-d H:i:s', time() - 15 * DAY);
        $projects = Pitch::all(array('conditions' => array(
            'published' => 1,
            'billed' => 1,
            'status' => 1,
            'awarded' => array('!=' => 0),
            "awardedDate <= '$firstDate' AND awardedDate > '$secondDate'"
        )));
        foreach($projects as $project) {
            $user = User::first(32);
            NotificationsMailer::sendLongFinishNotification($user, $project);
        }
    }
}