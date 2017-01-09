<?php

namespace app\extensions\command;

use \app\models\User;

class LastDigest extends \app\extensions\command\CronJob
{

    public function run()
    {
        $this->header('Welcome to the LastDigest command!');
        $count = User::sendLastDigest();
        $this->out('emails has been set to ' . $count . ' users');
    }
}
