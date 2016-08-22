<?php

namespace app\tests\cases\extensions\mailers;

use app\extensions\tests\AppUnit;
use \app\extensions\mailers\SolutionsMailer;

class SolutionsMailerTest extends  AppUnit
{

    public function setUp()
    {
        $this->rollUp(['Pitch', 'User', 'Solution', 'Category']);
    }

    public function tearDown()
    {
        $this->rollDown(['Pitch', 'User', 'Solution', 'Category']);
    }

    public function testSendNewSolutionNotification()
    {
        $html = SolutionsMailer::sendNewSolutionNotification(1);
        $this->assertPattern("/ДОБАВЛЕНО НОВОЕ РЕШЕНИЕ/", $html);
        $this->assertPattern("/ДМИТРИЙ/", $html);
        $this->assertPattern("/TEST TITLE/", $html);
    }

    public function testSendVictoryNotification()
    {
        $html = SolutionsMailer::sendVictoryNotification(1);
        $this->assertPattern("/Ваше решение/", $html);
        $this->assertPattern("/стало победителем\./", $html);
        $this->assertPattern("/Здравствуйте, Алексей!<br>/", $html);
        $this->assertPattern("/Мы поздравляем вас!/", $html);
        $this->assertPattern("/У заказчика есть право на внесение 3 правок на/", $html);
        $this->assertPattern("/, пожалуйста, ознакомьтесь с /", $html);
        $this->assertPattern("/#5/", $html);
    }

    public function testSendSolutionBoughtNotification()
    {
        $html = SolutionsMailer::sendSolutionBoughtNotification(1);
        $this->assertPattern("/Ваше решение/", $html);
        $this->assertPattern("/Проверка названия/", $html);
        $this->assertPattern("/#5/", $html);
    }
}
