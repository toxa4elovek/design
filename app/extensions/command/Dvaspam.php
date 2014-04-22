<?php

namespace app\extensions\command;

use \app\models\User;

class Dvaspam extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the Dvaspam command!');
        $count = User::sendDvaSpam();
        $this->out('emails has been set to ' . $count . ' users');
    }
}

?>