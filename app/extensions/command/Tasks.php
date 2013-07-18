<?php

namespace app\extensions\command;

use \app\models\Task;
use \app\models\Pitch;
use \app\models\User;

class Tasks extends \lithium\console\Command {

    public function run() {
        $this->header('Welcome to the Task Command');
        set_time_limit(0);
        $tasks = Task::all(array('conditions' => array('completed' => 0)));
        foreach($tasks as $task) {
            if('newpitch' == $task->type) {
                Tasks::__newptich($task);
            }
        }
        if(count($tasks > 0)) {
            $this->out('All tasks are completed');
        }else {
            $this->out('No tasks are in due.');
        }
    }

    private function __newptich($task) {
        $pitch = Pitch::first($task->model_id);
        $params = array('pitch' => $pitch);
        $task->completed = 1;
        $task->save();
        User::sendSpamNewPitch($params);

        $this->out('New pitch email has been sent');
    }

}

?>