<?php

namespace app\extensions\command;

use app\extensions\storage\Rcache;
use app\models\Pitch;
use app\models\Solution;
use app\models\User;
use app\models\Wincomment;

class PostAutoWarningsForClosing extends CronJob {

    public function run() {
        $this->header('Welcome to the PostAutoWarningsForClosing command!');
        Rcache::init();
        $this->out('Fetching projects that are closing right now...');
        $projects = Pitch::all(array('conditions' => array(
            'published' => 1,
            'billed' => 1,
            'status' => 1,
            'awarded' => array('!=' => 0)
        )));
        foreach($projects as $project) {
            if(Pitch::isNeededToPostClosingWarning($project->id)) {
                $this->out($project->id);
                $step = Pitch::getCurrentClosingStep($project->id);
                if($step < 2) {
                    $step = 2;
                }
                $data = array(
                'user_id' => 108,
                'created' => date('Y-m-d H:i:s'),
                'solution_id' => $project->awarded,
                'step' => $step,
                'text' => Pitch::getAutoClosingWarningComment($project->id)
                );
                $data = Wincomment::createComment($data);
                $winComment = Wincomment::first($data['id']);
                $solution = Solution::first($project->awarded);
                $recipient = User::first($solution->user_id);
                User::sendSpamWincomment($winComment, $recipient);
                $recipient = User::first($project->user_id);
                User::sendSpamWincomment($winComment, $recipient);
            }
        }
        $this->out('Command finished job.');
    }

}
