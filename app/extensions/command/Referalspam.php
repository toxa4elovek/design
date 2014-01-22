<?php

namespace app\extensions\command;

use \app\models\User;

class Referalspam extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the Referalspam command!');
        $count = User::sendSpamReferal();
        $this->out('emails have been set to ' . $count . ' users');
    }
}

?>