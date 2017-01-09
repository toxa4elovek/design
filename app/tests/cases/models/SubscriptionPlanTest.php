<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\SubscriptionPlan;
use app\models\Task;
use app\models\User;

class SubscriptionPlanTest extends AppUnit
{

    public function setUp()
    {
        SubscriptionPlan::config(['connection' => 'test']);
        $this->rollUp(['Pitch', 'User', 'Task']);
    }

    public function tearDown()
    {
        $this->rollDown(['Pitch', 'User', 'Task']);
    }

    public function testGetPlan()
    {
        $result = SubscriptionPlan::getPlan(1);
        $expected = [
            'id' => 1,
            'price' => 49000,
            'title' => 'Предпринимательский',
            'duration' => YEAR,
            'free' => [],
        ];
        $this->assertEqual($expected, $result);

        $result = SubscriptionPlan::getPlan(2);
        $expected = [
            'id' => 2,
            'price' => 69000,
            'title' => 'Фирменный',
            'duration' => YEAR,
            'free' => ['chooseWinnerFinishDate', 'hideproject'],
        ];
        $this->assertEqual($expected, $result);

        $result = SubscriptionPlan::getPlan(9999);
        $expected = null;
        $this->assertEqual($expected, $result);
    }

    public function testGetNextSubscriptionPlanId()
    {
        $result = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $expected = 8;
        $this->assertEqual($expected, $result);
        $result = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $expected = 8;
        $this->assertEqual($expected, $result);
        $payment = SubscriptionPlan::first(8);
        $payment->billed = 1;
        $payment->save();
        $result = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $expected = 9;
        $this->assertEqual($expected, $result);
    }

    public function testGetNextSubscriptionPlanIdByGAId()
    {
        $result = SubscriptionPlan::getNextSubscriptionPlanIdByGAId('ga-id', '1234');
        $expected = 8;
        $this->assertEqual($expected, $result);
        $result = SubscriptionPlan::getNextSubscriptionPlanIdByGAId('ga-id', '1234');
        $expected = 8;
        $this->assertEqual($expected, $result);
        $payment = SubscriptionPlan::first(8);
        $this->assertEqual(0, $payment->user_id);
        $this->assertEqual('ga-id', $payment->ga_id);
        $this->assertEqual('1234', $payment->promocode);
        $payment->billed = 1;
        $payment->save();
        $result = SubscriptionPlan::getNextSubscriptionPlanIdByGAId('ga-id', '4321');
        $expected = 9;
        $this->assertEqual($expected, $result);
        $payment = SubscriptionPlan::first(9);
        $this->assertEqual(0, $payment->user_id);
        $this->assertEqual('ga-id', $payment->ga_id);
        $this->assertEqual('4321', $payment->promocode);
    }

    public function testHasSubscriptionPlanDraft()
    {
        $result = SubscriptionPlan::hasSubscriptionPlanDraft(1);
        $this->assertFalse($result);
        SubscriptionPlan::getNextSubscriptionPlanId(1);
        $result = SubscriptionPlan::hasSubscriptionPlanDraft(1);
        $this->assertTrue($result);
    }

    public function testGetNextFundBalanceId()
    {
        $result = SubscriptionPlan::getNextFundBalanceId(1);
        $expected = 8;
        $this->assertEqual($expected, $result);
        $result = SubscriptionPlan::getNextFundBalanceId(1);
        $expected = 8;
        $this->assertEqual($expected, $result);
        $payment = SubscriptionPlan::first(8);
        $payment->billed = 1;
        $payment->save();
        $result = SubscriptionPlan::getNextFundBalanceId(1);
        $expected = 9;
        $this->assertEqual($expected, $result);
    }

    public function testSetTotal()
    {
        $result = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $expected = 8;
        $this->assertEqual($expected, $result);
        $plan = SubscriptionPlan::first(8);
        $this->assertTrue('0.00', $plan->total);
        $result = $plan->setTotal(10000);
        $this->assertTrue($result);
        $plan = SubscriptionPlan::first(8);
        $this->assertEqual(10000.00, $plan->total);
        $this->assertEqual(10000.00, $plan->price);
    }

    public function testSetTotalOfPaymentForRecord()
    {
        $result = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $expected = 8;
        $this->assertEqual($expected, $result);
        $plan = SubscriptionPlan::first(8);
        $this->assertTrue('0.00', $plan->total);
        $result = $plan->setTotalOfPaymentForRecord(10000);
        $this->assertTrue($result);
        $this->assertEqual(10000.00, $plan->total);
        $this->assertEqual(10000.00, $plan->price);
        $plan = SubscriptionPlan::first(8);
        $this->assertEqual(10000.00, $plan->total);
        $this->assertEqual(10000.00, $plan->price);
    }

