<?php

namespace app\tests\cases\extensions\mailers;

use app\extensions\tests\AppUnit;
use \app\extensions\mailers\NotificationsMailer;
use app\models\Bill;
use app\models\User;
use app\models\Pitch;
use app\models\SubscriptionPlan;

class NotificationsMailerTest extends  AppUnit
{

    public function setUp()
    {
        SubscriptionPlan::config(['connection' => 'test']);
        $this->rollUp(['Pitch', 'User', 'Solution', 'Bill']);
    }

    public function tearDown()
    {
        $this->rollDown(['Pitch', 'User', 'Solution', 'Bill']);
    }

    public function testSendFillBalanceSuccess()
    {
        $user = User::first(2);
        $user->save(null, ['validate' => false]);
        $data = [
            'id' => 8,
            'type' => 'fund-balance',
            'specifics' => 'a:2:{s:7:"plan_id";i:0;s:12:"fund_balance";i:9000;}',
            'billed' => 1
        ];
        $plan = Pitch::create($data);
        $plan->save();
        $html = NotificationsMailer::sendFillBalanceSuccess($user, $plan);
        $this->assertPattern("/Ваш счёт пополнен на сумму 9000 рублей/", $html);
        $this->assertPattern("/ЗДРАВСТВУЙТЕ ДМИТРИЙ/", $html);
        $this->assertPattern("/ВЫ УСПЕШНО ПОПОЛНИЛИ СЧЁТ./", $html);
    }

    public function testSendLongFinishNotification()
    {
        // http://cp.godesigner.ru/users/loginasadmin?query=redirect&redirect=http://godesigner.ru/users/loginasuser/30400
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
        $this->assertPattern("@Приём работ в проекте <a href=\"https://godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a>@", $html, "Не найдена строчка по регулярке: @Приём работ в проекте <a href=\"https://godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a>@");
        $this->assertPattern("/ЗДРАВСТВУЙТЕ ДМИТРИЙ/", $html);
        $this->assertPattern("/У вас есть 4 дня на выбор лучшего решения, либо на отказ от всех решений./", $html);
        $this->assertPattern("@https://www\.godesigner\.ru/answers/view/71@", $html);
    }

    public function testSendChooseWinnerNotificationForGuarantee()
    {
        $project = Pitch::first(1);
        $html = NotificationsMailer::sendChooseWinnerNotificationForGuarantee($project);
        $this->assertPattern("@Приём работ в проекте <a href=\"https://godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a>@", $html, "Не найдена строчка по регулярке: @Приём работ в проекте <a href=\"https://godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a>@");
        $this->assertPattern("/ЗДРАВСТВУЙТЕ ДМИТРИЙ/", $html);
        $this->assertPattern("/У вас есть 4 дня на выбор лучшего решения\./", $html);
        $this->assertNoPattern("@https://www\.godesigner\.ru/answers/view/71@", $html);
    }

    public function testSendPenaltyActiveReminder()
    {
        $project = Pitch::first(1);
        $html = NotificationsMailer::sendPenaltyActiveReminder($project, 1000);
        $this->assertPattern("@В проекте <a href=\"https://godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a>@", $html);
        $this->assertPattern("/ЗДРАВСТВУЙТЕ ДМИТРИЙ/", $html);
        $this->assertPattern("@Вы можете номинировать работу, перейти к завершительному этапу и вносить правки после оплаты штрафа из расчёта 25 руб\./час@", $html);
        $this->assertPattern("@https://www\.godesigner\.ru/answers/view/53@", $html);
    }

    public function testSendStartPenaltyNotification()
    {
        $project = Pitch::first(1);
        $html = NotificationsMailer::sendStartPenaltyNotification($project);
        $this->assertPattern("@Поскольку вы не приняли решение о выборе победителя, в проекте <a href=\"https://godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a> активирован штрафной период &mdash; 10 дней\.@", $html);
        $this->assertPattern("/ЗДРАВСТВУЙТЕ ДМИТРИЙ/", $html);
        $this->assertPattern("@Вы можете номинировать работу, перейти к завершительному этапу и вносить правки после оплаты штрафа из расчёта 25 руб\./час@", $html);
        $this->assertPattern("@https://www\.godesigner\.ru/answers/view/53@", $html);
    }

    public function testSendPenaltyEndsSoonReminder()
    {
        $project = Pitch::first(1);
        $project->finishDate = date(MYSQL_DATETIME_FORMAT, time() - 9 * DAY);
        $project->save();
        $html = NotificationsMailer::sendPenaltyEndsSoonReminder($project);
        $this->assertPattern(sprintf('@%s истекает штрафной период для выбора победителя\.@', date('d.m.Y H:i:s', time() + DAY)), $html);
        $this->assertPattern("@Пожалуйста, активизируйтесь на сайте и примите решение в проекте <a href=\"https://godesigner.ru/pitches/view/$project->id\">&laquo;$project->title&raquo;</a>\.@", $html);
        $this->assertPattern("/ЗДРАВСТВУЙТЕ ДМИТРИЙ/", $html);
        $this->assertPattern("@Вы можете номинировать работу, перейти к завершительному этапу и вносить правки после оплаты штрафа из расчёта 25 руб\./час@", $html);
        $this->assertPattern('/В случае вашей неактивности победитель в проекте будет назначен автоматически\. Подробнее/', $html);
        $this->assertPattern("@https://www\.godesigner\.ru/answers/view/53@", $html);
    }

    public function testSendProjectFinishedNotifications()
    {
        $project = Pitch::first(1);
        $date = date(MYSQL_DATETIME_FORMAT);
        $project->totalFinishDate = $date;
        $project->save();
        $html = NotificationsMailer::sendProjectFinishedNotifications($project, 'nyudmitriy@godesigner.ru');
        $this->assertPattern('/Необходимо связаться с заказчиком по вопросу предоставления закрывающих документов/', $html);
        $this->assertPattern('/Добрый день!/', $html);
        $this->assertPattern("/$date завершен проект «<a style=\"color: #6590a3;
text-decoration: underline;\" href=\"http:\/\/cp.godesigner.ru\/pitches\/edit\/1\">Проверка названия<\/a>»/", $html);
    }

    public function testSendNewReferalAd()
    {
        $user = User::first(1);
        $html = NotificationsMailer::sendNewReferalAd($user);
        $this->assertPattern('/Вы получите 10 000 рублей,<br>если ваш друг станет <a style="color:#6998a1; text-decoration:none; font-size: 16px; font-family: Arial, sans-serif;line-height:23px" href="https:\/\/godesigner.ru\/pages\/subscribe">абонентом GoDesigner<\/a>/', $html);
        $this->assertPattern('/<img src="https:\/\/godesigner.ru\/img\/mail\/10000\/button.png" alt="Получить код">/', $html);
        $this->assertPattern('/<img src="https:\/\/godesigner.ru\/img\/mail\/10000\/hero.png" alt="Как это работает">/', $html);
        $this->assertPattern('/<img src="https:\/\/godesigner.ru\/img\/mail\/10000\/title.png" alt="Получи 10 000 за друга!">/', $html);
    }
}
