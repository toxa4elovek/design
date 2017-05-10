<?php

namespace app\extensions\command;

use app\extensions\mailers\NotificationsMailer;
use app\models\Pitch;

class SendPenaltyActiveReminder extends CronJob
{

    /**
     * Команда отправляет уведомление абонентам, у которых есть активный штрафной период
     */
    public function run()
    {
        $this->_renderHeader();
        $arrayOfProjects = [];
        $projects = Pitch::all([
            'conditions' => [
                'type' => ['!=' => '1on1'],
                'status' => 1,
                'awarded' => 0,
                'published' => 1,
                'category_id' => ['!=' => 20],
            ]
        ]);
        foreach ($projects as $project) {
            $arrayOfProjects[] = $project;
        }
        $helper = new \app\extensions\helper\Pitch();
        $arrayOfProjects = array_filter($arrayOfProjects, function ($project) use ($helper) {
            if ($helper->isWaitingForExperts($project)) {
                return false;
            }
            return ($helper->getPenalty($project) > 0);
        });

        array_walk($arrayOfProjects, function ($project) use ($helper) {
            NotificationsMailer::sendPenaltyActiveReminder($project, $helper->getPenalty($project));
        });

        $this->_renderFooter(sprintf('%d email sent', count($arrayOfProjects)));
    }
}
