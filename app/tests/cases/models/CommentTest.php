<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Comment;

class CommentTest extends AppUnit
{

    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    public function testGetPitchExpertUserIds()
    {
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

    public function testWinnerSelectionCommentForClient()
    {
        $message = '@Дмитрий Н., срок проекта подошел к концу! Дизайнеры больше не могут предлагать решения и оставлять комментарии! Настал момент анонсировать победителя. У вас есть 4 дня на выбор лучшего решения. Выбрав лучшее, вы получите возможность внесения поправок и время на получение исходников.';
        $this->assertEqual($message, Comment::getWinnerSelectionCommentForClient('Дмитрий Н.', 4));

        $message = '@Михаил С., срок проекта подошел к концу! Дизайнеры больше не могут предлагать решения и оставлять комментарии! Настал момент анонсировать победителя. У вас есть 1 день на выбор лучшего решения. Выбрав лучшее, вы получите возможность внесения поправок и время на получение исходников.';
        $this->assertEqual($message, Comment::getWinnerSelectionCommentForClient('Михаил С.', 1));

        $message = '@Nata M., срок проекта подошел к концу! Дизайнеры больше не могут предлагать решения и оставлять комментарии! Настал момент анонсировать победителя. У вас есть 5 дней на выбор лучшего решения. Выбрав лучшее, вы получите возможность внесения поправок и время на получение исходников.';
        $this->assertEqual($message, Comment::getWinnerSelectionCommentForClient('Nata M.', 5));
    }
}
