<?php
namespace app\models;

class Logreferal extends AppModel
{

    public $belongsTo = ['User'];

    /**
     * Метод возвращяет количество завершенных реферальныйх платежей пользователя $userId
     *
     * @param $userId
     * @return int
     */
    public static function getCompletePaymentCount($userId)
    {
        return self::count(['conditions' => ['user_id' => $userId]]);
    }
}
