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

    public function testMarkAsCompleted() {
        $item = Task::first(array('conditions' => array('completed' => 1)));
        $result = $item->markAsCompleted();
        $this->assertFalse($result);
        $item2 = Task::first(array('conditions' => array('completed' => 0)));
        $result = $item2->markAsCompleted();
        $this->assertTrue($result);
        $item2 = Task::first(array('conditions' => array('id' => $item2->id)));
        $this->assertEqual('1', $item2->completed);
    }

    public function testDeleteCompleted() {
        $count = Task::deleteCompleted(2);
        $this->assertEqual(2, $count);
        $completedTasks = Task::all(array('conditions' => array('completed' => 1)));
        $this->assertEqual(1, count($completedTasks));
    }

}