<?php

namespace app\tests\cases\models;

use \app\extensions\tests\AppUnit;
use \app\models\Solution;

class SolutionTest extends AppUnit {

    public function setUp() {
        $this->rollUp('Solution');
    }

    public function tearDown() {
        $this->rollDown(array('Solution', 'Solutionfile'));
    }

    public function testSave() {  
        $this->assertFalse(Solution::copy(0, 0));
        $this->assertFalse(Solution::copy(100076, ''));
        Solution::copy(100076, 2);
        $new = Solution::first(array('order' => array('id' => 'DESC')));
        $old = Solution::first(2);
        $this->assertNotEqual($new->data(), $old->data());
    }


    public function testGetCreatedDate() {
        $this->assertEqual('14 Августа 2014, 21:27', Solution::getCreatedDate(1));
        $this->assertEqual('4 Августа 2014, 21:27',Solution::getCreatedDate(2));
        $this->assertFalse(Solution::getCreatedDate(false));
        $this->assertFalse(Solution::getCreatedDate(5));
    }

}
