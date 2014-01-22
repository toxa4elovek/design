<?php

namespace app\extensions\command;

use \app\models\Sendemail;

class ClearOldSpam extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the ClearOldSpam command!');
        if (Sendemail::clearOldSpam()) {
            $this->out('Old messages have been deleted successfully.');
        } else {
            $this->out('Error occured while deleting.');
        }
    }
}