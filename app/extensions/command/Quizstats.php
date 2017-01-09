<?php

namespace app\extensions\command;

use \app\models\Option;
use \app\models\Question;

class Quizstats extends \app\extensions\command\CronJob
{

    public function run()
    {
        $this->header('Welcome to the QuizStats command!');

        $data = Question::getStats();
        $string = serialize($data);

        if (!$option = Option::first(['conditions' => ['name' => 'quiz_stats']])) {
            $option = Option::create();
            $option->name = 'quiz_stats';
        }

        $option->value = $string;
        $option->save();

        $this->out('Stats stored!');
    }
}
