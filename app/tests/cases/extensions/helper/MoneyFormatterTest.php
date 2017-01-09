<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\tests\AppUnit;
use app\extensions\helper\MoneyFormatter;

class MoneyFormatterTest extends AppUnit
{

    /**
     * Test object instance.
     *
     * @var object
     */
    public $money = null;

    /**
     * Initialize test by creating a new object instance with a default context.
     */
    public function setUp()
    {
        $this->money = new MoneyFormatter();
    }

    public function tearDown()
    {
    }

    public function testApplyDiscount()
    {
        $result = $this->money->applyDiscount(10000, 50);
        $this->assertEqual(5000, $result);

        $result = $this->money->applyDiscount(10000, 25);
        $this->assertEqual(7500, $result);

        $result = $this->money->applyDiscount(49000, 10);
        $this->assertEqual(44100, $result);
    }
}
