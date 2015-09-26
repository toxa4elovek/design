<?php

namespace app\tests\cases\extensions\mailers;

use app\extensions\tests\AppUnit;
use \app\extensions\mailers\SolutionsMailer;

class SolutionsMailerTest extends  AppUnit {

    public function setUp() {
        $this->rollUp(array('Pitch', 'User', 'Solution', 'Category'));
    }

    public function tearDown() {
        $this->rollDown(array('Pitch', 'User', 'Solution', 'Category'));
    }

    public function testSendNewSolutionNotification() {
        $html = SolutionsMailer::sendNewSolutionNotification(1);
        $this->assertPattern("/ДОБАВЛЕНО НОВОЕ РЕШЕНИЕ/", $html);
        $this->assertPattern("/ДМИТРИЙ/", $html);
        $this->assertPattern("/TEST TITLE/", $html);
    }

    public function testSendVictoryNotification() {
        $html = SolutionsMailer::sendVictoryNotification(1);
        $this->assertPattern("/ВАШЕ РЕШЕНИЕ СТАЛО ПОБЕДИТЕЛЕМ!/", $html);
        $this->assertPattern("/АЛЕКСЕЙ/", $html);
        $this->assertPattern("/TEST TITLE/", $html);
        $this->assertPattern("/#5/", $html);
    }

    public function testSendSolutionBoughtNotification() {
        $html = SolutionsMailer::sendSolutionBoughtNotification(1);
        $this->assertPattern("/Ваше решение/", $html);
        $this->assertPattern("/Проверка названия/", $html);
        $this->assertPattern("/#5/", $html);
    }

}