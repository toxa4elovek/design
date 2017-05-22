<?php

namespace app\extensions\command;

use app\extensions\mailers\SpamMailer;
use app\models\Pitch;
use app\models\Solution;
use app\models\User;

class Send1on1Email extends CronJob
{

    public function run()
    {
        $this->header('Welcome to the PostAutoWarningsForClosing command!');
        $firstDate = date(MYSQL_DATETIME_FORMAT, time() - 3 * DAY);
        $secondDate = date(MYSQL_DATETIME_FORMAT, time() - 4 * DAY);
        $projects = Pitch::all(['conditions' => [
            'Pitch.published' => 1,
            'Pitch.billed' => 1,
            'Pitch.status' => 2,
            'Pitch.awarded' => ['!=' => 0],
            "Pitch.totalFinishDate <= '$firstDate' AND Pitch.totalFinishDate > '$secondDate'"
        ], 'with' => ['User']]);
        foreach ($projects as $project) {
            $winningSolution = Solution::first($project->awarded);
            $designer = User::first($winningSolution->user_id);
            $client = $project->user;
            $data = [
                'designerId' => $designer->id,
                'user' => $client
            ];
            SpamMailer::send1on1email($data);
        }
        $this->out('Command finished job.');
    }
}
