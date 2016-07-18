<?php

namespace app\models;

/**
 * Class Lead
 * @package app\models
 * @method Record|null first(array $conditions) static
 */
class Lead extends AppModel
{

    /**
     * @var array связи
     */
    public $belongsTo = ['User'];

    /**
     * Метод сбрасывает состояние лида, чтобы он снова появлялся сверху списка в админке/
     *
     * @param $userId integer
     * @return bool
     */
    public static function resetLeadForUser($userId)
    {
        if ($lead = self::first(['conditions' => ['user_id' => (int) $userId]])) {
            $lead->result = 0;
            return $lead->save();
        }
        return false;
    }
}
