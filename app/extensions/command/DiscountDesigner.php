<?php

namespace app\extensions\command;

use \app\models\User;
use \app\extensions\mailers\SpamMailer;

class DiscountDesigner extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the DiscountDesigner command!');
        $users = User::all(array('conditions' => array(
                        'isDesigner' => 1,
                        'confirmed_email' => 1), 'with' => 'Solution'));
        $count = 0;
        foreach ($users as $user) {
            $data = array(
                'email' => $user->email,
                'subject' => 'Зарабатывай больше!'
            );
            SpamMailer::discountDesigners($data);
            $count++;
        }
        $this->out("$count users emailed");
    }

}

?>