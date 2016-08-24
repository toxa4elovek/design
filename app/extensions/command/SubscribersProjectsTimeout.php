<?php

namespace app\extensions\command;

use app\extensions\helper\User;
use app\models\Pitch;

class SubscribersProjectsTimeout extends CronJob
{

    public function run()
    {
        $this->_renderHeader();
        $currentDate = new \DateTime();
        $projectHelper = new \app\extensions\helper\Pitch();
        $userHelper = new User();
        $subscribersProjects = Pitch::all(['conditions' => [
            'status' => 1,
            'category_id' => 20,
            'awarded' => 0,
            'chooseWinnerFinishDate' => ['<' => $currentDate->format('Y-m-d H:i:s')],
            'published' => 1
        ]]);
        $count = 0;
        foreach ($subscribersProjects as $project) {
            if ($project->expert == 0) {
                Pitch::markAsRefunded($project->id);
                $this->out("Project found #$project->id, marking as refunded.");
                $count++;
            } else {
                $this->out("Project found #$project->id, checking if waiting for expert...");
                if (!$projectHelper->isWaitingForExperts($project)) {
                    $this->out("Project #$project->id is not waiting for experts anymore...");
                    $expertOpinionDate = $projectHelper->expertOpinion($project->id);
                    $client = \app\models\User::first($project->user_id);
                    if ((int) $client->subscription_status === 1) {
                        $delayedChooseWinnerTime = $expertOpinionDate + (3 * DAY);
                    } elseif ((int) $client->subscription_status === 4) {
                        $delayedChooseWinnerTime = $expertOpinionDate + (7 * DAY);
                    } else {
                        $delayedChooseWinnerTime = $expertOpinionDate + strtotime($project->chooseWinnerFinishDate) - strtotime($project->finishDate);
                    }
                    if ($delayedChooseWinnerTime < time()) {
                        $this->out("Time to close project #$project->id");
                        Pitch::markAsRefunded($project->id);
                        $count++;
                    }
                }
            }
        }
        $this->_renderFooter("$count projects refunded");
    }
}
