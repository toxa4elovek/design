<?php

namespace app\tests\cases\extensions\mailers;

use app\extensions\tests\AppUnit;
use \app\extensions\mailers\PromoMailer;
use app\models\Pitch;
use app\models\User;

class PromoMailerTest extends  AppUnit {

    public function setUp() {
        $this->rollUp(array('Pitch', 'User', 'Solution', 'Category'));
    }

    public function tearDown() {
        $this->rollDown(array('Pitch', 'User', 'Solution', 'Category'));
    }

    public function testSendPromoCodeFollowUp() {
        $pitch = Pitch::first(1);
        $user = User::first(2);
        $promocode = 'afaf';
        $data['project'] = $pitch;
        $data['user'] = $user;
        $data['promocode'] = $promocode;
        $html = PromoMailer::sendPromoCodeFollowUp($data);
        $this->assertPattern("/Мы считаем/", $html);
        $this->assertPattern("/Дмитрий/", $html);
        $this->assertPattern("/Введите промокод «afaf» на первом этапе создания проекта/", $html);
        $this->assertPattern("@<a href=\"http://godesigner.ru/pitches/details/1@", $html);
        $this->assertPattern("@Проверка названия</a>@", $html);
    }

    public function testSendGoodProfitFollowUp() {
        $pitch = Pitch::first(1);
        $user = User::first(2);
        $data['project'] = $pitch;
        $data['user'] = $user;
        $html = PromoMailer::sendGoodProfitFollowUp($data);
        $this->assertPattern("@Проверка названия</a>@", $html);
        $this->assertPattern("/Дмитрий/", $html);
        $this->assertPattern("/nyudmitriy@godesigner.ru/", $html);
        $this->assertPattern("/Семь дней назад был создан проект/", $html);
    }
}