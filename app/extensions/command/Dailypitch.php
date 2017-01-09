<?php

namespace app\extensions\command;

use \app\models\Pitch;

class Dailypitch extends \app\extensions\command\CronJob
{

    public function run()
    {
        $this->header('Welcome to the Dailypitch command!');
        $count = Pitch::dailypitch();
        $this->out('emails has been sent to ' . $count . ' users');
    }
}
