<?php

namespace app\extensions\command;

use app\extensions\mailers\NotificationsMailer;
use app\models\Pitch;
use app\models\User;

/**
 * Class SendLongFinishNotification
 * @package app\extensions\command
 *
 * Команда отправляет письмо, если завершения слишком долгое
 *
 */
class SendLongFinishNotification extends CronJob
{

    public function run()
    {
        $this->header('Welcome to the SendLongFinishNotification command!');
        $firstDate = date(MYSQL_DATETIME_FORMAT, time() - 14 * DAY);
        $secondDate = date(MYSQL_DATETIME_FORMAT, time() - 15 * DAY);
        $projects = Pitch::all(['conditions' => [
            'published' => 1,
            'billed' => 1,
            'status' => 1,
            'awarded' => ['!=' => 0],
            "awardedDate <= '$firstDate' AND awardedDate > '$secondDate'"
        ]]);
        foreach ($projects as $project) {
            $user = User::first(47);
            NotificationsMailer::sendLongFinishNotification($user, $project);
        }
    }
}
