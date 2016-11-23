<?php

namespace app\models;

/**
 * Class Receipt
 *
 * Класс для работы с записями чека
 *
 * @package app\models
 */
class Receipt extends AppModel
{

    public static $dict = [
        'award' => 'Награда Дизайнеру',
        'private' => 'Скрыть проект',
        'social' => 'Рекламный Кейс',
        'experts' => 'Экспертное мнение',
        'pinned' => '«Прокачать» проект',
        'timelimit' => 'Установлен срок',
        'brief' => 'Заполнение брифа',
        'guaranteed' => 'Гарантированный проект',
        'premium' => 'Премиум-проект',
        'fee' => 'Сбор GoDesigner',
        'discount' => 'Скидка'
    ];

    public static $fee = FEE_LOW;


    /**
     * Метод создает чек, считает коммсиию, и сохряняет (перезаписывает) данные
     *
     * @param $data array массив входящих данных
     * @return integer номер проекта
     */
    public static function createReceipt($data)
    {
        if ($data['commonPitchData']['category_id'] == 7) {
            self::$dict['award'] = 'Награда копирайтеру';
        }
        $receiptData = [];
        $projectId = $data['commonPitchData']['id'];
        $keys = [
            'award',
            'private',
            'social',
            'experts',
            'pinned',
            'timelimit',
            'brief',
            'discount',
            'guaranteed',
            'premium'
        ];
        foreach ($keys as $key) {
            if (isset($data['features'][$key])) {
                $value = $data['features'][$key];
                if ($key == 'experts') {
                    $value = 0;
                    foreach ($data['features']['experts'] as $expertId) {
                        $expert = Expert::first($expertId);
                        $value += $expert->price;
                    }
                }
                $receiptData[] = [
                    'pitch_id' => $projectId,
                    'name' => self::$dict[$key],
                    'value' => $value
                ];
            }
        }
        if ($data['commonPitchData']['category_id'] != 20) {
            self::$fee = self::findOutFeeModifier($data);
            $commission = self::__getCommissionWithEffectOfPromocodes($data);
            $commission = self::__applyReferalDiscrountEffects($data, $commission);
            $receiptData[] = [
                'pitch_id' => $projectId,
                'name' => self::$dict['fee'] . ' ' . str_replace('.', ',', self::$fee * 100 . '%'),
                'value' => $commission
            ];
        } else {
            $receiptData[] = [
                'pitch_id' => $projectId,
                'name' => self::$dict['fee'] . ' 0%',
                'value' => 0
            ];
        }
        self::updateOrCreateReceiptForProject($projectId, $receiptData);
        return (int) $projectId;
    }

    /**
     * Метод помощник, мутирует сбор, если есть скидка от реферала
     *
     * @param $data array
     * @param $commission integer
     * @return mixed
     */
    private static function __applyReferalDiscrountEffects($data, $commission)
    {
        if (isset($data['commonPitchData']['referalDiscount']) && !empty($data['commonPitchData']['referalDiscount'])) {
            $commission -= $data['commonPitchData']['referalDiscount'];
        }
        return $commission;
    }

    /**
     * Метод-помощник для снижения сложности родительского метода
     *
     * @param $data array
     * @return float|int
     */
    private static function __getCommissionWithEffectOfPromocodes($data)
    {
        if (!isset($data['commonPitchData']['id'])) {
            $data['commonPitchData']['id'] = null;
        }
        $commission = round($data['features']['award'] * self::$fee);
        if (isset($data['commonPitchData']['promocode']) && ($promocode = Promocode::checkPromocode($data['commonPitchData']['promocode'], $data['commonPitchData']['id'])) && ($promocode != 'false')) {
            $commission = self::__processPromocodes($promocode, $commission, $data['features']['award']);
        }
        return $commission;
    }

    /**
     * Метод-помощник, обрабатывает промокоды, вовзращяет уменьшенную коммисси
     * и может поменять процент сбора
     *
     * @param $promocode array
     * @param $commission integer
     * @param $award integer
     * @return float|int
     */
    private static function __processPromocodes($promocode, $commission, $award)
    {
        if ($promocode['type'] == 'in_twain') {
            self::$fee = round((self::$fee / 2), 3, PHP_ROUND_HALF_DOWN);
            $commission = round($award * self::$fee);
        }
        if ($promocode['type'] == 'discount') {
            $commission -= 700;
        }
        if ($promocode['type'] == 'custom_discount') {
            $decimal = $promocode['data'] / 100;
            $amount = round($commission * $decimal);
            $commission -= $amount;
        }
        return $commission;
    }

    /**
     * Метод возвращяет все записи чека для проекта $projectId
     *
     * @param $projectId
     * @return \lithium\data\collection\RecordSet|null
     */
    public static function fetchReceipt($projectId)
    {
        return self::all(['conditions' => ['pitch_id' => $projectId]]);
    }

