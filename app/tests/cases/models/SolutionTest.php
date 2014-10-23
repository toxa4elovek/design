<?php

namespace app\tests\cases\models;

use \app\extensions\tests\AppUnit;
use \app\models\Solution;
use app\extensions\storage\Rcache;

class SolutionTest extends AppUnit {

    public function setUp() {
        Rcache::init();
        $this->rollUp(array('Pitch', 'Solution'));
    }

    public function tearDown() {
        Rcache::flushdb();
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
        $this->assertEqual('4 Августа 2014, 21:27', Solution::getCreatedDate(2));
        $this->assertFalse(Solution::getCreatedDate(false));
        $this->assertFalse(Solution::getCreatedDate(5));
    }

    public function testGetBestSolution() {
        $solution = Solution::getBestSolution(6);
        $this->assertEqual(6, $solution->id);
        $solution2 = Solution::getBestSolution(7);
        $this->assertEqual(7, $solution2->id);
        $solution3 = Solution::getBestSolution(6);
        $this->assertEqual(6, $solution3->id);
        $solution4 = Solution::getBestSolution(4);
        $this->assertEqual(3, $solution4->id);
    }

}
