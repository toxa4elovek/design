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

    public function testLock() {
        $this->assertTrue(Post::lock(1, 10));
        $this->assertTrue(Post::lock(1, 10));
        $this->assertFalse(Post::lock(1, 15));
    }

    public function testLastEditTime() {
        $post = Post::first(1);
        $post->lastEditTime = '0000-00-00 00:00:00';
        Post::updateLastEditTime(1);
        $post = Post::first(1);
        $this->assertEqual(date('Y-m-d H:i:s'), $post->lastEditTime);
    }

    public function testUnlock() {
        Post::lock(1, 10);
        $this->assertTrue(Post::unlock(1));
        $this->assertFalse(Post::unlock(5));
    }

}