<?php

namespace app\controllers;

use \app\models\Question;
use \app\models\Option;
use \app\models\Variant;
use \app\models\Test;
use \app\models\User;

use \lithium\storage\Session;

class QuestionsController extends \app\controllers\AppController
{

    /**
     * Методы, доступные без аутентификации
     *
     * @var array
     */
    public $publicActions = ['index', 'validate', 'activate'];

    public function index()
    {
        $questions = Question::all(['with' => ['Variant']]);
        $questions = $questions->data();
        shuffle($questions);
        foreach ($questions as &$question) {
            shuffle($question['variants']);
        }

        $stats = Option::first(['conditions' => ['name' => 'quiz_stats']]);
        $stats = unserialize($stats->value);
        $limit = Question::$questionsLimit;
        return compact('stats', 'questions', 'limit');
    }

    public function validate()
    {
        $result = [
            'correct' => 0,
            'percent' => 0,
            'total' => Question::$questionsLimit,
        ];
        $firstTime = false;
        $test = null;

        $variants = Variant::all(['conditions' => ['correct' => 1]]);
        $variants = $variants->data();

        if (is_array($this->request->data['questions'])) {
            foreach ($this->request->data['questions'] as $key => $value) {
                if (array_key_exists($value, $variants) && $key == $variants[$value]['question_id']) {
                    $result['correct']++;
                }
            }
        }
        $result['percent'] = round($result['correct'] / $result['total'] * 100);

        if (($user_id = Session::read('user.id')) && ($user = User::first($user_id))) {
            $result['user_id'] = (int) $user_id;
            $result['user_created'] = $user->created;

            if (! $test = Test::first(['conditions' => ['user_id' => (int) $user_id]])) {
                $result['first_time'] = 1;
                $firstTime = true;
            }
            $test = Test::create();
            $test->set($result);
            $test->save();
        } elseif (!Session::read('user.id')) {
            $result['user_id'] = 0;
            $result['first_time'] = 1;
            $test = Test::create();
            $test->set($result);
            $test->save();
        }

        return $this->render([
            'layout' => false,
            'template' => '_result',
            'data' => compact('result', 'firstTime', 'test'),
        ]);
    }

    /**
     *  Метод для активации результата теста
     */
    public function activate()
    {
        $result = Test::activate($this->request->id);
        return compact('result');
    }
}
