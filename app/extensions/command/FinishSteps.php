<?php

namespace app\extensions\command;

use \app\models\User;

class FinishSteps extends \app\extensions\command\CronJob {

    public function run() {
        $count = User::sendStep2Spam();
        $this->out('Step2 emails has been set to ' . $count . 'users');
        $count = User::sendStep3Spam();
        $this->out('Step3 emails has been set to ' . $count . 'users');
        $count = User::sendStep4Spam();
        $this->out('Step4 emails has been set to ' . $count . 'users');
    }
}

?>