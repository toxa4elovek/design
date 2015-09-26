<?php

namespace app\extensions\command;

use \app\models\Pitch;

class TimeoutPitches extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the Timeout Pitches command!');
        $count = Pitch::timeoutPitches();
        $this->out($count . ' has been set to status 1.');
    }
}

?>