<?php

namespace app\models;

use \app\models\Expert;

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

    public static $fee = 0.145;


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
        $receiptData[] = array(
            'pitch_id' => $data['commonPitchData']['id'],
            'name' => self::$dict['fee'],
            'value' => round($data['features']['award'] * self::$fee)
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


}