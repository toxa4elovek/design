<?php

namespace app\controllers;

use \app\models\Answer;

/**
 * Class AnswersController
 *
 * Контроллер запросов раздела "Помощь"
 *
 * @package app\controllers
 */
class AnswersController extends AppController {

    /**
     * @var array Публичные методы
     */
    public $publicActions = array('index', 'view');

    /**
     * Метод для вывода списка вопросов/результатов поиска
     *
     * @return array|void
     */
    public function index() {
        $search = '';
        if(isset($this->request->query['search'])) {
            require_once LITHIUM_APP_PATH . '/libraries/sphinxapi.php';
            $client = new \SphinxClient();
            $client->open();
            error_reporting(0);
            $client->SetMatchMode( SPH_MATCH_EXTENDED2  );
            $client->SetSortMode(SPH_SORT_RELEVANCE);
            $client->SetFieldWeights(array (
                'title' => 10000,
                'text' => 1,
            ));
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
            $search = implode(' ', $words);
            $answers = array();
            foreach($words as $word) {
                $searchQuery = $client->Query($word, 'help');
                foreach($searchQuery['words'] as $sphinxWord => $data) {
                    $cleanedWord = preg_replace("/[^[:alnum:][:space:]]/u", '', $sphinxWord);
                    $search .= ' ' . $cleanedWord;
                }
                $answersIds = array_keys($searchQuery['matches']);
                foreach ($answersIds as $answerId) {
                    $answer = Answer::first(['conditions' => ['Answer.id' => $answerId]]);
                    $answers[] = $answer->data();
                }
                //$result = Answer::searchForWord($word);
                //$answers += $result->data();
            }
        }else {
            $category = null;
            if((isset($this->request->query['category'])) && (in_array($this->request->query['category'], array_keys(Answer::$questioncategory_id)))) {
                $category = $this->request->query['category'];
            }
            $answers = Answer::getPopularQuesions(1000, $category);
            $answers = $answers->data();
        }
        if((isset($this->request->query['ajax'])) && ($this->request->query['ajax'] == 'true')) {
            return $this->render(array('layout' => false, 'data' => compact('answers', 'search')));
        }else {
            return compact('answers', 'search', 'category');
        }
    }

    /**
     * Метод выводит страницу помощи
     *
     * @return array|object
     */
    public function view() {
        if(($this->request->id != null) && ($answer = Answer::first((int) $this->request->id))) {
            $answer->increaseCounterForRecord();
            $similar = $answer->getSimilarQuesions(10);
            $answer->category = Answer::$questioncategory_id[$answer->questioncategory_id];
            return compact('answer', 'similar');
        }
        return $this->redirect('Answers::index');
    }

}