    /**
     * Метод высчитывает множитель сбора сервиса в зависимости от уровня награды,
     * категории и количестве требуемых единиц
     *
     * @param $data array
     * @return float
     */
    public static function findOutFeeModifier($data)
    {
        $fee = self::$fee;
        $award = $data['features']['award'];
        if ($category = Category::first($data['commonPitchData']['category_id'])) {
            $minAward = $minValue = $category->minAward;
            $normalAward = $normal = $category->normalAward;
            $goodAward = $high = $category->goodAward;
            if (!empty($data['specificPitchData']['site-sub'])) {
                $minValue = self::__getMinValueForWebsite(
                   (int) $data['specificPitchData']['site-sub'],
                   (int) $minAward,
                   (int) $category->id
               );
            }
            $extraNormal = $normalAward - $minAward;
            $extraHigh = $goodAward - $minAward;
            $normal = $minValue + $extraNormal;
            $high = $minValue + $extraHigh;
            if ($award < $normal) {
                $fee = FEE_LOW;
            } elseif ($award < $high) {
                $fee = FEE_NORMAL;
            } else {
                $fee = FEE_GOOD;
            }
        }
        return $fee;
    }

    /**
     * Метод-помощник для подсчета минимума для категорий, с выбором количества единиц
     *
     * @param $quantity integer количество единиц
     * @param $minAward integer минимальная награда категории
     * @param $categoryId integer номер категории
     * @return integer
     */
    private static function __getMinValueForWebsite($quantity, $minAward, $categoryId)
    {
        $multiplier = $minAward / 2;
        if ($categoryId == 3) {
            $multiplier = 2000;
        }
        return (($quantity - 1) * $multiplier) + $minAward;
    }

    /**
     * Метод создает построчно чек из массива $data
     *
     * @param $projectId int
     * @param $data array
     * @return bool
     */
    public static function createReceiptForProject($projectId, $data)
    {
        if (is_array($data)) {
            foreach ($data as $row) {
                $data = [
                    'pitch_id' => $projectId,
                    'name' => $row['name'],
                    'value' => $row['value']
                ];
                $row = self::create($data);
                $row->save();
            }
            return true;
        }
        return false;
    }

    /**
     * Метод создает построчно чек из массива $data
     *
     * @param $projectId int
     * @param $data array
     * @return bool
     */
    public static function updateOrCreateReceiptForProject($projectId, $data)
    {
        if (is_array($data)) {
            $existingRows = self::all(['conditions' => [
                'pitch_id' => $projectId
            ]]);
            $existingRows->delete();
            foreach ($data as $row) {
                $rowData = [
                    'pitch_id' => $projectId,
                    'name' => $row['name'],
                    'value' => $row['value']
                ];
                $row = self::create($rowData);
                $row->save();
            }
            return true;
        }
        return false;
    }

    /**
     * Метод возвращяет простую структуру для отображения чека на фронтэнде
     *
     * @param $projectId int
     * @return array
     */
    public static function exportToArray($projectId)
    {
        $receiptRows = self::all(['conditions' => [
            'pitch_id' => $projectId
        ]]);
        $array = [];
        foreach ($receiptRows as $row) {
            $array[] = ['name' => $row->name, 'value' => (int) $row->value];
        }
        return $array;
    }

    /**
     * Метод возвращяет сумму проекта согласно чеку
     *
     * @param $projectId int
     * @return int
     */
    public static function getTotalForProject($projectId)
    {
        $total = 0;
        $array = self::exportToArray($projectId);
        foreach ($array as $row) {
            $total += $row['value'];
        }
        return (int) $total;
    }

    /**
     * Метод возвращяет сумму проекта на основе массива
     *
     * @param $receipt array
     * @return int
     */
    public static function getTotalFromArray(array $receipt = [])
    {
        $total = 0;
        foreach ($receipt as $row) {
            $total += $row['value'];
        }
        return (int) $total;
    }

    /**
     * Метод пытается посчитать, сколько прибыли принёс проект.
     * Неточность в подсчете прибыли от экспертов (примерно 20 процентов),
     * это может быть неверно в случае с экспертами не за 1000 рублей.
     *
     * @param $projectId
     * @return int
     */
    public static function getProfitForProject($projectId)
    {
        $profit = 0;
        $array = self::exportToArray($projectId);
        foreach ($array as $row) {
            if (preg_match('/Награда/', $row['name'])) {
                continue;
            }
            if ($row['name'] == self::$dict['experts']) {
                $row['value'] = (20 / 100) * $row['value'];
            }
            $profit += $row['value'];
        }
        return (int) $profit;
    }

    /**
     * Метод возвращяет текущее значение сборя для проекта $projectId
     *
     * @param $projectId integer
     * @return int
     */
    public static function getCommissionForProject($projectId)
    {
        $record = self::first(['conditions' => [
            'pitch_id' => (int) $projectId,
            'name' => ['LIKE' => 'Сбор GoDesigner%']
        ]]);
        return (int) $record->value;
    }

    /**
     * Метод добавляет строчку в массив чека
     *
     * @param $array
     * @param $name
     * @param $value
     * @return array
     */
    public static function addRow($array, $name, $value)
    {
        $array[] = ['name' => $name, 'value' => $value];
        return $array;
    }

    /**
     * Метод обновляет строчку в массиве чека
     *
     * @param $array
     * @param $name
     * @param $value
     * @return $array
     */
    public static function updateRow($array, $name, $value)
    {
        foreach ($array as &$row) {
            if ($row['name'] == $name) {
                $row['value'] = $value;
            }
        }
        return $array;
    }
}
