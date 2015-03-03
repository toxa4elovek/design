<?php

namespace app\extensions\command;

use app\extensions\mailers\CommentsMailer;
use \app\models\Task;
use \app\models\Pitch;
use \app\models\User;
use app\extensions\mailers\SolutionsMailer;

class Tasks extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the Task Command');
        set_time_limit(0);
        $tasks = Task::all(array('conditions' => array(
            'completed' => 0,
            'type' => 'newpitch',
        )));
        $count = count($tasks);
        foreach($tasks as $task) {
            $methodName = '__' . $task->type;
            if(method_exists('app\extensions\command\Tasks', $methodName)) {
                $task->markAsCompleted();
                Tasks::$methodName($task);
            }
        }
        if($count) {
            $this->out($count . ' tasks completed');
        }else {
            $this->out('No tasks are in due.');
        }
    }

    private function __newpitch($task) {
        $pitch = Pitch::first($task->model_id);
        $params = array('pitch' => $pitch);
        User::sendSpamNewPitch($params);
        $this->out('New pitch email has been sent');
    }

}