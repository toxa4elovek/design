<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\SubscriptionPlan;
use app\models\Pitch;

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
            'price' => 49000,
            'title' => 'Предпринимательский'
        );
        $this->assertEqual($expected, $result);

        $result = SubscriptionPlan::getPlan(2);
        $expected = array(
            'price' => 69000,
            'title' => 'Фирменный'
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

}