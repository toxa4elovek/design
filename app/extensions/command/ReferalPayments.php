<?php

namespace app\extensions\command;

use \app\models\User;
use \app\extensions\mailers\SpamMailer;

class ReferalPayments extends \app\extensions\command\CronJob {

    protected $dest = 'nyudmitriy@godesigner.ru';
    protected $admin = 'fedchenko@godesigner.ru';

    public function run() {
        $this->header('Welcome to the ReferalPayments command!');
        $count = User::getReferalPaymentsCount();
        if ($count > 0) {
            $data = array('to' => $this->dest);
            Spammailer::referalpayments($data);
            $data = array('to' => $this->admin);
            Spammailer::referalpayments($data);
        }
        $this->out($count . ' users');
    }
}