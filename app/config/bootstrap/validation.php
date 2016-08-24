<?php
use lithium\util\Validator;
use app\models\User;
use \lithium\storage\Session;

/**
* Checks whether email is unique
*/
Validator::add('userUniqueEmail', function($value) {
	if($user = User::find('first', array('conditions' => array('email' => $value)))) {
        return false;
	}else {
		return true;
	}
});

Validator::add('passwordConfirmed', function($value, $attr1, $attr2) {
	return (bool) ($attr2['values']['confirm_password'] == $attr2['values']['password']);
});