<?php

namespace app\controllers;

use \app\models\Question;
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

        $usersTotal = User::count();
        $usersTested = Test::count();
        $usersNeud = Test::count(array(
            'conditions' => array(
                'percent' => array(
                    '<' => 70,
                ),
            ),
        ));
        $usersUd = Test::count(array(
            'conditions' => array(
                'percent' => array(
                    '>=' => 70,
                    '<' => 80,
                ),
            ),
        ));
        $usersGood = Test::count(array(
            'conditions' => array(
                'percent' => array(
                    '>=' => 80,
                    '<' => 90,
                ),
            ),
        ));
        $usersExc = Test::count(array(
            'conditions' => array(
                'percent' => array(
                    '>' => 90,
                ),
            ),
        ));
        $stats = array(
            '0' => array(
                'text' => 'Количество  тестируемых',
                'percent' => round($usersTested / $usersTotal * 100),
                'value' => $usersTested,
            ),
            '1' => array(
                'text' => 'Неудовлетворительно',
                'percent' => round($usersNeud / $usersTested * 100),
                'value' => $usersNeud,
            ),
            '2' => array(
                'text' => 'Удовлетворительно',
                'percent' => round($usersUd / $usersTested * 100),
                'value' => $usersUd,
            ),
            '3' => array(
                'text' => 'Хорошо',
                'percent' => round($usersGood / $usersTested * 100),
                'value' => $usersGood,
            ),
            '4' => array(
                'text' => 'Отлично',
                'percent' => round($usersExc / $usersTested * 100),
                'value' => $usersExc,
            ),
        );

        return compact('stats', 'questions');
    }

    public function validate() {
        $result = array(
            'correct' => 0,
            'percent' => 0,
            'total' => Question::count(),
        );

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
            }

        }

        return $this->render(array(
            'layout' => false,
            'template' => '_result',
            'data' => compact('result'),
        ));
    }
}
