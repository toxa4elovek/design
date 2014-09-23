<?php

namespace app\extensions\command;

use app\extensions\mailers\CommentsMailer;
use \app\models\Task;
use \app\models\Pitch;
use \app\models\Solution;
use \app\models\User;
use app\extensions\mailers\SolutionsMailer;

class Tasks extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the Task Command');
        set_time_limit(0);
        $tasks = Task::all(array('conditions' => array('completed' => 0)));
        $count = count($tasks);
        foreach($tasks as $task) {
            $methodName = '__' . $task->type;
            if(method_exists('app\extensions\command\Tasks', $methodName)) {
                $task->markAsCompleted();
                Tasks::$methodName($task);
            }
        }
        if($count) {
            $this->out($count . ' tasks completed');
        }else {
            $this->out('No tasks are in due.');
        }
    }

    private function __newpitch($task) {
        $pitch = Pitch::first($task->model_id);
        $params = array('pitch' => $pitch);
        User::sendSpamNewPitch($params);
        $this->out('New pitch email has been sent');
    }

    private function __newSolutionNotification($task) {
        if($result = SolutionsMailer::sendNewSolutionNotification($task->model_id)) {
            $this->out('New solution notification sent');
        }else {
            $this->out('User do not want to receive notification for solution ' . $task->model_id);
        }
    }

    private function __victoryNotification($task) {
        if($result = SolutionsMailer::sendVictoryNotification($task->model_id)) {
            $this->out('New victory notification sent');
        }else {
            $this->out('Error sending victory notification');
        }
    }

    private function __newCommentFromAdminNotification($task) {
        if($count = CommentsMailer::sendNewCommentFromAdminNotification($task->model_id)) {
            $this->out($count . ' new comment from admin notifications sent');
        }else {
            $this->out('no new comment from admin notifications sent');
        }
    }

    private function __newPersonalCommentNotification($task) {
        if($result = CommentsMailer::sendNewPersonalCommentNotification($task->model_id)) {
            $this->out('New personal comment notifications sent');
        }else {
            $this->out('User do not want to receive this notification');
        }
    }

    private function __dvaSpam($task) {
        $count = User::sendDvaSpam();
        $this->out('Emails has been set to ' . $count . ' users');
    }
    
    private function  __bestSolutionInTwitter($task) {
        $lastday = time() - (DAY);
        $day = date('Y-m-d', $lastday);
        $start = strtotime($day);
        $end = $start + DAY;
        $solution = Solution::first(array(
            'conditions' => array(
                    'Pitch.status' => 0,
                    'Pitch.published' => 0,
                    'Pitch.private' => 0,
                    'created' => array('>=' => date('Y-m-d H:i:s', $start), '<=' => date('Y-m-d H:i:s', $end))
            ),
            'order' => array('Solution.likes' => 'desc', 'Solution.views' => 'desc'),
            'with' => array('Pitch')
        ));
        $params = '?utm_source=twitter&utm_medium=tweet&utm_content=winner-tweet&utm_campaign=sharing';
        $solutionUrl = 'http://www.godesigner.ru/pitches/viewsolution/' . $solution->id . $params;
        //Самое популярное решение за 24.09.2014 «Лого для сервиса Бригадир Онлайн» http://www.godesigner.ru/pitches/viewsolution/106167 #Go_Deer
        $tweet = 'Самое популярное решение за '.$day.' «' . $solution->pitch->title . '» ' . $solutionUrl . ' #Go_Deer';
        if(User::sendTweet($tweet)) {
            $this->out('The best solution for '.$day.' sent');
        } else {
            $this->out('Error! The best solution for '.$day.' was not sent');
        }
    }

}