<?php

namespace app\extensions\command;

use \app\models\User;

class Comeback extends \app\extensions\command\CronJob
{

    public function run()
    {
        $this->header('Welcome to the Comeback command!');
        $count = User::sendSpamToLostClients();
        $this->out('emails has been set to ' . $count . ' users');
    }
}
