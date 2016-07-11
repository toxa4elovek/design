<?php

namespace app\extensions\command;

use app\extensions\mailers\NotificationsMailer;
use app\models\Pitch;

class SendPenaltyEndsSoonReminder extends CronJob
{

    /**
     * Команда отправляет уведомление заказчикам, у которых в периоде через +12-+13 часов начнётся штрафной период
     */
    public function run()
    {
        $this->_renderHeader();
        $arrayOfProjects = [];
        $finishDateDeltaStart = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() + 12 * HOUR));
        $finishDateDeltaEnd = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() + 13 * HOUR));
        $projects = Pitch::all([
            'conditions' => [
                'status' => 1,
                'awarded' => 0,
                'published' => 1,
                'category_id' => ['!=' => 20],
                'AND' => [
                    [sprintf("finishDate >=  '%s'", $finishDateDeltaStart->format(MYSQL_DATETIME_FORMAT))],
                    [sprintf("finishDate <=  '%s'", $finishDateDeltaEnd->format(MYSQL_DATETIME_FORMAT))],
                ],
            ]
        ]);
        foreach ($projects as $project) {
            $arrayOfProjects[] = $project;
        }

        array_walk($arrayOfProjects, function ($project) {
            NotificationsMailer::sendPenaltyEndsSoonReminder($project);
        });

        $this->_renderFooter(sprintf('%d email sent', count($arrayOfProjects)));
    }
}
