<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_oauth\models;

class MailruConsumer extends \lithium\core\StaticObject {

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

	public static function access($access) {
		$config = static::$_service->config(); 
		$url = $config['access']  . '?client_id=' . $config['client_id'] 
		. '&client_secret=' . $config['client_secret'] 
		. '&code=' . $access;
		return static::$_service->send('GET', $url, array(), array('port' => 443));
	}
	
	public static function getInfo($params = array()) {
		$config = static::$_service->config(); 
		$data = array(
			'method' => 'users.getInfo', 
			'app_id' => $config['app_id'],
			'session_key' => $params['session_key'],
			'uid' => $params['vid'],
			'secure' => '1',
			'format' => 'json',
		); 
		$data['sig'] = static::createMD5($data, $config['secret_key']);
		
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $config['host']);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 40);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);
        if(isset($response['error'])) {
        	return false;
        }else {
        	return $response[0];
        }
	}
	
	public static function createMD5(array $requestParams, $secretKey) {
		ksort($requestParams);
		$params = '';
		foreach ($requestParams as $key => $value) {
			$params .= "$key=$value";
		}
		return md5($params . $secretKey);
	}


}

?>