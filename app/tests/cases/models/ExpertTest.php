<?php

namespace app\tests\cases\models;

use app\extensions\storage\Rcache;
use app\extensions\tests\AppUnit;
use app\models\Expert;
use app\models\Pitch;

class ExpertTest extends AppUnit
{

    public $models = ['Expert', 'Pitch', 'Comment', 'User'];

    public function setUp()
    {
        Rcache::init();
        Rcache::flushdb();
        $this->rollUp($this->models);
    }

    public function tearDown()
    {
        Rcache::flushdb();
        $this->rollDown($this->models);
    }

    public function testGetExpertUserIds()
    {
        $result = Expert::getExpertUserIds();
        $expected = [5, 4, 6];
        $this->assertEqual($expected, $result);

        $ids = [1, 2];
        $userIds = Expert::getExpertUserIds($ids);
        $this->assertEqual([5, 4], $userIds);

        // список из двух членов
        $ids = [1];
        $userIds = Expert::getExpertUserIds($ids);
        $this->assertEqual([5], $userIds);

        // пустой список
        $ids = [];
        $userIds = Expert::getExpertUserIds($ids);
        $this->assertEqual([5, 4, 6], $userIds);

        // без аргумента
        $userIds = Expert::getExpertUserIds();
        $this->assertEqual([5, 4, 6], $userIds);
    }

    public function testIsExpertNeedToWriteComment()
    {
        $project = Pitch::first(3);
        $result = Expert::isExpertNeedToWriteComment($project, 1);
        $this->assertTrue($result);
        $result = Expert::isExpertNeedToWriteComment($project, 2);
        $this->assertFalse($result);
        $result = Expert::isExpertNeedToWriteComment($project, 3);
        $this->assertFalse($result);

        $project = Pitch::first(1);
        $result = Expert::isExpertNeedToWriteComment($project, 3);
        $this->assertFalse($result);

        $project = Pitch::first(3);
        $project->status = 0;
        $project->save();
        $result = Expert::isExpertNeedToWriteComment($project, 1);
        $this->assertFalse($result);
        $result = Expert::isExpertNeedToWriteComment($project, 2);
        $this->assertFalse($result);
    }
}
