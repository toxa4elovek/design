<?php

namespace app\tests\cases\models;

use \app\extensions\tests\AppUnit;
use \app\models\Receipt;

class ReceiptTest extends AppUnit {

    public function setUp() {
        $this->rollUp(array('Receipt'));
    }

    public function tearDown() {
        $this->rollDown(array('Receipt'));
    }

    public function testCreateReceiptForProject(){
        $receipt = array(
            array(
                'name' => 'Оплата тарифа test',
                'value' => 1000
            ),
            array(
                'name' => 'Пополнение счёта',
                'value' => 9000
            )
        );
        $result = Receipt::createReceiptForProject(5, $receipt);
        $this->assertTrue($result);
        $receiptRows = Receipt::all(array('conditions' => array('pitch_id' => 5)));
        $this->assertEqual(2, count($receiptRows->data()));
        $result = Receipt::createReceiptForProject(5, $receipt);
        $this->assertTrue($result);
        $receiptRows = Receipt::all(array('conditions' => array('pitch_id' => 5)));
        $this->assertEqual(4, count($receiptRows->data()));
    }

    public function testUpdateOrCreateReceiptForProject() {
        $receipt = array(
            array(
                'name' => 'Оплата тарифа test',
                'value' => 1000
            ),
            array(
                'name' => 'Пополнение счёта',
                'value' => 9000
            )
        );
        $result = Receipt::updateOrCreateReceiptForProject(5, $receipt);
        $this->assertTrue($result);
        $receiptRows = Receipt::all(array('conditions' => array('pitch_id' => 5)));
        $this->assertEqual(2, count($receiptRows->data()));

        $receipt = array(
            array(
                'name' => 'Оплата тарифа test',
                'value' => 1000
            ),
            array(
                'name' => 'Пополнение счёта',
                'value' => 9000
            ),
            array(
                'name' => 'Новый пункт',
                'value' => 5000
            )
        );
        $result = Receipt::updateOrCreateReceiptForProject(5, $receipt);
        $this->assertTrue($result);
        $receiptRows = Receipt::all(array('conditions' => array('pitch_id' => 5)));
        $this->assertEqual(3, count($receiptRows->data()));

    }

    public function testExportToArray() {
        $receipt = array(
            array(
                'name' => 'Оплата тарифа test',
                'value' => 1000
            ),
            array(
                'name' => 'Пополнение счёта',
                'value' => 9000
            )
        );
        $result = Receipt::createReceiptForProject(5, $receipt);
        $this->assertTrue($result);
        $expected = $receipt;
        $result = Receipt::exportToArray(5);
        $this->assertEqual($expected, $result);
    }

    public function testGetTotalForProject() {
        $receipt = array(
            array(
                'name' => 'Оплата тарифа test',
                'value' => 1000
            ),
            array(
                'name' => 'Пополнение счёта',
                'value' => 9000
            )
        );
        $result = Receipt::createReceiptForProject(5, $receipt);
        $this->assertTrue($result);
        $result = Receipt::getTotalForProject(5);
        $this->assertEqual(10000, $result);
    }

}