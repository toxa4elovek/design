<?php

namespace app\extensions\command;

use app\extensions\mailers\NotificationsMailer;
use app\models\Pitch;

class SendStartPenaltyNotification extends CronJob
{

    /**
     * Команда отправляет уведомление заказчикам, у которых в прошедший час начался штрафной период
     */
    public function run()
    {
        $this->_renderHeader();
        $arrayOfProjects = [];
        $finishDateDeltaStart = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() - (4 * DAY) - HOUR));
        $finishDateDeltaEnd = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() - (4 * DAY)));
        $projects = Pitch::all([
            'conditions' => [
                'status' => 1,
                'awarded' => 0,
                'published' => 1,
                'category_id' => ['!=' => 20],
                'type' => ['!=' => '1on1'],
                'AND' => [
                    [sprintf("finishDate >=  '%s'", $finishDateDeltaStart->format(MYSQL_DATETIME_FORMAT))],
                    [sprintf("finishDate <=  '%s'", $finishDateDeltaEnd->format(MYSQL_DATETIME_FORMAT))],
                ],
            ]
        ]);
        foreach ($projects as $project) {
            $arrayOfProjects[] = $project;
        }
        $pitchHelper = new \app\extensions\helper\Pitch();
        array_walk($arrayOfProjects, function ($project) use ($pitchHelper) {
            if (((int) $project->expert === 1) && (($allowSelect = $pitchHelper->expertOpinion($project->id)) && ($allowSelect == strtotime($project->finishDate)))) {
            } else {
                NotificationsMailer::sendStartPenaltyNotification($project);
            }
        });

        $this->_renderFooter(sprintf('%d email sent', count($arrayOfProjects)));
    }
}
