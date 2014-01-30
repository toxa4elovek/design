<?php

namespace app\tests\cases\extensions\mailers;

use app\extensions\tests\AppUnit;
use \app\extensions\mailers\CommentsMailer;

class CommentsMailerTest extends  AppUnit {

    public function setUp() {
        $this->rollUp(array('Pitch', 'User', 'Solution', 'Category', 'Comment'));
    }

    public function tearDown() {
        $this->rollDown(array('Pitch', 'User', 'Solution', 'Category', 'Comment'));
    }

    public function testSendNewCommentFromAdminNotification() {
        $html = CommentsMailer::sendNewCommentFromAdminNotificationToUser(1, 2);
        $this->assertPattern("/GO DESIGNER ОСТАВИЛ КОММЕНТАРИЙ/", $html);
        $this->assertPattern("/ДМИТРИЙ/", $html);
        $this->assertPattern("/This is test text/", $html);
    }

    public function testSendNewCommentFromAdninNotification() {
        $count = CommentsMailer::sendNewCommentFromAdminNotification(1000);
        $this->assertIdentical(0, $count);
        $count = CommentsMailer::sendNewCommentFromAdminNotification(1);
        $this->assertIdentical(2, $count);
    }

    public function testSendNewPersonalCommentNotification() {
        $html = CommentsMailer::sendNewPersonalCommentNotification(1000);
        $this->assertFalse($html);
        $html = CommentsMailer::sendNewPersonalCommentNotification(1);
        $this->assertPattern("/ВАМ ОСТАВЛЕН НОВЫЙ КОММЕНТАРИЙ/", $html);
        $this->assertPattern("/АЛЕКСЕЙ/", $html);
        $this->assertPattern("/This is test text/", $html);
    }

}