<?php

namespace app\extensions\command;

use app\extensions\mailers\NotificationsMailer;
use app\models\Pitch;

class SendStartPenaltyNotification extends CronJob
{

    /**
     * Команда отправляет уведомление абонентам, у которых в ближайший прошедший час начался штрафной период
     */
    public function run()
    {
        $this->_renderHeader();
        $arrayOfProjects = [];
        $finishDateDeltaStart = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() - HOUR));
        $finishDateDeltaEnd = new \DateTime(date(MYSQL_DATETIME_FORMAT, time()));
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
            NotificationsMailer::sendStartPenaltyNotification($project);
        });

        $this->_renderFooter(sprintf('%d email sent', count($arrayOfProjects)));
    }
}
