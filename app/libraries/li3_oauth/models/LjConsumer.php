<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_oauth\models;

class LjConsumer extends \lithium\core\StaticObject {

	/**
	 * Holds an instance of the oauth service class
	 *
	 * @see \li3_oauth\extensions\services\Oauth
	 */
	protected static $_service = null;

	protected static $_classes = array(
		'oauth' => '\li3_oauth\extensions\service\Oauth'
	);
	
	public static function config($config) {
		static::$_service = new static::$_classes['oauth']($config);
	}

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
		preg_match('#http://(.*).livejournal.com#', $ljAddress, $match);
		$username = $match[1];
		return $username;
	}
	
	public static function getLjAddress($username) {
		return 'http://' . $username . '.livejournal.com';
	}


}

?>