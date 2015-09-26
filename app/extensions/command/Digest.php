<?php

namespace app\extensions\command;

use \app\models\User;

class Digest extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the Digest command!');
        $count = User::sendDailyDigest();
        $this->out('emails has been set to ' . $count . 'users');
    }
}

?>