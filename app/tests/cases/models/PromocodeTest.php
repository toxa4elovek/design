<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Promocode;
use lithium\storage\Session;

class PromocodeTest extends AppUnit
{

    public function setUp()
    {
        $this->rollUp(['Promocode']);
    }

    public function tearDown()
    {
        $this->rollDown(['Promocode']);
    }

    public function testGetOldPromocodes()
    {
        $promocodes = Promocode::getOldPromocodes();
        $this->assertEqual(1, count($promocodes));
        $first = $promocodes->first();
        $this->assertEqual(3, $first->id);
    }

    public function testGenerateToken()
    {
        $length = 5;
        $token = Promocode::generateToken($length);
        $this->assertTrue(is_string($token));
        $this->assertIdentical($length, strlen($token));
    }

    public function testCreatePromocode()
    {
        $userId = 2;
        $token = Promocode::createPromocode($userId);
        $this->assertTrue(is_string($token));
        $this->assertIdentical(4, strlen($token));
        $promocode = Promocode::first(['conditions' => ['code' => $token]]);
        $this->assertEqual($userId, $promocode->user_id);
        $this->assertEqual($token, $promocode->code);
        $this->assertEqual(date('Y-m-d H:i:s', time() + (2 * MONTH)), $promocode->expires);
        $this->assertEqual(date('Y-m-d H:i:s'), $promocode->starts);
    }

    public function testCheckPromocode()
    {
        $this->assertIdentical('false', Promocode::checkPromocode('0000'));
        $this->assertEqual(Promocode::first(1)->data(), Promocode::checkPromocode('aaaa'));
        $this->assertEqual(Promocode::first(2)->data(), Promocode::checkPromocode('bbbb'));
        $this->assertIdentical('false', Promocode::checkPromocode('cccc'));
        $this->assertIdentical('false', Promocode::checkPromocode('dddd'));
        $this->assertEqual(Promocode::first(5)->data(), Promocode::checkPromocode('eeee'));
        $promocode = Promocode::first(5);
        $this->assertEqual($promocode->pitch_id, null);
        $this->assertEqual($promocode->user_id, null);
        Session::write('user.id', 11);
        $result = Promocode::checkPromocode('gggg');
        $promocode = Promocode::first(7);
        $this->assertEqual($result, $promocode->data());
        $this->assertEqual($promocode->user_id, 11);
    }

    public function testIsMultiUse()
    {
        $code = Promocode::first(1);
        $this->assertTrue($code->isMultiUse());
        $code = Promocode::first(2);
        $this->assertTrue($code->isMultiUse());
        $code = Promocode::first(3);
        $this->assertFalse($code->isMultiUse());
        $code = Promocode::first(5);
        $this->assertTrue($code->isMultiUse());
    }
}
