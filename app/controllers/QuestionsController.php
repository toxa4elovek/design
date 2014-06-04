<?php

namespace app\controllers;

use \app\models\Question;
use \app\models\Option;
use \app\models\Variant;
use \app\models\Test;
use \app\models\User;

use \lithium\storage\Session;

class QuestionsController extends \app\controllers\AppController {

    public function index() {

        $questions = Question::all(array('with' => array('Variant')));
        $questions = $questions->data();
        foreach ($questions as &$question) {
            shuffle($question['variants']);
        }

        $stats = Option::first(array('conditions' => array('name' => 'quiz_stats')));
        $stats = unserialize($stats->value);

        return compact('stats', 'questions');
    }

    public function validate() {
        $result = array(
            'correct' => 0,
            'percent' => 0,
            'total' => Question::count(),
        );
        $firstTime = false;

        if (!empty($this->request->data['questions']) && is_array($this->request->data['questions']) && ($user_id = Session::read('user.id'))) {
            $variants = Variant::all(array('conditions' => array('correct' => 1)));
            $variants = $variants->data();
            $result['user_id'] = (int) $user_id;

            foreach ($this->request->data['questions'] as $key => $value) {
                if (array_key_exists($value, $variants) && $key == $variants[$value]['question_id']) {
                    $result['correct']++;
                }
            }

            $result['percent'] = round($result['correct'] / $result['total'] * 100);

            if (! $test = Test::first(array('conditions' => array('user_id' => (int) $user_id)))) {
                $test = Test::create();
                $test->set($result);
                $test->save();
                $firstTime = true;
            }

        }

        return $this->render(array(
            'layout' => false,
            'template' => '_result',
            'data' => compact('result', 'firstTime'),
        ));
    }
}
