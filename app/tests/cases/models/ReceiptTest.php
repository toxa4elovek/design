<?php

namespace app\tests\cases\models;

use \app\extensions\tests\AppUnit;
use \app\models\Receipt;

class ReceiptTest extends AppUnit
{

    public function setUp()
    {
        $this->rollUp(['Receipt', 'Expert', 'Category', 'Promocode']);
    }

    public function tearDown()
    {
        $this->rollDown(['Receipt', 'Expert', 'Category', 'Promocode']);
    }

    public function testFindOutFee()
    {

        // простые подсчеты
        $data = [
            'features' => [
                'award' => 10000
            ],
            'commonPitchData' => ['category_id' => 1]
        ];
        $result = Receipt::findOutFeeModifier($data);
        $this->assertEqual(FEE_LOW, $result);

        $data = [
            'features' => [
                'award' => 19000
            ],
            'commonPitchData' => ['category_id' => 1]
        ];
        $result = Receipt::findOutFeeModifier($data);
        $this->assertEqual(FEE_NORMAL, $result);

        $data = [
            'features' => [
                'award' => 50000
            ],
            'commonPitchData' => ['category_id' => 1]
        ];
        $result = Receipt::findOutFeeModifier($data);
        $this->assertEqual(FEE_GOOD, $result);

        // категория 3
        $data = [
            'features' => [
                'award' => 12000
            ],
            'commonPitchData' => ['category_id' => 3],
            'specificPitchData' => ['sub-site' => 1]
        ];
        $result = Receipt::findOutFeeModifier($data);
        $this->assertEqual(FEE_LOW, $result);

        $data = [
            'features' => [
                'award' => 52000
            ],
            'commonPitchData' => ['category_id' => 3],
            'specificPitchData' => ['sub-site' => 1]
        ];
        $result = Receipt::findOutFeeModifier($data);
        $this->assertEqual(FEE_GOOD, $result);

        $data = [
            'features' => [
                'award' => 52000
            ],
            'commonPitchData' => ['category_id' => 3],
            'specificPitchData' => ['site-sub' => 20]
        ];
        $result = Receipt::findOutFeeModifier($data);
        $this->assertEqual(FEE_LOW, $result);
    }

    public function testCreateReceiptForProject()
    {
        $this->assertFalse(Receipt::createReceiptForProject(5, 'non array'));
        $receipt = [
            [
                'name' => 'Оплата тарифа test',
                'value' => 1000
            ],
            [
                'name' => 'Пополнение счёта',
                'value' => 9000
            ]
        ];
        $result = Receipt::createReceiptForProject(5, $receipt);
        $this->assertTrue($result);
        $receiptRows = Receipt::all(['conditions' => ['pitch_id' => 5]]);
        $this->assertEqual(2, count($receiptRows->data()));
        $result = Receipt::createReceiptForProject(5, $receipt);
        $this->assertTrue($result);
        $receiptRows = Receipt::all(['conditions' => ['pitch_id' => 5]]);
        $this->assertEqual(4, count($receiptRows->data()));
    }

    public function testUpdateOrCreateReceiptForProject()
    {
        $this->assertFalse(Receipt::updateOrCreateReceiptForProject(5, 'string'));

        $receipt = [
            [
                'name' => 'Оплата тарифа test',
                'value' => 1000
            ],
            [
                'name' => 'Пополнение счёта',
                'value' => 9000
            ]
        ];
        $result = Receipt::updateOrCreateReceiptForProject(5, $receipt);
        $this->assertTrue($result);
        $receiptRows = Receipt::all(['conditions' => ['pitch_id' => 5]]);
        $this->assertEqual(2, count($receiptRows->data()));

        $receipt = [
            [
                'name' => 'Оплата тарифа test',
                'value' => 1000
            ],
            [
                'name' => 'Пополнение счёта',
                'value' => 9000
            ],
            [
                'name' => 'Новый пункт',
                'value' => 5000
            ]
        ];
        $result = Receipt::updateOrCreateReceiptForProject(5, $receipt);
        $this->assertTrue($result);
        $receiptRows = Receipt::all(['conditions' => ['pitch_id' => 5]]);
        $this->assertEqual(3, count($receiptRows->data()));
    }

    public function testExportToArray()
    {
        $receipt = [
            [
                'name' => 'Оплата тарифа test',
                'value' => 1000
            ],
            [
                'name' => 'Пополнение счёта',
                'value' => 9000
            ]
        ];
        $result = Receipt::createReceiptForProject(5, $receipt);
        $this->assertTrue($result);
        $expected = $receipt;
        $result = Receipt::exportToArray(5);
        $this->assertEqual($expected, $result);
    }