    public function testSetTotalOfPayment()
    {
        $result = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $expected = 8;
        $this->assertEqual($expected, $result);
        $plan = SubscriptionPlan::first(8);
        $this->assertTrue('0.00', $plan->total);
        $result = SubscriptionPlan::setTotalOfPayment(8, 10000);
        $this->assertTrue($result);
        $plan = SubscriptionPlan::first(8);
        $this->assertEqual(10000.00, $plan->total);
        $this->assertEqual(10000.00, $plan->price);
    }

    public function testSetPlanForPayment()
    {
        $result = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $expected = 8;
        $this->assertEqual($expected, $result);
        $result = SubscriptionPlan::setPlanForPayment(8, 2);
        $this->assertTrue($result);
        $plan = SubscriptionPlan::first(8);
        $this->assertEqual(['plan_id' => '2'], unserialize($plan->specifics));

        $result = SubscriptionPlan::setPlanForPayment(8, 1);
        $this->assertTrue($result);
        $plan = SubscriptionPlan::first(8);
        $this->assertEqual(['plan_id' => '1'], unserialize($plan->specifics));

        $result = SubscriptionPlan::setPlanForPayment(99, 1);
        $this->assertFalse($result);
    }

    public function testSetFundBalanceForPayment()
    {
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $result = SubscriptionPlan::setFundBalanceForPayment($id, 9000);
        $this->assertTrue($result);
        $plan = SubscriptionPlan::first($id);
        $this->assertEqual(['fund_balance' => 9000], unserialize($plan->specifics));
        // не существующий план
        $this->assertFalse(SubscriptionPlan::setFundBalanceForPayment(999999, 9000));
    }

    public function testGetFundBalanceForPaymentForRecord()
    {
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        SubscriptionPlan::setFundBalanceForPayment($id, 15000);
        $planRecord = SubscriptionPlan::first($id);
        $result = $planRecord->getFundBalanceForPaymentForRecord();
        $this->assertEqual(15000, $result);

        // пустое значение
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $plan = SubscriptionPlan::first($id);
        $plan->specifics = '';
        $plan->save();
        $this->assertNull($planRecord->getFundBalanceForPaymentForRecord($id));

        // не существующий план
        $this->assertNull($planRecord->getFundBalanceForPaymentForRecord(999999, 9000));
    }

    public function testGetFundBalanceForPayment()
    {
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        SubscriptionPlan::setFundBalanceForPayment($id, 15000);
        $result = SubscriptionPlan::getFundBalanceForPayment(8);
        $this->assertEqual(15000, $result);

        // пустое значение
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $plan = SubscriptionPlan::first($id);
        $plan->specifics = '';
        $plan->save();
        $this->assertNull(SubscriptionPlan::getFundBalanceForPayment($id));

        // не существующий план
        $this->assertNull(SubscriptionPlan::getFundBalanceForPayment(999999, 9000));
    }

    public function testGetPlanForPaymentForRecord()
    {
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        SubscriptionPlan::setPlanForPayment($id, 2);
        $plan = SubscriptionPlan::first($id);
        $result = $plan->getPlanForPaymentForRecord();
        $this->assertEqual(2, $result);

        // сериализованных данных просто нет
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $plan = SubscriptionPlan::first($id);
        $plan->specifics = '';
        $plan->save();
        $this->assertNull($plan->getPlanForPaymentForRecord());

        // сериалозованные данные есть, но нужного ключа в них нет
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $plan = SubscriptionPlan::first($id);
        $plan->specifics = serialize(['noInfo' => true]);
        $plan->save();
        $this->assertNull($plan->getPlanForPaymentForRecord());
    }

    public function testGetPlanForPayment()
    {
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        SubscriptionPlan::setPlanForPayment($id, 2);
        $result = SubscriptionPlan::getPlanForPayment(8);
        $this->assertEqual(2, $result);

        // сериализованных данных просто нет
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $plan = SubscriptionPlan::first($id);
        $plan->specifics = '';
        $plan->save();
        $this->assertNull(SubscriptionPlan::getPlanForPayment($id));

        // сериалозованные данные есть, но нужного ключа в них нет
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $plan = SubscriptionPlan::first($id);
        $plan->specifics = serialize(['noInfo' => true]);
        $plan->save();
        $this->assertNull(SubscriptionPlan::getPlanForPayment($id));

        // не существующий план
        $this->assertNull(SubscriptionPlan::getPlanForPayment(999999));
    }

