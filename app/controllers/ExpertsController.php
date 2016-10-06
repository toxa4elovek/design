<?php
namespace app\controllers;

use app\models\Comment;
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
        $experts = Expert::all(array('order' => array('id' => 'asc'), 'conditions' => array('enabled' => 1)));
        return compact('experts');
    }

    /**
     * Метод отображает страницу эксперта
     *
     * @return array|object
     */
    public function view() {
        if($expert = Expert::first($this->request->id)) {
            $questions = $this->popularQuestions(10);

            $postNonBreakList = array('и', 'в', 'для', 'не', 'на', 'с', '&mdash;', '-', 'по');
            $preBreakingList = array('(\s\d+\s)' => 'гг.');

            foreach($postNonBreakList as $word) {
                $postNonBreakList[] = $this->mb_ucfirst($word);
            }
            foreach($postNonBreakList as $word) {
                $pattern = "(\s)($word) ";
                $expert->text = preg_replace("/$pattern/im", '$1$2&nbsp;', $expert->text);
            }

            foreach($preBreakingList as $prevPattern => $word) {
                $pattern = "$prevPattern($word)";
                //$expert->text = preg_replace("/$pattern/im", '$1$2&nbsp;', $expert->text);
            }

            $comments = Comment::all([
                'conditions' => [
                    'Comment.user_id' => $expert->user_id,
                    'Pitch.private' => 0,
                    'Pitch.category_id' => ['!=' => '7']
                ],
                'order' => ['Comment.id' => 'desc'],
                'limit' => 3,
                'with' => ['Pitch']
            ]);

            return compact('expert', 'questions', 'comments');
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

    public function mb_ucfirst($string, $enc = 'UTF-8') {
        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) .
        mb_substr($string, 1, mb_strlen($string, $enc), $enc);
    }

}