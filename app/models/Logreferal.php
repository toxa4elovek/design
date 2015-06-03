<?php
namespace app\models;

class Logreferal extends AppModel {

    public $belongsTo = array('User');

    /**
     * Метод возвращяет количество завершенных реферальныйх платежей пользователя $userId
     *
     * @param $userId
     * @return int
     */
    public static function getCompletePaymentCount($userId) {
        return self::count(array('conditions' => array('user_id' => $userId)));
    }
}