<?php

namespace app\extensions\command;

use \app\models\Task;

class ClearTask extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the ClearTask command!');
        //if ($count = Sendemail::clearOldSpamSimple()) {
        //    $this->out($count . ' old messages have been deleted successfully.');
        //}
            $this->out('End of task.');
        //}
    }
}