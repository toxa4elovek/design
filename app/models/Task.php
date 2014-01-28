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

    /**
     * Метод удаляет $count завершенных залач
     *
     * @param $count
     * @return int
     */
    public static function deleteCompleted($count = 100) {
        $tasksToDelete = self::getCompletedTasks($count);
        $count = 0;
        foreach($tasksToDelete as $task) {
            $task->delete();
            $count++;
        }
        return $count;
    }

    /**
     * Метод помечает задачу как выполненную, вызывается для объекта задачи
     *
     * @param $record
     * @return bool
     */
    public function markAsCompleted($record) {
        if($record->completed == 1) {
            return false;
        }else {
            $record->completed = 1;
            return $record->save();
        }
    }

}