<?php

namespace app\models;

class Sendemail extends \app\models\AppModel
{

    /**
     * Delete Old Sent Messages
     * @return boolean
     */
    public static function clearOldSpam()
    {
        return self::remove([
            'created' => [
                '<' => date('Y-m-d H:i:s', time() - (WEEK * 3)),
            ],
        ]);
    }

    /**
     * Удаляет старые письма, по 1000 писем за раз.
     *
     * @return int
     */
    public static function clearOldSpamSimple()
    {
        $sentEmails = self::find('all', [
            'fields' => ['id'],
            'limit' => 1000,
            'conditions' =>  ['created' => ['<' => date('Y-m-d H:i:s', time() - (WEEK * 3.00))]],
            'order' => ['id' => 'asc']
        ]);
        foreach ($sentEmails as $email) {
            $email->delete();
        }
        return count($sentEmails);
    }
}
