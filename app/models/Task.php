<?php

namespace app\models;

class Task extends \app\models\AppModel {

    /**
     * Метод получения завершенных задач
     *
     * @param int $limit
     * @return mixed
     */
    public static function getCompletedTasks($limit = 100) {
        return self::all(array(
            'conditions' => array('completed' => 1),
            'limit' => $limit,
            'order' => array('id' => 'asc')
        ));
    }

    /**
     * Метод создания новой задачи для крона
     *
     * @param $modelId
     * @param $type
     * @return mixed
     */
    public static function createNewTask($modelId, $type) {
        $data = array(
            'model_id' => $modelId,
            'type' => $type,
            'date' => date('Y-m-d H:i:s'),
            'completed' => '0'
        );
        $newTask = Task::create($data);
        $newTask->save();
        return $newTask->id;
    }

}