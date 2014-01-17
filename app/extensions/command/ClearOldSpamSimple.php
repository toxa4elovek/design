<?php

namespace app\extensions\command;

use \app\models\Sendemail;

class ClearOldSpamSimple extends \lithium\console\Command {

    public function run() {
        $this->header('Welcome to the ClearOldSpam command!');
        if ($count = Sendemail::clearOldSpamSimple()) {
            $this->out($count . ' old messages have been deleted successfully.');
        } else {
            $this->out('Error occured while deleting.');
        }
    }
}