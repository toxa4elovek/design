<?php

namespace app\extensions\command;

use app\models\Pitch;

class SubscribersProjectsTimeout extends CronJob
{

    public function run()
    {
        $this->_renderHeader();
        $currentDate = new \DateTime();
        $subscribersProjects = Pitch::all(['conditions' => [
            'status' => 1,
            'category_id' => 20,
            'awarded' => 0,
            'chooseWinnerFinishDate' => ['<' => $currentDate->format('Y-m-d H:i:s')],
            'published' => 1
        ]]);
        $count = 0;
        foreach ($subscribersProjects as $project) {
            Pitch::markAsRefunded($project->id);
            $this->out("Project found #$project->id, marking as refunded.");
            $count++;
        }
        $this->_renderFooter("$count projects refunded");
    }
}