    public function testGetTotalForProject()
    {
        $receipt = [
            [
                'name' => 'Оплата тарифа test',
                'value' => 1000
            ],
            [
                'name' => 'Пополнение счёта',
                'value' => 9000
            ]
        ];
        $result = Receipt::createReceiptForProject(5, $receipt);
        $this->assertTrue($result);
        $result = Receipt::getTotalForProject(5);
        $this->assertEqual(10000, $result);
    }

    public function testFetchReceipt()
    {
        $result = Receipt::fetchReceipt(2);
        $this->assertEqual(2, count($result));
        $this->assertEqual('object', gettype($result));
        $this->assertEqual('lithium\data\collection\RecordSet', get_class($result));
    }

    public function testCreateReceipt()
    {
        $data = [
            'features' => [
                'award' => 10000,
                'experts' => [2, 3]
            ],
            'commonPitchData' => [
                'category_id' => 1,
                'id' => 2000,
                'referalDiscount' => 500
            ],
        ];
        $result = Receipt::createReceipt($data);
        $this->assertEqual(2000, $result);
        $total = Receipt::getTotalForProject(2000);
        $this->assertEqual(16950, $total);
        $exported = Receipt::exportToArray(2000);
        $expected = [
            [
                'name' => 'Награда Дизайнеру',
                'value' => 10000,
            ],
            [
                'name' => 'Экспертное мнение',
                'value' => 5000,
            ],
            [
                'name' => 'Сбор GoDesigner 24,5%',
                'value' => 1950,
            ],
        ];
        $this->assertEqual($expected, $exported);

        $data = [
            'features' => [
                'award' => 10000,
                'experts' => [2, 3]
            ],
            'commonPitchData' => [
                'category_id' => 1,
                'id' => 2000,
                'promocode' => 'aaaa'
            ],
        ];
        Receipt::createReceipt($data);

        $exported = Receipt::exportToArray(2000);
        $expected = [
            [
                'name' => 'Награда Дизайнеру',
                'value' => 10000,
            ],
            [
                'name' => 'Экспертное мнение',
                'value' => 5000,
            ],
            [
                'name' => 'Сбор GoDesigner 24,5%',
                'value' => 1750,
            ],
        ];
        $this->assertEqual($expected, $exported);

        $data = [
            'features' => [
                'award' => 10000,
            ],
            'commonPitchData' => [
                'category_id' => 1,
                'id' => 2000,
                'promocode' => 'hhhh'
            ],
        ];
        Receipt::createReceipt($data);

        $exported = Receipt::exportToArray(2000);
        $expected = [
            [
                'name' => 'Награда Дизайнеру',
                'value' => 10000,
            ],
            [
                'name' => 'Сбор GoDesigner 24,5%',
                'value' => 1225,
            ],
        ];
        $this->assertEqual($expected, $exported);

        $data = [
            'features' => [
                'award' => 10000,
            ],
            'commonPitchData' => [
                'category_id' => 1,
                'id' => 2000,
                'promocode' => 'jjjj'
            ],
        ];
        Receipt::createReceipt($data);

        $exported = Receipt::exportToArray(2000);
        $expected = [
            [
                'name' => 'Награда Дизайнеру',
                'value' => 10000,
            ],
            [
                'name' => 'Сбор GoDesigner 24,5%',
                'value' => 0,
            ],
        ];
        $this->assertEqual($expected, $exported);

        $data = [
            'features' => [
                'award' => 10000,
                'experts' => [2, 3]
            ],
            'commonPitchData' => [
                'category_id' => 1,
                'id' => 2000,
                'promocode' => 'bbbb'
            ],
        ];
        Receipt::createReceipt($data);

        $exported = Receipt::exportToArray(2000);
        $expected = [
            [
                'name' => 'Награда Дизайнеру',
                'value' => 10000,
            ],
            [
                'name' => 'Экспертное мнение',
                'value' => 5000,
            ],
            [
                'name' => 'Сбор GoDesigner 12,2%',
                'value' => 1220,
            ],
        ];
        $this->assertEqual($expected, $exported);

        $data = [
            'features' => [
                'award' => 30000,
                'discount' => -700
            ],
            'commonPitchData' => [
                'category_id' => 7,
                'id' => 2001
            ],
        ];
        $result = Receipt::createReceipt($data);
        $this->assertEqual(2001, $result);
        $total = Receipt::getTotalForProject(2001);
        $this->assertEqual(35150, $total);
        $exported = Receipt::exportToArray(2001);
        $expected = [
            [
                'name' => 'Награда копирайтеру',
                'value' => 30000,
            ],
            [
                'name' => 'Скидка',
                'value' => -700
            ],
            [
                'name' => 'Сбор GoDesigner 19,5%',
                'value' => 5850,
            ],
        ];
        $this->assertEqual($expected, $exported);
    }

