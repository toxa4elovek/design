<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Solution;

class SolutionTest extends AppUnit {

    public function setUp() {

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

}
