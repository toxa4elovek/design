<?php

namespace app\models;

/*
use \lithium\util\Validator;
use \lithium\util\String;
*/

class Sendemail extends \app\models\AppModel {

    /**
     * Delete Old Sent Messages
     * @return boolean
     */
    public static function clearOldSpam() {
        return self::remove(array(
            'created' => array(
                '<' => date('Y-m-d H:i:s', time() - MONTH),
            ),
        ));
    }

    public static function clearOldSpamSimple() {
        $sentEmails = self::find('all', array(
            'limit' => 1000,
            'conditions' =>  array('created' => array('<' => date('Y-m-d H:i:s', time() - MONTH))),
            'order' => array('id' => 'asc')
        ));
        foreach($sentEmails as $email) {
            $email->delete();
        }
        return count($sentEmails);
    }
}
