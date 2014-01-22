<?php

namespace app\models;

class Task extends \app\models\AppModel {

    public function getCompletedTasks($limit = 100) {
        return self::all(array(
            'conditions' => array('completed' => 1),
            'limit' => $limit,
            'order' => array('id' => 'asc')
        ));
    }

}