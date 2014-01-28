<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Post;

class PostTest extends AppUnit {

    public function setUp() {
        $this->rollUp('Post');
    }

    public function tearDown() {
        $this->rollDown('Post');
    }

    public function testTrue() {
        $this->assertEqual(true, true);
    }

}