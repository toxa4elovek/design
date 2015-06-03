<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Logreferal;

class LogreferalTest extends AppUnit {

    public function setUp()
    {
        $this->rollUp('Logreferal');
    }

    public function tearDown()
    {
        $this->rollDown('Logreferal');
    }

    public function testGetCompletePaymentCount() {
        $this->assertEqual(0, Logreferal::getCompletePaymentCount(5));
        $this->assertEqual(1, Logreferal::getCompletePaymentCount(2));
        $this->assertEqual(2, Logreferal::getCompletePaymentCount(3));
    }

}