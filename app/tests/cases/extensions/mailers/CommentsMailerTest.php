<?php

namespace app\tests\cases\extensions\mailers;

use app\extensions\mailers\CommentsMailer;
use app\extensions\tests\AppUnit;
use app\models\Comment;
use app\models\Pitch;
use app\models\User;

class CommentsMailerTest extends AppUnit
{

    public $models = ['Pitch', 'User', 'Solution', 'Category', 'Comment'];

    public function setUp()
    {
        $this->rollUp($this->models);
    }

    public function tearDown()
    {
        $this->rollDown($this->models);
    }

    public function testSendNewCommentFromAdminNotificationToUser()
    {
        $userRecord = User::first(2);
        $commentRecord = Comment::first(1);
        $projectRecord = Pitch::first(1);
        $html = CommentsMailer::sendNewCommentFromAdminNotificationToUser($commentRecord, $userRecord, $projectRecord);
        $this->assertPattern("/GoDesigner оставил комментарий/", $html);
        $this->assertPattern("/Дмитрий/", $html);
        $this->assertPattern("/This is test text/", $html);
    }

    public function testSendNewCommentFromAdminNotification()
    {
        $count = CommentsMailer::sendNewCommentFromAdminNotification(1000);
        $this->assertIdentical(0, $count);
        $count = CommentsMailer::sendNewCommentFromAdminNotification(1);
        $this->assertIdentical(2, $count);
        $count = CommentsMailer::sendNewCommentFromAdminNotification(5);
        $this->assertIdentical(1, $count);
    }

    public function testSendNewPersonalCommentNotification()
    {
        $html = CommentsMailer::sendNewPersonalCommentNotification(1000);
        $this->assertFalse($html);
        $html = CommentsMailer::sendNewPersonalCommentNotification(1);
        $this->assertPattern("/ВАМ ОСТАВЛЕН НОВЫЙ КОММЕНТАРИЙ/", $html);
        $this->assertPattern("/АЛЕКСЕЙ/", $html);
        $this->assertPattern("/This is test text/", $html);
    }
}
