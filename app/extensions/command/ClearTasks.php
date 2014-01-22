<?php

namespace app\extensions\command;

use \app\models\Task;

class ClearTasks extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the ClearTask command!');
        $tasks = Task::getCompletedTasks(2);
        $this->out('Total tasks fetched for deletion - ' . count($tasks));
        foreach($tasks as $task) {
            $task->delete();
        }
        $this->out('Task completed');
    }
}