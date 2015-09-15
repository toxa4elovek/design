<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\SubscriptionPlan;

class SubscriptionPlanTest extends AppUnit {

    public function setUp()
    {
        //$this->rollUp('News');
    }

    public function tearDown()
    {
        //$this->rollDown('News');
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

}