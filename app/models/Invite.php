<?php

namespace app\models;

class Invite extends \app\models\AppModel {

	public static function isValidInvite($code) {
		if($invite = self::first(array('conditions' => array('code' => $code, 'used' => 0)))) {
			return true;
		}
		return false;
	}

	public static function activateInvite($code, $userId) {
		if(self::isValidInvite($code)) {
			self::update(array(
                'used' => 1,
                'activated' => date('Y-m-d H:i:s'),
                'user_id' => $userId
            ), array('code' => $code));
			return true;
		}
	}

}