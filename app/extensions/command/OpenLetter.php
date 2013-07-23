<?php

namespace app\extensions\command;

use \app\models\Pitch;

class OpenLetter extends \lithium\console\Command {

    public function run() {
        $this->header('Welcome to the OpenLetter command!');
        $count = Pitch::openLetter();
        $messages = ($count == 1) ? ' message ' : ' messages ';
        $this->out($count . $messages . 'sent.');
    }
}