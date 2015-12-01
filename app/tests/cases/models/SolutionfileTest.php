<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Solutionfile;
use app\models\Solution;

class SolutionfileTest extends AppUnit {

    public function setUp() {

    }

    public function tearDown() {
        $this->rollDown(array('Solution', 'Solutionfile'));
    }

    public function testSave() {
        /*
        $this->assertTrue(Solutionfile::copy(2, 100075));
        $solutionFiles = Solutionfile::find('all',array('conditions'=>array('model_id'=>100075)));
        foreach ($solutionFiles as $file) {
            $this->assertTrue(file_exists($file->filename));
        }
        $this->assertFalse(Solutionfile::copy(0, 0));*/
    }

}
