<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\SubscriptionPlan;
use app\models\Pitch;
use app\models\User;

class SubscriptionPlanTest extends AppUnit {

    public function setUp()
    {
        SubscriptionPlan::config(array('connection' => 'test'));
        $this->rollUp(array('Pitch', 'User'));
    }

    public function tearDown()
    {
        $this->rollDown(array('Pitch', 'User'));
    }

    public function testGetPlan() {
        $result = SubscriptionPlan::getPlan(1);
        $expected = array(
            'id' => 1,
            'price' => 49000,
            'title' => 'Предпринимательский',
            'duration' => YEAR
        );
        $this->assertEqual($expected, $result);

        $result = SubscriptionPlan::getPlan(2);
        $expected = array(
            'id' => 2,
            'price' => 69000,
            'title' => 'Фирменный',
            'duration' => YEAR
        );
        $this->assertEqual($expected, $result);

        $result = SubscriptionPlan::getPlan(9999);
        $expected = null;
        $this->assertEqual($expected, $result);
    }

    public function testGetNextSubscriptionPlanId() {
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

    public function testGetNextFundBalanceId() {
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

    public function testSetTotal() {
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

    public function testSetTotalOfPayment() {
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

    public function testSetPlanForPayment() {
        $result = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $expected = 8;
        $this->assertEqual($expected, $result);
        $result = SubscriptionPlan::setPlanForPayment(8, 2);
        $this->assertTrue($result);
        $plan = SubscriptionPlan::first(8);
        $this->assertEqual(array('plan_id' => '2'), unserialize($plan->specifics));

        $result = SubscriptionPlan::setPlanForPayment(8, 1);
        $this->assertTrue($result);
        $plan = SubscriptionPlan::first(8);
        $this->assertEqual(array('plan_id' => '1'), unserialize($plan->specifics));

        $result = SubscriptionPlan::setPlanForPayment(99, 1);
        $this->assertFalse($result);
    }

    public function testSetFundBalanceForPayment() {
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        $result = SubscriptionPlan::setFundBalanceForPayment($id, 9000);
        $this->assertTrue($result);
        $plan = SubscriptionPlan::first($id);
        $this->assertEqual(array('fund_balance' => 9000), unserialize($plan->specifics));
    }

    public function testGetFundBalanceForPayment() {
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        SubscriptionPlan::setFundBalanceForPayment($id, 15000);
        $result = SubscriptionPlan::getFundBalanceForPayment(8);
        $this->assertEqual(15000, $result);
    }

    public function testGetPlanForPayment() {
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        SubscriptionPlan::setPlanForPayment($id, 2);
        $result = SubscriptionPlan::getPlanForPayment(8);
        $this->assertEqual(2, $result);
    }

    public function testActivatePlan() {
        // только план
        $this->assertFalse(User::isSubscriptionActive(1));
        $this->assertEqual(0, User::getBalance(1));
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        SubscriptionPlan::setPlanForPayment($id, 2);
        $result = SubscriptionPlan::activatePlanPayment($id);
        $this->assertTrue($result);
        $this->assertTrue(User::isSubscriptionActive(1));
        $this->assertEqual(0, User::getBalance(1));
        // только баланс

        $this->assertFalse(User::isSubscriptionActive(2));
        $this->assertEqual(0, User::getBalance(2));
        $id = SubscriptionPlan::getNextSubscriptionPlanId(2);
        SubscriptionPlan::setPlanForPayment($id, 0);
        SubscriptionPlan::setFundBalanceForPayment($id, 15000);
        $result = SubscriptionPlan::activatePlanPayment($id);
        $this->assertTrue($result);
        $this->assertFalse(User::isSubscriptionActive(2));
        $this->assertEqual(15000, User::getBalance(2));
    }

    public function testActivatePlanComplex() {
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
    }

}