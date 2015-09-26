<?php

namespace app\extensions\command;

use \app\models\User;
use \app\extensions\mailers\SpamMailer;

class DiscountDesigner extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the DiscountDesigner command!');
        /*$users = User::first(array('conditions' => array(
                        'isDesigner' => 1,
                        'confirmed_email' => 1), 'with' => 'Solution'));*/
        $users = User::all(array('conditions' => array('email' => 'devochkina@godesigner.ru'), 'with' => 'Solution'));
        $count = 0;
        foreach ($users as $user) {
            if (count($user->solutions)) {
                $data = array(
                    'email' => $user->email,
                    'user' => $user,
                    'subject' => 'Зарабатывай больше!',
                );
                SpamMailer::discountDesigners($data);
                $count++;
            }
        }
        $this->out("$count users emailed");
    }

}

?>