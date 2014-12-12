<?php

namespace app\extensions\command;

use \app\models\User;
use \app\models\Post;
use \app\models\Pitch;
use \app\extensions\mailers\SpamMailer;

class DesignerRemind extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the DesignerRemind command!');
        $users = User::all(array('conditions' => array(
                        'isDesigner' => 1,
                        'created' => array(
                            '>' => date('Y-m-d', strtotime(date('Y-m-d') . ' -10 days')),
                            '<' => date('Y-m-d', strtotime(date('Y-m-d') . ' -9 days'))))));
        $pitches = Pitch::getPitchesForHomePage();
        $posts = Post::all(array('conditions' => array('published' => 1),'limit' => 5, 'order' => array('created' => 'desc')));
        $count = 0;
        foreach ($users as $user) {
            $data = array(
                'email' => $users->email,
                'subject' => 'Добро пожаловать!',
                'pitches' => $pitches,
                'posts' => $posts
            );
            SpamMailer::designerRemind($data);
            $count++;
        }
        $this->out("$count users emailed");
    }

}

?>