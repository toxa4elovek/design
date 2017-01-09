<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Post;

class PostTest extends AppUnit
{

    public function setUp()
    {
        $this->rollUp('Post');
    }

    public function tearDown()
    {
        $this->rollDown('Post');
    }

    public function testLock()
    {
        $this->assertTrue(Post::lock(1, 10));
        $this->assertTrue(Post::lock(1, 10));
        $this->assertFalse(Post::lock(1, 15));
    }

    public function testLastEditTime()
    {
        $post = Post::first(1);
        $post->lastEditTime = '0000-00-00 00:00:00';
        Post::updateLastEditTime(1);
        $post = Post::first(1);
        $this->assertEqual(date('Y-m-d H:i:s'), $post->lastEditTime);
        $this->assertFalse(Post::updateLastEditTime(9999));
    }

    public function testUnlock()
    {
        Post::lock(1, 10);
        $this->assertTrue(Post::unlock(1));
        $this->assertFalse(Post::unlock(5));
        Post::lock(1, 10);
        $post = Post::first(1);
        $post->lock = '';
        $post->save();
        $this->assertFalse(Post::unlock(1));
    }

    public function testIsLockedByMe()
    {
        $this->assertTrue(Post::lock(1, 10));
        $this->assertTrue(Post::isLockedByMe(1, 10));
        $this->assertFalse(Post::isLockedByMe(1, 15));
    }

    public function testIncreaseCounter()
    {
        $post = Post::first(1);
        $this->assertEqual(0, $post->views);
        $result = Post::increaseCounter(1);
        $this->assertEqual(1, $result);
        $post = Post::first(1);
        $this->assertEqual(1, $post->views);
    }

    public function testGetCommonTags()
    {
        $result = Post::getCommonTags();
        $expected = [
            '"заказчикам"',
            '"дизайнерам"',
            '"фриланс"',
            '"интервью"',
            '"команда go"',
            '"герой месяца"',
            '"совет в обед"',
            '"топ 10"',
            '"фриланс под пальмами"',
        ];
        $this->assertEqual($expected, $result);
    }

    public function testParseExistingTags()
    {
        $result = Post::parseExistingTags('');
        $this->assertFalse($result);
        $result = Post::parseExistingTags('заказчикам|дизайнерам');
        $expected = [
            '"заказчикам"',
            '"дизайнерам"',
        ];
        $this->assertEqual($expected, $result);
    }
}
