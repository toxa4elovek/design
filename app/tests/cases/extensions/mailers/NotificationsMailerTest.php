<?php

namespace app\tests\cases\extensions\mailers;

use app\extensions\tests\AppUnit;
use \app\extensions\mailers\NotificationsMailer;
use app\models\User;
use app\models\Pitch;
use app\models\SubscriptionPlan;

class NotificationsMailerTest extends  AppUnit
{

    public function setUp()
    {
        SubscriptionPlan::config(array('connection' => 'test'));
        $this->rollUp(array('Pitch', 'User', 'Solution'));
    }

    public function tearDown()
    {
        $this->rollDown(array('Pitch', 'User', 'Solution'));
    }

    public function testSendFillBalanceSuccess()
    {
        $user = User::first(2);
        $user->save(null, array('validate' => false));
        $data = array(
            'id' => 8,
            'type' => 'fund-balance',
            'specifics' => 'a:2:{s:7:"plan_id";i:0;s:12:"fund_balance";i:9000;}',
            'billed' => 1
        );
        $plan = Pitch::create($data);
        $plan->save();
        $html = NotificationsMailer::sendFillBalanceSuccess($user, $plan);
        $this->assertPattern("/Ваш счёт пополнен на сумму 9000 рублей/", $html);
        $this->assertPattern("/ЗДРАВСТВУЙТЕ ДМИТРИЙ/", $html);
        $this->assertPattern("/ВЫ УСПЕШНО ПОПОЛНИЛИ СЧЁТ./", $html);
    }

    public function testSendLongFinishNotification()
    {
        // http://cp.godesigner.ru/users/loginasadmin?query=redirect&redirect=http://www.godesigner.ru/users/loginasuser/30400
        $user = User::first(2);
        $project = Pitch::first(7);
        $project->status = 2;
        $project->category_id = 1;
        $project->awardedDate = date('Y-m-d H:i:s', time() - DAY);
        $project->awarded = 9;
        $project->save();
        $html = NotificationsMailer::sendLongFinishNotification($user, $project);
        $this->assertPattern("/Завершение проекта затянулось/", $html);
        $this->assertPattern("@https://cp\.godesigner\.ru/users/loginasadmin\?query=redirect&redirect=https://www\.godesigner\.ru/users/step2/9@", $html);
    }

    public function testSendSubscriberChooseWinnerWarning()
    {
        $project = Pitch::first(1);
        $project->category_id = 20;
        $endChooseWinnerPeriod = time() + DAY;
        $project->chooseWinnerFinishDate = date(MYSQL_DATETIME_FORMAT, time() + DAY);
        $project->save();
        $html = NotificationsMailer::sendSubscriberChooseWinnerWarning($project);
        $expectedDateTimeString = date('H:i:s d.m.y', $endChooseWinnerPeriod);
        $this->assertPattern("/Проект завершается в $expectedDateTimeString/", $html);
        $this->assertPattern("/ЗДРАВСТВУЙТЕ ДМИТРИЙ/", $html);
        $this->assertPattern("/Пожалуйста, активизируйтесь на сайте и выберите победителя/", $html);
        $this->assertPattern("@https://www\.godesigner\.ru/answers/view/102@", $html);
    }

    public function testSendChooseWinnerNotificationForNonGuarantee()
    {
        $project = Pitch::first(1);
        $html = NotificationsMailer::sendChooseWinnerNotificationForNonGuarantee($project);
        $this->assertPattern("@Приём работ в проекте <a href=\"https://www.godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a>@", $html, "Не найдена строчка по регулярке: @Приём работ в проекте <a href=\"https://www.godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a>@");
        $this->assertPattern("/ЗДРАВСТВУЙТЕ ДМИТРИЙ/", $html);
        $this->assertPattern("/У вас есть 4 дня на выбор лучшего решения, либо на отказ от всех решений./", $html);
        $this->assertPattern("@https://www\.godesigner\.ru/answers/view/71@", $html);
    }

    public function testSendChooseWinnerNotificationForGuarantee()
    {
        $project = Pitch::first(1);
        $html = NotificationsMailer::sendChooseWinnerNotificationForGuarantee($project);
        $this->assertPattern("@Приём работ в проекте <a href=\"https://www.godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a>@", $html, "Не найдена строчка по регулярке: @Приём работ в проекте <a href=\"https://www.godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a>@");
        $this->assertPattern("/ЗДРАВСТВУЙТЕ ДМИТРИЙ/", $html);
        $this->assertPattern("/У вас есть 4 дня на выбор лучшего решения\./", $html);
        $this->assertNoPattern("@https://www\.godesigner\.ru/answers/view/71@", $html);
    }

    public function testSendPenaltyActiveReminder()
    {
        $project = Pitch::first(1);
        $html = NotificationsMailer::sendPenaltyActiveReminder($project, 1000);
        $this->assertPattern("@В проекте <a href=\"https://www.godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a>@", $html);
        $this->assertPattern("/ЗДРАВСТВУЙТЕ ДМИТРИЙ/", $html);
        $this->assertPattern("@Вы можете номинировать работу, перейти к завершительному этапу и вносить правки после оплаты штрафа из расчёта 25 руб\./час@", $html);
        $this->assertPattern("@https://www\.godesigner\.ru/answers/view/53@", $html);
    }

    public function testSendStartPenaltyNotification()
    {
        $project = Pitch::first(1);
        $html = NotificationsMailer::sendStartPenaltyNotification($project);
        $this->assertPattern("@Поскольку вы не приняли решение о выборе победителя, в проекте <a href=\"https://www.godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a> активирован штрафной период &mdash; 10 дней\.@", $html);
        $this->assertPattern("/ЗДРАВСТВУЙТЕ ДМИТРИЙ/", $html);
        $this->assertPattern("@Вы можете номинировать работу, перейти к завершительному этапу и вносить правки после оплаты штрафа из расчёта 25 руб\./час@", $html);
        $this->assertPattern("@https://www\.godesigner\.ru/answers/view/53@", $html);
    }
}
