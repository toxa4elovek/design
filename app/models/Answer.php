<?php
namespace app\models;

/**
 * Class Answer
 *
 * Класс для работы с записями вопросов помощи
 *
 * @package app\models
 */
class Answer extends AppModel {

    /**
     * @var array Фиксированные категории вопросов
     */
    public static $questioncategory_id = array(
        '1' => 'Общие вопросы',
        '2' => 'Помощь заказчикам',
        '3' => 'Помощь дизайнерам',
        '4' => 'Оплата и денежные вопросы',
        '5' => 'Для юридических лиц'
    );

    /**
     * Метод увеличивает счётчик просмотра вопросов записи на единицу
     *
     * @param $answerRecord \lithium\data\entity\Record запись вопроса
     * @return bool результат операции
     */
    public function increaseCounterForRecord($answerRecord) {
        $answerRecord->hits +=1;
        return (bool) $answerRecord->save();
    }

    /**
     * Метод возвращяет коллекцию схожих по тематике вопросов
     *
     * @param $answerRecord \lithium\data\entity\Record запись вопроса
     * @param int $limit опциональный лимит
     * @return \lithium\data\collection\RecordSet|null
     */
    public function getSimilarQuesions($answerRecord, $limit = 5) {
        return self::all(array(
            'order' => array('RAND()'),
            'limit' => $limit,
            'conditions' => array(
                'questioncategory_id' => $answerRecord->questioncategory_id,
                'id' => array('!=' => $answerRecord->id)
            )
        ));
    }

    /**
     * Метод возвращяет коллекцию самых популярных вопросов
     *
     * @param int $limit опциональный лимит
     * @return \lithium\data\collection\RecordSet|null
     */
    public static function getPopularQuesions($limit = 5, $category = null) {
        $conditions = array();
        if(!is_null($category)) {
            $conditions = array('questioncategory_id' => $category);
        }
        return self::all(array(
            'conditions' => $conditions,
            'limit' => $limit,
            'order' => array('hits' => 'desc')
        ));
    }

    /**
     * Метод возвращяет вопросы, содержащие в названии или тексте слово $word
     *
     * @param $word string поисковое слово
     * @return \lithium\data\collection\RecordSet|null
     */
    public static function searchForWord($word) {
        return self::all(array('conditions' => array(
            'OR' => array(
                'title' => array('LIKE' => '%' . $word . '%'),
                'text' => array('LIKE' => '%' . $word . '%')
        ))));
    }

}