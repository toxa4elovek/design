<?php

namespace app\extensions\command;

use \app\models\User;
use \app\extensions\mailers\SpamMailer;

class ReferalPayments extends \app\extensions\command\CronJob {

    protected $dest = 'nyudmitriy@godesigner.ru';

    public function run() {
        $this->header('Welcome to the ReferalPayments command!');
        $count = User::getReferalPayments();
        if ($count > 0) {
            $data = array('to' => $this->dest);
            Spammailer::referalpayments($data);
        }
        $this->out($count . ' users');
    }
}

?>