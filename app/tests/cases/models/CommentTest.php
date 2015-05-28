<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Comment;

class CommentTest extends AppUnit{

    public function setUp() {
    }

    public function tearDown() {
    }

    public function testGetPitchExpertUserIds() {
        $string = 'test';
        $this->assertTrue(Comment::checkComment($string));
        $string = 't';
        $this->assertTrue(Comment::checkComment($string));
        $string = '#1,';
        $this->assertFalse(Comment::checkComment($string));
        $string = '   ';
        $this->assertFalse(Comment::checkComment($string));
        $string = '';
        $this->assertFalse(Comment::checkComment($string));
        $string = '1';
        $this->assertTrue(Comment::checkComment($string));
    }

}