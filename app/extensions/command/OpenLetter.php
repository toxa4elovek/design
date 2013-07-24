<?php

namespace app\extensions\command;

use \app\models\Pitch;

class OpenLetter extends \lithium\console\Command {

    public function run() {
        $this->header('Welcome to the OpenLetter command!');
        $count = Pitch::openLetter();
        $messages = ($count['sent'] == 1) ? ' message ' : ' messages ';
        $have = ($count['sent'] == 1) ? ' has ' : ' have ';
        $this->out($count['sent'] . $messages . 'of ' . $count['all'] . $have . 'been sent.');
    }
}