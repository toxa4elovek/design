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
}
