<?php

namespace app\extensions\command;

use \app\models\User;

class BankAccounts extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the BankAccounts command!');
        $users = User::all(array('conditions' => array('paymentOptions' => array('!=' => ''))));
        $count = 0;
        foreach ($users as $user) {
            if (false === User::accountCheck($user)) {
                $data = unserialize($user->paymentOptions);
                $data[0]['accountnum'] = '';
                $user->paymentOptions = serialize($data);
                $user->save(null, array('validate' => false));
                $count++;
            }
        }
        $this->out("$count user accounts reset");
    }
}

?>