    public function testActivatePlan()
    {
        // не существующий план
        $this->assertFalse(SubscriptionPlan::activatePlanPayment(9999));

        // только план
        $user = User::first(1);
        $user->companydata = serialize([
            'company_name' => 'Полное название компании',
        ]);
        $user->short_company_name = 'Краткое Наз';
        $user->save(null, ['validate' => false]);
        $this->assertFalse(User::isSubscriptionActive(1));
        $this->assertEqual(0, User::getBalance(1));
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        SubscriptionPlan::setPlanForPayment($id, 2);
        $result = SubscriptionPlan::activatePlanPayment($id);
        $plan = SubscriptionPlan::first($id);
        $this->assertTrue($result);
        $this->assertTrue(User::isSubscriptionActive(1));
        $this->assertEqual(0, User::getBalance(1));
        $this->assertEqual('Оплата абонентского обслуживания (Полное название компании)', $plan->title);
        $this->assertNull(Task::first(['conditions' =>
            [
            'type' => 'emailFillBalanceSuccessNotification',
            'model_id' => $id
            ]
        ]));

        // только баланс
        $user = User::first(2);
        $user->short_company_name = 'Краткое Наз';
        $user->save(null, ['validate' => false]);

        $this->assertFalse(User::isSubscriptionActive(2));
        $this->assertEqual(0, User::getBalance(2));
        $id = SubscriptionPlan::getNextSubscriptionPlanId(2);
        SubscriptionPlan::setPlanForPayment($id, 0);
        SubscriptionPlan::setFundBalanceForPayment($id, 15000);
        SubscriptionPlan::setTotalOfPayment($id, 15000);
        $result = SubscriptionPlan::activatePlanPayment($id);
        $plan = SubscriptionPlan::first($id);
        $this->assertTrue($result);
        $this->assertFalse(User::isSubscriptionActive(2));
        $this->assertEqual(15000, User::getBalance(2));
        $this->assertEqual('Оплата абонентского обслуживания (Краткое Наз)', $plan->title);
        $task = Task::first(['conditions' =>
            [
                'type' => 'emailFillBalanceSuccessNotification',
                'model_id' => $id
            ]
        ]);
        $this->assertTrue(is_object($task));
        $this->assertEqual('lithium\data\entity\Record', get_class($task));
    }

    public function testActivatePlanComplex()
    {
        // и план и баланс
        $this->assertFalse(User::isSubscriptionActive(1));
        $this->assertEqual(0, User::getBalance(1));
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        SubscriptionPlan::setPlanForPayment($id, 2);
        SubscriptionPlan::setFundBalanceForPayment($id, 15000);
        $result = SubscriptionPlan::activatePlanPayment($id);
        $this->assertTrue($result);
        $this->assertTrue(User::isSubscriptionActive(1));
        $this->assertEqual(15000, User::getBalance(1));
        $activatedPlan = SubscriptionPlan::first($id);
        $this->assertEqual(date('Y-m-d H:i:s'), $activatedPlan->totalFinishDate);
        $this->assertEqual(date('Y-m-d H:i:s'), $activatedPlan->started);
        $task = Task::first(['conditions' =>
            [
                'type' => 'emailFillBalanceSuccessNotification',
                'model_id' => $id
            ]
        ]);
        $this->assertTrue(is_object($task));
        $this->assertEqual('lithium\data\entity\Record', get_class($task));
    }

    public function testExtractFundBalanceAmount()
    {
        $id = SubscriptionPlan::getNextSubscriptionPlanId(2);
        SubscriptionPlan::setFundBalanceForPayment($id, 15000);
        $result = SubscriptionPlan::extractFundBalanceAmount($id);
        $this->assertEqual(15000, $result);

        // сериализованный данных просто нет
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $plan = SubscriptionPlan::first($id);
        $plan->specifics = '';
        $plan->save();
        $this->assertIdentical(0, SubscriptionPlan::extractFundBalanceAmount($id));

        // сериалозованные данные есть, но нужного ключа в них нет
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $plan = SubscriptionPlan::first($id);
        $plan->specifics = serialize(['noInfo' => true]);
        $plan->save();
        $this->assertIdentical(0, SubscriptionPlan::extractFundBalanceAmount($id));

        // не существующий план
        $result = SubscriptionPlan::extractFundBalanceAmount(99999);
        $this->assertIdentical(0, $result);
    }
}
