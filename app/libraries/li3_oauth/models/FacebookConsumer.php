<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_oauth\models;

class FacebookConsumer extends \lithium\core\StaticObject
{

    /**
     * Holds an instance of the oauth service class
     *
     * @see \li3_oauth\extensions\services\Oauth
     */
    protected static $_service = null;

    protected static $_classes = [
        'oauth' => '\li3_oauth\extensions\service\Oauth'
    ];

    /**
     * Configure the Consumer to access the Oauth service layer
     * {{{
     * Consumer::config(array(
     *    'host' => 'localhost',
     *    'oauth_consumer_key' => 'key',
     *    'oauth_consumer_secret' => 'secret',
     *    'request_token' => 'libraries/oauth_php/example/request_token.php',
     *    'access_token' => 'libraries/oauth_php/example/access_token.php',
     * ));
     * }}}
     *
     * @param array $config
     *              - host: the oauth domain
     *              - oauth_consumer_key: key from oauth service provider
     *              - oauth_consumer_secret: secret from oauth service provider
     *              - oauth_consumer_key: key from oauth service provider
     *              - authorize: path to authorize  url
     *              - request_token: path to request token url
     *              - access_token: path to access token url
     *
     * @return void
     */
    public static function config($config)
    {
        static::$_service = new static::$_classes['oauth']($config);
    }

    /**
     * Magic method to pass through HTTP methods. i.e.`Consumer::post()`
     *
     * @param string $method
     * @param string $params
     * @return mixed
     */
    public static function __callStatic($method, $params)
    {
        return static::$_service->invokeMethod($method, $params);
    }

    /**
     * get url from remote authorization endpoint along with request params
     *
     * @param array $token
     * @param array $options
     * @return string
     */
    public static function authorize(array $options = [])
    {
        $config = static::$_service->config();
        $fullUrl = $config['scheme'] . '://' . $config['auth_host'] . $config['authorize'] . '?client_id=' . $config['client_id']
        . '&redirect_uri=' . $config['redirect_uri']
        . '&display=page&scopre=' . $config['scope'];
        return $fullUrl;
    }
    
    public static function access($access)
    {
        $config = static::$_service->config();
        $url = $config['access']  . '?client_id=' . $config['client_id']
        . '&client_secret=' . $config['client_secret']
        . '&code=' . $access . '&redirect_uri=' . urlencode($config['redirect_uri']) ;
        return static::$_service->send('GET', $url, [], ['port' => 443]);
    }
    
    public static function getUser($uid, $access_token)
    {
        $url = '/method/getProfiles?uid='. $uid . '&access_token=' . $access_token;
        return static::$_service->send('GET', $url, [], ['port' => 443]);
    }
}
