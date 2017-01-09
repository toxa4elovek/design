<?php

namespace app\extensions\command;

use \app\models\Pitch;

class ExpertReminder extends \app\extensions\command\CronJob
{

    public function run()
    {
        $this->header('Welcome to the ExpertReminder command!');
        $res = Pitch::ExpertReminder();
        $messages = ($res == 1) ? ' message' : ' messages';
        $have = ($res == 1) ? ' has' : ' have';
        $this->out($res . $messages . $have . ' been sent.');
    }
}
