<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_oauth\models;

class YandexConsumer extends \lithium\core\StaticObject {

	/**
	 * Magic method to pass through HTTP methods. i.e.`Consumer::post()`
	 *
	 * @param string $method
	 * @param string $params
	 * @return mixed
	 */
	public static function __callStatic($method, $params) {
		return static::$_service->invokeMethod($method, $params);
	}

	public static function getUsername($ljAddress) {
		preg_match('#http://(.*).ya.ru#', $ljAddress, $match);
		$username = $match[1];
		return $username;
	}
	
	public static function getYaAddress($username) {
		return 'http://' . $username . '.ya.ru';
	}


}

?>