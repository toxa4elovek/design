<?php

namespace app\controllers;

use \app\models\Answer;

class AnswersController extends \lithium\action\Controller {

    public $publicActions = array('index', 'view');

    public function index() {
        $conditions = array();
        $search = '';
        if(isset($this->request->query['search'])) {
            $searchCondition = urldecode(filter_var($this->request->query['search'], FILTER_SANITIZE_STRING));
            $words = explode(' ', $searchCondition);
            foreach($words as $index => &$searchWord) {
                if($searchWord == '') {
                    unset($words[$index]);
                    continue;
                }
                $searchWord = mb_eregi_replace('[^A-Za-z0-9а-яА-Я]', '', $searchWord);
                $searchWord = trim($searchWord);
            }
            if(count($words) == 1) {
                $answers = Answer::all(array('conditions' => array(
                    'OR' => array(
                        'title' => array('LIKE' => '%' . $words[0] . '%'),
                        'text' => array('LIKE' => '%' . $words[0] . '%')
                    )
                )));
                $answers = $answers->data();
            }else {
                $answers = array();
                foreach($words as $word) {
                    $result = Answer::all(array('conditions' => array(
                    'OR' => array(
                        'title' => array('LIKE' => '%' . $word . '%'),
                        'text' => array('LIKE' => '%' . $word . '%')
                    ))));
                    $answers += $result->data();
                }
            }
            $search = implode(' ', $words);
        }else {
            $answers = Answer::all();
            $answers = $answers->data();
        }
        if((isset($this->request->query['ajax'])) && ($this->request->query['ajax'] == 'true')) {
            return $this->render(array('layout' => false, 'data' => array('answers' => $answers, 'search' => $search)));
        }else {
            return compact('answers', 'search');
        }
    }

    public function view() {
        if($answer = Answer::first($this->request->id)) {
            Answer::increaseCounter($this->request->id);
            $similar = Answer::all(array(
                'order' => array('RAND()'),
                'limit' => 5,
                'conditions' => array(
                    'questioncategory_id' => $answer->questioncategory_id,
                    'id' => array('!=' => $answer->id)
                )
            ));
            return compact('answer', 'similar');
        }
        return $this->redirect('Answers::index');
    }

}

?>