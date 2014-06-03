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
}