    public function testGetCommissionForProject()
    {
        $data = [
            'features' => [
                'award' => 10000,
                'experts' => [2, 3]
            ],
            'commonPitchData' => [
                'category_id' => 1,
                'id' => 2000,
            ],
        ];
        $result = Receipt::createReceipt($data);
        $commission = Receipt::getCommissionForProject($result);
        $this->assertIdentical(2450, $commission);
    }

    public function testAddRow()
    {
        $data = [
            [
                'name' => 'Награда копирайтеру',
                'value' => 30000,
            ],
            [
                'name' => 'Сбор GoDesigner 19,5%',
                'value' => 5850,
            ],
        ];
        $result = Receipt::addRow($data, 'Скидка', -700);
        $expected = [
            [
                'name' => 'Награда копирайтеру',
                'value' => 30000,
            ],
            [
                'name' => 'Сбор GoDesigner 19,5%',
                'value' => 5850,
            ],
            [
                'name' => 'Скидка',
                'value' => -700,
            ],
        ];
        $this->assertIdentical($expected, $result);
    }

    public function testUpdateRow()
    {
        $data = [
            [
                'name' => 'Награда копирайтеру',
                'value' => 30000,
            ],
            [
                'name' => 'Сбор GoDesigner 19,5%',
                'value' => 5850,
            ],
        ];
        $result = Receipt::updateRow($data, 'Награда копирайтеру', 15000);
        $expected = [
            [
                'name' => 'Награда копирайтеру',
                'value' => 15000,
            ],
            [
                'name' => 'Сбор GoDesigner 19,5%',
                'value' => 5850,
            ],
        ];
        $this->assertIdentical($expected, $result);
    }

    public function testGetTotalFromArray()
    {
        $receipt = [
            [
                'name' => 'Оплата тарифа «Проверка»',
                'value' => 21000
            ],
            [
                'name' => 'Пополнение счёта',
                'value' => 5000
            ],
            [
                'name' => 'Скидка',
                'value' => -5000
            ]
        ];
        $this->assertEqual(21000, Receipt::getTotalFromArray($receipt));
    }

    public function testGetRealProfit()
    {
        $data = [
            'features' => [
                'award' => 10000,
            ],
            'commonPitchData' => [
                'category_id' => 1,
                'id' => 2000,
            ],
        ];
        $result = Receipt::createReceipt($data);
        $this->assertEqual(2000, $result);
        $total = Receipt::getTotalForProject(2000);
        $this->assertEqual(12450, $total);
        $profit = Receipt::getProfitForProject(2000);
        $this->assertEqual(2450, $profit);

        $data = [
            'features' => [
                'award' => 10000,
                'experts' => [1]
            ],
            'commonPitchData' => [
                'category_id' => 1,
                'id' => 2000,
            ],
        ];
        $result = Receipt::createReceipt($data);
        $this->assertEqual(2000, $result);
        $total = Receipt::getTotalForProject(2000);
        $this->assertEqual(13450, $total);
        $profit = Receipt::getProfitForProject(2000);
        $this->assertEqual(2650, $profit);

        $data = [
            'features' => [
                'award' => 10000,
                'experts' => [1],
                'private' => 3500
            ],
            'commonPitchData' => [
                'category_id' => 1,
                'id' => 2000,
            ],
        ];
        $result = Receipt::createReceipt($data);
        $this->assertEqual(2000, $result);
        $total = Receipt::getTotalForProject(2000);
        $this->assertEqual(16950, $total);
        $profit = Receipt::getProfitForProject(2000);
        $this->assertEqual(6150, $profit);

        $data = [
            'features' => [
                'award' => 10000,
                'experts' => [1],
                'private' => 3500,
            ],
            'commonPitchData' => [
                'category_id' => 1,
                'id' => 2000,
                'promocode' => 'jjjj'
            ],
        ];
        $result = Receipt::createReceipt($data);
        $this->assertEqual(2000, $result);
        $total = Receipt::getTotalForProject(2000);
        $this->assertEqual(14500, $total);
        $profit = Receipt::getProfitForProject(2000);
        $this->assertEqual(3700, $profit);
    }
}
