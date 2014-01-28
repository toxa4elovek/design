<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Task;

class TaskTest extends AppUnit{

    public function setUp() {
        $this->rollUp('Task');
    }

    public function tearDown() {
        $this->rollDown('Task');
    }

    public function testCreateTask() {
        $result = Task::createNewTask(3, 'newpitch');
        $task = Task::find('first', array('conditions' => array('id' => $result)));
        $result = array(
            'id' => $result,
            'model_id' => '3',
            'type' => 'newpitch',
            'date' => date('Y-m-d H:i:s'),
            'completed' => '0'
        );
        $this->assertEqual($result, $task->data());
    }

    public function testGetCompletedTask() {
        $items = Task::getCompletedTasks(2);
        $ids = array();
        $correctIds = array('1', '3');
        foreach($items as $item) {
            $ids[] = $item->id;
        }
        $this->assertEqual($correctIds, $ids);
    }

}