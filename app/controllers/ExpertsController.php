<?php
namespace app\controllers;

use \app\models\Expert;

class ExpertsController extends \app\controllers\AppController {

    public $publicActions = array('index', 'view');

    public function index() {
        $experts = Expert::all(array('order' => array('id' => 'asc')));
        return compact('experts');
    }

    public function view() {
        $validExperts = array('1', '2', '3', '4', '5', '6', '7', '8');
        if(($this->request->id) && (in_array($this->request->id, $validExperts))) {
            $expert = Expert::first($this->request->id);
            $questions = $this->popularQuestions();
            return compact('expert', 'questions');
            //return $this->render(array('template' => $this->request->id, 'data' => array('expert', 'questions' => $this->popularQuestions())));
        }else {
            return $this->redirect('/experts');
        }
    }



}