<?php

namespace app\tests\cases\extensions\billing;

use app\extensions\billing\Payture;
use app\extensions\tests\AppUnit;

class PaytureTest extends AppUnit {

    public $sessionId = null;
    public $orderId = null;

    public function testInit() {
        // Новый заказ
        $amount = 1000 * 100;
        $this->orderId = $this->__generateRandomString();
        $result = Payture::init(array(
            'SessionType' => 'Pay',
            'OrderId' => $this->orderId,
            'Amount' => $amount,
            'Url' => 'http://godesigner/users/mypitches',
            'Total' => $amount,
            'Product' => 'Оплата проекта'
        ));

        $this->assertEqual('True', $result['Success']);
        $this->assertEqual($this->orderId, $result['OrderId']);
        $this->assertEqual($amount, $result['Amount']);
        $this->assertTrue(is_string($result['SessionId']));
        $this->sessionId = $result['SessionId'];
        var_dump($result['SessionId']);
        var_dump($this->orderId);
        // Повторный заказ
        $result = Payture::init(array(
            'SessionType' => 'Pay',
            'OrderId' => $this->orderId,
            'Amount' => $amount,
            'Url' => 'http://www.godesigner.ru/users/mypitches',
            'Total' => $amount,
            'Product' => 'Оплата проекта'
        ));
        $this->assertEqual('False', $result['Success']);
        $this->assertEqual('DUPLICATE_ORDER_ID', $result['ErrCode']);
    }

    public function testPay() {
        $expected = 'https://sandbox.payture.com/apim/Pay?SessionId=' . $this->sessionId;
        $result = Payture::pay($this->sessionId);
        $this->assertEqual($expected, $result);
        echo $result;
    }

/*


    public function testRefund() {
        $result = Payture::refund('LtnUTL1t7egFQdRiUWUbaARoRWG2j1Z5vn0p81Sgf9V6mNphKj', '90000');
        var_dump($result);
        $this->assertEqual('10000', $result['NewAmount']);
    }

    public function testPayStatus() {
        $this->orderId = 'LtnUTL1t7egFQdRiUWUbaARoRWG2j1Z5vn0p81Sgf9V6mNphKj';
        $result = Payture::PayStatus('313432141');
        $this->assertEqual('False', $result['Success']);
        $this->assertEqual('ORDER_NOT_FOUND', $result['ErrCode']);

        $result = Payture::PayStatus($this->orderId);
        $this->assertEqual('True', $result['Success']);
        $this->assertEqual('Charged', $result['State']);
    }
*/
    private function __generateRandomString($length = 50) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}