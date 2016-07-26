<?php

namespace app\extensions\command;

use app\extensions\mailers\NotificationsMailer;
use app\models\Pitch;

class SubscriberChooseWinnerReminder extends CronJob
{

    /**
     * Комнада отправляет уведомление абонентам, у которых заканчивается приём работ через 24-25 часов
     */
    public function run()
    {
        $this->_renderHeader();
        $arrayOfProjects = [];
        $finishDateDeltaStart = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() + 24 * HOUR));
        $finishDateDeltaEnd = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() + 25 * HOUR));
        $projects = Pitch::all([
            'conditions' => [
                'status' => 1,
                'awarded' => 0,
                'published' => 1,
                'category_id' => 20,
                'AND' => [
                    [sprintf("chooseWinnerFinishDate >=  '%s'", $finishDateDeltaStart->format(MYSQL_DATETIME_FORMAT))],
                    [sprintf("chooseWinnerFinishDate <=  '%s'", $finishDateDeltaEnd->format(MYSQL_DATETIME_FORMAT))],
                ],
            ]
        ]);
        foreach ($projects as $project) {
            $arrayOfProjects[] = $project;
        }

        array_walk($arrayOfProjects, function ($project) {
            NotificationsMailer::sendSubscriberChooseWinnerWarning($project);
        });

        $this->_renderFooter(sprintf('%d sms sent', count($arrayOfProjects)));
    }
}
