<?php

namespace app\models;

/**
 * Class Avatar
 *
 * Метод для управления аватарами пользователя
 * @package app\models
 */
class Avatar extends AppModel
{

    /**
     * Метод удаляет все записи и файлы аватаров для пользователя
     *
     * @param $userId
     */
    public static function clearOldAvatars($userId)
    {
        $avatars = self::all(['conditions' => ['model_id' => $userId]]);
        foreach ($avatars as $avatar) {
            if (file_exists($avatar->filename)) {
                unlink($avatar->filename);
            }
            $avatar->delete();
        }
    }
}
