<?php

namespace app\models;

class Sendemail extends \app\models\AppModel {

    /**
     * Delete Old Sent Messages
     * @return boolean
     */
    public static function clearOldSpam() {
        return self::remove(array(
            'created' => array(
                '<' => date('Y-m-d H:i:s', time() - (WEEK * 3)),
            ),
        ));
    }

    /**
     * Удаляет старые письма, по 1000 писем за раз.
     *
     * @return int
     */
    public static function clearOldSpamSimple() {
        $sentEmails = self::find('all', array(
            'limit' => 1000,
            'conditions' =>  array('created' => array('<' => date('Y-m-d H:i:s', time() - (WEEK * 3)))),
            'order' => array('id' => 'asc')
        ));
        foreach($sentEmails as $email) {
            $email->delete();
        }
        return count($sentEmails);
    }
}
