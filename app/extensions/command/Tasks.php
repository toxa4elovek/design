<?php

namespace app\extensions\command;

use \app\models\Task;
use \app\models\Pitch;
use \app\models\User;

class Tasks extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the Task Command');
        set_time_limit(0);
        $task = Task::first(array('conditions' => array('completed' => 0)));
        if($task) {
            if('newpitch' == $task->type) {
                Tasks::__newptich($task);
            }
            $this->out('Task completed');
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

}

?>