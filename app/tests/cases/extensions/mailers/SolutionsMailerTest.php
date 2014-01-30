<?php

namespace app\tests\cases\extensions\mailers;

use app\extensions\tests\AppUnit;
use \app\extensions\mailers\SolutionsMailer;

class SolutionsMailerTest extends  AppUnit {

    public function setUp() {
        $this->rollUp(array('Pitch', 'User', 'Solution', 'Category'));
    }

    public function tearDown() {
        $this->rollUp(array('Pitch', 'User', 'Solution', 'Category'));
    }

    public function testSendNewSolutionNotification() {
        $html = SolutionsMailer::sendNewSolutionNotification(1);
        $this->assertPattern("/ДОБАВЛЕНО НОВОЕ РЕШЕНИЕ/", $html);
        $this->assertPattern("/ДМИТРИЙ/", $html);
        $this->assertPattern("/Test title/", $html);
    }

    public function testSendVictoryNotification() {
        $html = SolutionsMailer::sendVictoryNotification(1);
        $this->assertPattern("/ВАШЕ РЕШЕНИЕ СТАЛО ПОБЕДИТЕЛЕМ!/", $html);
        $this->assertPattern("/АЛЕКСЕЙ/", $html);
        $this->assertPattern("/Test title/", $html);
        $this->assertPattern("/#5/", $html);
    }

}