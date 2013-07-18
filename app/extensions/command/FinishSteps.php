<?php

namespace app\extensions\command;

use \app\models\User;

class FinishSteps extends \lithium\console\Command {

    public function run() {
        $this->header('Welcome to the FinishSteps command!');
        $count = User::sendChooseWinnerSpam();
        $this->out('Choose winner emails has been set to ' . $count . 'users');
        $count = User::sendStep2Spam();
        $this->out('Step2 emails has been set to ' . $count . 'users');
        $count = User::sendStep3Spam();
        $this->out('Step3 emails has been set to ' . $count . 'users');
        $count = User::sendStep4Spam();
        $this->out('Step4 emails has been set to ' . $count . 'users');
    }
}

?>