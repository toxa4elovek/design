<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Task;

class TaskTest extends AppUnit
{

    public function setUp()
    {
        $this->rollUp('Task');
    }

    public function tearDown()
    {
        $this->rollDown('Task');
    }

    public function testCreateTask()
    {
        $result = Task::createNewTask(3, 'newpitch');
        $task = Task::find('first', ['conditions' => ['id' => $result]]);
        $result = [
            'id' => $result,
            'model_id' => '3',
            'type' => 'newpitch',
            'date' => date('Y-m-d H:i:s'),
            'completed' => '0'
        ];
        $this->assertEqual($result, $task->data());

        $result = Task::createNewTask(4, 'postNewsToSocial', HOUR);
        $task = Task::find('first', ['conditions' => ['id' => $result]]);
        $result = [
            'id' => $result,
            'model_id' => '4',
            'type' => 'postNewsToSocial',
            'date' => date('Y-m-d H:i:s', time() + HOUR),
            'completed' => '0'
        ];
        $this->assertEqual($result, $task->data());
    }

    public function testGetCompletedTask()
    {
        $items = Task::getCompletedTasks(2);
        $ids = [];
        $correctIds = ['1', '3'];
        foreach ($items as $item) {
            $ids[] = $item->id;
        }
        $this->assertEqual($correctIds, $ids);
    }

    public function testMarkAsCompleted()
    {
        $item = Task::first(['conditions' => ['completed' => 1]]);
        $result = $item->markAsCompleted();
        $this->assertFalse($result);
        $item2 = Task::first(['conditions' => ['completed' => 0]]);
        $result = $item2->markAsCompleted();
        $this->assertTrue($result);
        $item2 = Task::first(['conditions' => ['id' => $item2->id]]);
        $this->assertEqual('1', $item2->completed);
    }

    public function testDeleteCompleted()
    {
        $count = Task::deleteCompleted(2);
        $this->assertEqual(2, $count);
        $completedTasks = Task::all(['conditions' => ['completed' => 1]]);
        $this->assertEqual(1, count($completedTasks));
    }
}
