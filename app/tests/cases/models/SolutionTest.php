<?php

namespace app\tests\cases\models;

use \app\extensions\tests\AppUnit;
use \app\models\Solution;

class SolutionTest extends AppUnit {

    public function setUp() {
        $this->rollUp('Solution');
    }

    public function tearDown() {
       $this->rollDown('Solution'); 
    }

    public function testGetCreatedDate() {
        $this->assertEqual('14 Августа 2014, 21:27', Solution::getCreatedDate(1));
        $this->assertEqual('4 Августа 2014, 21:27',Solution::getCreatedDate(2));
        $this->assertFalse(Solution::getCreatedDate(false));
        $this->assertFalse(Solution::getCreatedDate(5));
    }

}
