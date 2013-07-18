<?php

namespace app\extensions\command;

use \app\models\Pitch;

class Dailypitch extends \lithium\console\Command {

    public function run() {
        $this->header('Welcome to the Dailypitch command!');
        $count = Pitch::dailypitch();
        $this->out('emails has been set to ' . $count . 'users');
    }
}

?>