<?php

namespace app\tests\cases\extensions\mailers;

use app\extensions\mailers\UserMailer;
use app\extensions\tests\AppUnit;
use app\models\Grade;
use app\models\Promocode;
use app\models\User;

class UserMailerTest extends  AppUnit
{

    public function setUp()
    {
        $this->rollUp(['Grade', 'User', 'Promocode']);
    }

    public function tearDown()
    {
        $this->rollDown(['Grade', 'User', 'Promocode']);
    }

    public function testSendEmailAfterGrade()
    {
        $user = User::first(1);
        $grade = Grade::create([
            'text' => '',
            'site_rating' => 4
        ]);
        $this->assertFalse(UserMailer::sendEmailAfterGrade($grade, $user));
        $grade = Grade::create([
            'text' => '',
            'site_rating' => 3,
            'work_rating' => 4
        ]);
        $html = UserMailer::sendEmailAfterGrade($grade, $user);
        $this->assertPattern('/Здравствуйте, Алексей!/', $html);
        $this->assertPattern('/Вы завершили проект на сайте, но остались недовольны GoDesigner/', $html);
        $this->assertPattern('/Напишите, что не понравилось, и как улучшить сервис?/', $html);
        $this->assertPattern('/Мы работаем над ошибками и хотим стать лучше/', $html);

        $grade = Grade::create([
            'text' => '',
            'site_rating' => 3,
            'work_rating' => 3
        ]);
        $html = UserMailer::sendEmailAfterGrade($grade, $user);
        $this->assertPattern('/Здравствуйте, Алексей!/', $html);
        $this->assertPattern('/Очень жаль, что вы остались недовольны GoDesigner/', $html);
        $this->assertPattern('/Мы работаем над своими ошибками и в качестве компенсации дарим 25% скидку на сервисный сбор при создании нового проекта по промокоду «[\w]{4}»/', $html);
        $this->assertPattern('/Промокод действителен в течение месяца\.<br><br>/', $html);
        $this->assertPattern('/Пожалуйста, напишите, что не понравилось, и как улучшить сервис?/', $html);
        $promocode = Promocode::first(['conditions' => [
            'user_id' => $user->id,
            'data' => '25',
            'type' => 'custom_discount'
        ]]);
        $this->assertTrue(is_array($promocode->data()));

        $grade = Grade::create([
            'text' => '',
            'site_rating' => 2,
            'work_rating' => 1
        ]);
        $html = UserMailer::sendEmailAfterGrade($grade, $user);
        $this->assertPattern('/Здравствуйте, Алексей!/', $html);
        $this->assertPattern('/Очень жаль, что вы остались недовольны GoDesigner/', $html);
        $this->assertPattern('/Мы работаем над своими ошибками и в качестве компенсации дарим 50% скидку на сервисный сбор при создании нового проекта по промокоду «[\w]{4}»/', $html);
        $this->assertPattern('/Напишите нам, что не понравилось, и как улучшить сервис\?<br><br>/', $html);
        $this->assertPattern('/Промокод действителен в течение месяца\./', $html);
        $promocode = Promocode::first(['conditions' => [
            'user_id' => $user->id,
            'data' => '50',
            'type' => 'custom_discount'
        ]]);
        $this->assertTrue(is_array($promocode->data()));
    }
}
