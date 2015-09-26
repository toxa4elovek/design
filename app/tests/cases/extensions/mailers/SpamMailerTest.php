<?php

namespace app\tests\cases\extensions\mailers;

use app\extensions\tests\AppUnit;
use \app\extensions\mailers\SpamMailer;
use app\models\Pitch;
use app\models\User;

class SpamMailerTest extends  AppUnit {

    public function setUp() {
        $this->rollUp(array('Pitch', 'User', 'Solution', 'Category'));
    }

    public function tearDown() {
        $this->rollDown(array('Pitch', 'User', 'Solution', 'Category'));
    }

    public function testNewlogosaleproject() {
        $pitch = Pitch::first(1);
        $user = User::first(2);
        $data['pitch'] = $pitch;
        $data['user'] = $user;
        $html = SpamMailer::newlogosaleproject($data);
        $this->assertPattern("/ЛОГОТИП КУПИЛИ НА РАСПРОДАЖЕ/", $html);
        $this->assertPattern("/ДМИТРИЙ/", $html);
        $this->assertPattern("@http://cp.godesigner.ru/pitches/edit/1@", $html);
    }

    public function testSendNewLogosaleProject() {
        $pitch = Pitch::first(1);
        $result = SpamMailer::SendNewLogosaleProject($pitch);
        $this->assertEqual(array(), $result);
    }
}