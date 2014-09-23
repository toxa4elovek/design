<?php

namespace app\extensions\command;

use \app\models\User;
use \app\extensions\mailers\SpamMailer;

class DesignerRemind extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the DesignerRemind command!');
        $users = User::all(array('conditions' => array(
                        'isDesigner' => 1,
                        'created' => array(
                            '>' => date('Y-m-d', strtotime(date('Y-m-d') . ' -10 days')),
                            '<' => date('Y-m-d', strtotime(date('Y-m-d') . ' -9 days'))))));
        $count = 0;
        foreach ($users as $user) {
            SpamMailer::designerRemind($user);
            $count++;
        }
        $this->out("$count users emailed");
    }

}

?>