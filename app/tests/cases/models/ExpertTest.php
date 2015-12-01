<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Expert;
use app\extensions\storage\Rcache;

class ExpertTest extends AppUnit{

    public function setUp() {
        Rcache::init();
        Rcache::flushdb();
        $this->rollUp('Expert');
    }

    public function tearDown() {
        $this->rollDown('Expert');
    }

    public function testGetExpertUserIds() {
        $result = Expert::getExpertUserIds();
        $expected = array(5, 4, 6);
        $this->assertEqual($expected, $result);

        $ids = array(1, 2);
        $userIds = Expert::getExpertUserIds($ids);
        $this->assertEqual(array(5, 4), $userIds);

        // список из двух членов
        $ids = array(1);
        $userIds = Expert::getExpertUserIds($ids);
        $this->assertEqual(array(5), $userIds);

        // пустой список
        $ids = array();
        $userIds = Expert::getExpertUserIds($ids);
        $this->assertEqual(array(5, 4, 6), $userIds);

        // без аргумента
        $userIds = Expert::getExpertUserIds();
        $this->assertEqual(array(5, 4, 6), $userIds);
    }

}