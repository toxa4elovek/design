<?php

namespace app\extensions\command;

use \app\models\User;

class Spamtest extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the Comeback command!');
        $result = User::getDesignersForSpam();
        var_dump($result);
        $this->out('emails has been set to ' . $count . 'users');
    }
}

?>