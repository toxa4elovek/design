<?php
namespace app\controllers;

use \app\models\Expert;

/**
 * Класс для отображения страниц, связанных с экспертами
 *
 * Class ExpertsController
 * @package app\controllers
 */
class ExpertsController extends AppController {

    /**
     * Список методов, доступных без регистрации
     *
     * @var array
     */
    public $publicActions = array('index', 'view', 'viewByUser');

    /**
     * Просмотр списка экспертов
     *
     * @return array
     */
    public function index() {
        $experts = Expert::all(array('order' => array('id' => 'asc')));
        return compact('experts');
    }

    /**
     * Метод отображает страницу эксперта
     *
     * @return array|object
     */
    public function view() {
        if($expert = Expert::first($this->request->id)) {
            $questions = $this->popularQuestions();
            return compact('expert', 'questions');
        }else {
            return $this->redirect('/experts');
        }
    }

    /**
     * Метод отображает страницу эксперта, ищем эксперта по id обычного пользователя
     *
     * @return object
     */
    public function viewByUser() {
        if($expert = Expert::first(array('conditions' => array('user_id' => $this->request->id)))) {
            return $this->redirect('/experts/view/' . $expert->id);
        }
        return $this->redirect('/experts');
    }

}