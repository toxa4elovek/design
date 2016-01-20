<?php

namespace app\models;

use lithium\data\collection\RecordSet;
use lithium\data\entity\Record;

/**
 * Class Task
 *
 * Класс для взаимодействия с очередью задач
 *
 * @package app\models
 * @method Record|null first(array $conditions) static
 * @method int count(array $conditions) static
 * @method RecordSet|null all(array $conditions = []) static
 */
class Task extends AppModel
{

    /**
     * Метод получения завершенных задач
     *
     * @param $limit integer
     * @return mixed
     */
    public static function getCompletedTasks($limit = 100)
    {
        return self::all(array(
            'conditions' => array('completed' => 1),
            'limit' => $limit,
            'order' => array('id' => 'asc')
        ));
    }

    /**
     * Метод создания новой задачи для крона
     *
     * @param $modelId integer
     * @param $type string
     * @param $delay integer
     * @return integer
     */
    public static function createNewTask($modelId, $type, $delay = 0)
    {
        $data = array(
            'model_id' => (int) $modelId,
            'type' => $type,
            'date' => date('Y-m-d H:i:s', time() + $delay),
            'completed' => '0'
        );
        $newTask = Task::create($data);
        $newTask->save();
        return $newTask->id;
    }

    /**
     * Метод удаляет $count завершенных залач
     *
     * @param $count integer
     * @return int
     */
    public static function deleteCompleted($count = 100)
    {
        $tasksToDelete = self::getCompletedTasks((int) $count);
        $deletedCount = 0;
        foreach ($tasksToDelete as $task) {
            $task->delete();
            $deletedCount++;
        }
        return $deletedCount;
    }

    /**
     * Метод помечает задачу как выполненную, вызывается для объекта задачи
     *
     * @param $record /lithium/data/entity/Record
     * @return bool
     */
    public function markAsCompleted($record)
    {
        if ($record->completed != 1) {
            $record->completed = 1;
            return $record->save();
        }
        return false;
    }

    /**
     * Метод помечает задачу как невыполненную, вызывается для объекта задачи
     *
     * @param $record /lithium/data/entity/Record
     * @return bool
     */
    public function markAsIncomplete($record)
    {
        if ($record->completed == 1) {
            $record->completed = 0;
            return $record->save();
        }
        return false;
    }
}
