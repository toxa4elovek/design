<?php

namespace app\extensions\command;

use \app\models\Pitch;

class Promospam extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the Promospam command!');
        $count = Pitch::promospam();
        $this->out('emails has been set to ' . $count . 'users');
    }
}

?>