<?php

namespace app\extensions\command;

use \app\models\Task;

class ClearTasks extends \app\extensions\command\CronJob
{

    public function run()
    {
        $this->header('Welcome to the ClearTask command!');
        $count = Task::deleteCompleted();
        $this->out('Total tasks deleted - ' . $count);
        $this->out('Task completed');
    }
}
