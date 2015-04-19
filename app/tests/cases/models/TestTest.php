<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Test;

class TestTest extends AppUnit {

    public function setUp()
    {
        $this->rollUp('Test');
    }

    public function tearDown()
    {
        $this->rollDown('Test');
    }

    public function testActivate() {
        $result = Test::activate(10000);
        $this->assertFalse($result);
        $result = Test::activate(1);
        $this->assertTrue($result);
        $testItem = Test::first(1);
        $this->assertEqual($testItem->active, true);
    }
}