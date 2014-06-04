<?php

namespace app\controllers;

use \app\models\Question;
use \app\models\Variant;
use \app\models\Test;

use \lithium\storage\Session;

class QuestionsController extends \app\controllers\AppController {

    public function index() {

        $questions = Question::all(array('with' => array('Variant')));
        $questions = $questions->data();
        foreach ($questions as &$question) {
            shuffle($question['variants']);
        }

        return compact('questions');
    }

    public function intro() {
        $total = 1277;
        $params = array(
            '0' => array(
                'text' => 'Количество  тестируемых',
                'percent' => round(1000 / $total * 100),
                'value' => 1000,
            ),
            '1' => array(
                'text' => 'Неудовлетворительно',
                'percent' => round(837 / $total * 100),
                'value' => 837,
            ),
            '2' => array(
                'text' => 'Удовлетворительно',
                'percent' => round(654 / $total * 100),
                'value' => 654,
            ),
            '3' => array(
                'text' => 'Хорошо',
                'percent' => round(837 / $total * 100),
                'value' => 837,
            ),
            '4' => array(
                'text' => 'Отлично',
                'percent' => round(12 / $total * 100),
                'value' => 12,
            ),
        );

        return compact('params');
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
