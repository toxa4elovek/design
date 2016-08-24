<?php
namespace app\models;

/**
 * Class Answer
 *
 * Класс для работы с записями вопросов помощи
 *
 * @package app\models
 * @method Record|null first(integer $id) static
 * @method int count(array $conditions) static
 * @method RecordSet|null all(array $conditions) static
 *
 */
class Answer extends AppModel
{

    /**
     * @var array Фиксированные категории вопросов
     */
    public static $questioncategory_id = [
        '1' => 'Общие вопросы',
        '2' => 'Помощь заказчикам',
        '3' => 'Помощь дизайнерам',
        '4' => 'Оплата и денежные вопросы',
        '5' => 'Для юридических лиц'
    ];

    /**
     * Метод увеличивает счётчик просмотра вопросов записи на единицу
     *
     * @param $answerRecord \lithium\data\entity\Record запись вопроса
     * @return bool результат операции
     */
    public function increaseCounterForRecord($answerRecord)
    {
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
    public function getSimilarQuesions($answerRecord, $limit = 5)
    {
        return self::all([
            'order' => ['RAND()'],
            'limit' => $limit,
            'conditions' => [
                'questioncategory_id' => $answerRecord->questioncategory_id,
                'id' => ['!=' => $answerRecord->id]
            ]
        ]);
    }

    /**
     * Метод возвращяет коллекцию самых популярных вопросов
     *
     * @param int $limit опциональный лимит
     * @param int $category опциональная категория вопроса
     * @return \lithium\data\collection\RecordSet|null
     */
    public static function getPopularQuesions($limit = 5, $category = null)
    {
        $conditions = [];
        if (!is_null($category)) {
            $conditions = ['questioncategory_id' => (int) $category];
        }
        return self::all([
            'conditions' => $conditions,
            'limit' => (int) $limit,
            'order' => ['hits' => 'desc']
        ]);
    }

    /**
     * Метод возвращяет вопросы, содержащие в названии или тексте слово $word
     *
     * @param $word string слово для поиска
     * @return \lithium\data\collection\RecordSet|null
     */
    public static function searchForWord($word)
    {
        return self::all(['conditions' => [
            'OR' => [
                'title' => ['LIKE' => '%' . $word . '%'],
                'text' => ['LIKE' => '%' . $word . '%']
        ]]]);
    }
}
