<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Expert;

class ExpertTest extends AppUnit{

    public function setUp() {
        $this->rollUp('Expert');
    }

    public function tearDown() {
        $this->rollDown('Expert');
    }

    public function testGetPitchExpertUserIds() {
        // список из двух членов
        $ids = array(1, 2);
        $userIds = Expert::getPitchExpertUserIds($ids);
        $this->assertEqual(array(5, 4), $userIds);

        // список из двух членов
        $ids = array(1);
        $userIds = Expert::getPitchExpertUserIds($ids);
        $this->assertEqual(array(5), $userIds);

        // пустой список
        $ids = array();
        $userIds = Expert::getPitchExpertUserIds($ids);
        $this->assertEqual(array(), $userIds);
    }

}