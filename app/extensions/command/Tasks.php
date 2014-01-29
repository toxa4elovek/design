<?php

namespace app\extensions\command;

use \app\models\Task;
use \app\models\Pitch;
use \app\models\User;
use app\extensions\mailers\SolutionsMailer;

class Tasks extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the Task Command');
        set_time_limit(0);
        $tasks = Task::all(array('conditions' => array('completed' => 0)));
        $count = count($tasks);
        foreach($tasks as $task) {
            if('newpitch' == $task->type) {
                Tasks::__newptich($task);
            }
            if('newSolutionNotification' == $task->type) {
                Tasks::__newSolutionNotification($task);
            }
        }
        if($count) {
            $this->out($count . ' tasks completed');
        }else {
            $this->out('No tasks are in due.');
        }
    }

    private function __newptich($task) {
        $pitch = Pitch::first($task->model_id);
        $params = array('pitch' => $pitch);
        $task->markAsCompleted();
        User::sendSpamNewPitch($params);
        $this->out('New pitch email has been sent');
    }

    private function __newSolutionNotification($task) {
        $task->markAsCompleted();
        if($result = SolutionsMailer::sendNewSolutionNotification($task->model_id)) {
            $this->out('New Solution Notification sent');
        }else {
            $this->out('Error (or receiver disabled this notification) sending notification for solution ' . $task->model_id);
        }
    }

}

?>