<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Pitch;

class PitchTest extends AppUnit {

    public function setUp() {
        $this->rollUp(array('Pitch', 'User'));
    }

    public function tearDown() {
        $this->rollDown(array('Pitch', 'User'));
    }

    public function testGetOwnerOfPitch() {
        $result = Pitch::getOwnerOfPitch(4);
        $this->assertFalse($result);
        $result = Pitch::getOwnerOfPitch(1);
        $this->assertTrue(is_object($result));
        $this->assertEqual(2, $result->id);
    }

}