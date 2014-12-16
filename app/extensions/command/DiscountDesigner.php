<?php

namespace app\extensions\command;

use \app\models\User;
use \app\extensions\mailers\SpamMailer;

class DiscountDesigner extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the DiscountDesigner command!');
        $user = User::first(array('conditions' => array(
                        'isDesigner' => 1,
                        'confirmed_email' => 1), 'with' => 'Solution'));
        $count = 0;
/*
        foreach ($users as $user) {
            if (count($user->solutions)) {
                $data = array(
                    'email' => $user->email,
                    'subject' => 'Зарабатывай больше!'
                );
                SpamMailer::discountDesigners($data);
                $count++;
            }
        }
*/

        //foreach ($users as $user) {
            $data = array(
                //'email' => $user->email,
                'user' => $user,
                'subject' => 'Зарабатывай больше!',
                'email' => 'nyudmitriy@godesigner.ru'
            );
            SpamMailer::discountDesigners($data);
            $count++;
            //break;
        //}
        $this->out("$count users emailed");
    }

}

?>