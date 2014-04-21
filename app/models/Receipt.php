<?php

namespace app\models;

use \app\models\Expert;
use \app\models\Category;

class Receipt extends \app\models\AppModel {

    public static $dict = array(
        'award' => 'Награда Дизайнеру',
        'private' => 'Закрытый питч',
        'social' => 'Рекламный Кейс',
        'experts' => 'Экспертное мнение',
        'pinned' => '“Прокачать” бриф',
        'timelimit' => 'Установлен срок',
        'brief' => 'Заполнение брифа',
        'guaranteed' => 'Гарантированный питч',
        'fee' => 'Сбор GoDesigner',
    );

    public static $fee = FEE_LOW;


    public static function createReceipt($data) {
        $receiptData = array();
        if(isset($data['features']['award'])) {
            $receiptData[] = array(
                'pitch_id' => $data['commonPitchData']['id'],
                'name' => self::$dict['award'],
                'value' => $data['features']['award']
            );
        }
        if(isset($data['features']['private'])) {
            $receiptData[] = array(
                'pitch_id' => $data['commonPitchData']['id'],
                'name' => self::$dict['private'],
                'value' => $data['features']['private']
            );
        }
        if(isset($data['features']['social'])) {
            $receiptData[] = array(
                'pitch_id' => $data['commonPitchData']['id'],
                'name' => self::$dict['social'],
                'value' => $data['features']['social']
            );
        }
        if(isset($data['features']['experts'])) {
            $total = 0;
            foreach($data['features']['experts'] as $expertId) {
                $expert = Expert::first($expertId);
                $total += $expert->price;
            }

            $receiptData[] = array(
                'pitch_id' => $data['commonPitchData']['id'],
                'name' => self::$dict['experts'],
                'value' => $total
            );
        }
        if(isset($data['features']['pinned'])) {
            $receiptData[] = array(
                'pitch_id' => $data['commonPitchData']['id'],
                'name' => self::$dict['pinned'],
                'value' => $data['features']['pinned']
            );
        }
        if(isset($data['features']['timelimit'])) {
            $receiptData[] = array(
                'pitch_id' => $data['commonPitchData']['id'],
                'name' => self::$dict['timelimit'],
                'value' => $data['features']['timelimit']
            );
        }
        if(isset($data['features']['brief'])) {
            $receiptData[] = array(
                'pitch_id' => $data['commonPitchData']['id'],
                'name' => self::$dict['brief'],
                'value' => $data['features']['brief']
            );
        }
        if(isset($data['features']['guaranteed'])) {
            $receiptData[] = array(
                'pitch_id' => $data['commonPitchData']['id'],
                'name' => self::$dict['guaranteed'],
                'value' => $data['features']['guaranteed']
            );
        }
        $total = 0;
        foreach($receiptData as $row) {
            $total += $row['value'];

        }
        self::$fee = self::findOutFee($data);
        $comission = round($data['features']['award'] * self::$fee);
        if($promocode = Promocode::checkPromocode($data['commonPitchData']['promocode'])) {
            if($promocode['type'] == 'in_twain') {
                self::$fee = round((self::$fee / 2), 3, PHP_ROUND_HALF_DOWN);
                $comission = round($data['features']['award'] * self::$fee);
            }
            if($promocode['type'] == 'discount') {
                $comission -= 700;
            }
        }
        if (isset($data['commonPitchData']['referalDiscount']) && !empty($data['commonPitchData']['referalDiscount'])) {
            $comission -= $data['commonPitchData']['referalDiscount'];
        }
        $receiptData[] = array(
            'pitch_id' => $data['commonPitchData']['id'],
            'name' => self::$dict['fee'] . ' ' . str_replace('.', ',', self::$fee * 100 . '%'),
            'value' => $comission
        );
        self::remove(array('pitch_id' => $data['commonPitchData']['id']));
        foreach($receiptData as $row) {
            $receiptItem = self::create();
            $receiptItem->set($row);
            $receiptItem->save();
        }
        return $data['commonPitchData']['id'];
    }

    public static function fetchReceipt($id) {
        return self::all(array('conditions' => array('pitch_id' => $id)));
    }

    public static function findTotal($id) {
        $receipt = self::fetchReceipt($id);
        $total = 0;
        foreach($receipt as $item) {
            $total += $item->value;
        }
        return $total;
    }

    protected static function findOutFee($data) {
        $fee = self::$fee;
        $award = $data['features']['award'];
        if ($category = Category::first($data['commonPitchData']['category_id'])) {
            $minAward = $minValue = $category->minAward;
            $normalAward = $normal = $category->normalAward;
            $goodAward = $high = $category->goodAward;
            if (!empty($data['specificPitchData']['site-sub'])) { // Multi Items Pitch
                $quantity = $data['specificPitchData']['site-sub'];
                if ($category->id == 3) {
                    $mult = 2000;
                } else {
                    $mult = $minAward / 2;
                }
                $minValue = (($quantity - 1) * $mult) + $minAward;
            }
            if ($category->id == 7) {
                /*
                 * Needed for another behavior

                $mods = 0;
                $mods += (!empty($data['specificPitchData']['first-option'])) ? 1 : 0;
                $mods += (!empty($data['specificPitchData']['second-option'])) ? 1 : 0;
                $mods += (!empty($data['specificPitchData']['third-option'])) ? 1 : 0;
                switch ($mods) {
                    case 1: $mod = 1; break;
                    case 2: $mod = 1.5; break;
                    case 3: $mod = 1.75; break;
                    default: $mod = 1; break;
                }
                $minValue = COPY_BASE_PRICE * $mod;
                 */

                $minValue = $minAward;
            }
            if ($category->id == 11) {
                /*
                 * Nothing needed
                 *
                 */
                $minValue = $minAward;
            }
            $extraNormal = $normalAward - $minAward;
            $extraHigh = $goodAward - $minAward;
            $normal = $minValue + $extraNormal;
            $high = $minValue + $extraHigh;
            if ($award < $normal) {
                $fee = FEE_LOW;
            } else if ($award < $high) {
                $fee = FEE_NORMAL;
            } else {
                $fee = FEE_GOOD;
            }
        }
        return $fee;
    }
}