<?php

namespace app\controllers;

use \app\models\Question;

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
}
