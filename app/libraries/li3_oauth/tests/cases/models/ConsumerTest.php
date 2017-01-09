<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_oauth\tests\cases\models;

use \li3_oauth\models\Consumer;

class ConsumerTest extends \lithium\test\Unit
{

    public function setUp()
    {
        Consumer::config([
            'host' => 'localhost',
            'oauth_consumer_key' => 'key',
            'oauth_consumer_secret' => 'secret',
            'request' => 'libraries/oauth_php/example/request_token.php',
            'access' => 'libraries/oauth_php/example/access_token.php',
            'port' => 30500
        ]);
    }

    public function testAuthorize()
    {
        $expected = 'http://localhost/oauth/authorize?oauth_token=requestkey';
        $result = Consumer::authorize([
            'oauth_token' => 'requestkey',
            'oauth_token_secret' => 'requestsecret'
        ]);
        $this->assertEqual($expected, $result);
    }

    public function testAuthenticate()
    {
        $expected = 'http://localhost/oauth/authenticate?oauth_token=requestkey';
        $result = Consumer::authenticate([
            'oauth_token' => 'requestkey',
            'oauth_token_secret' => 'requestsecret'
        ]);
        $this->assertEqual($expected, $result);
    }

    public function testRequestToken()
    {
        $expected = [
            'oauth_token' => 'requestkey',
            'oauth_token_secret' => 'requestsecret'
        ];
        $result = Consumer::token('request', [
            'oauth_token' => 'key',
            'oauth_token_secret' => 'secret'
        ]);
        $this->assertEqual($expected, $result);
    }

    public function testAccessToken()
    {
        $expected = [
            'oauth_token' => 'accesskey',
            'oauth_token_secret' => 'accesssecret'
        ];
        $token = [
            'oauth_token' => 'requestkey',
            'oauth_token_secret' => 'requestsecret'
        ];
        $result = Consumer::token('access', compact('token'));
        $this->assertEqual($expected, $result);
    }

    public function testPost()
    {
        $expected = '{"test":"cool"}';
        $token = [
            'oauth_token' => 'requestkey',
            'oauth_token_secret' => 'requestsecret'
        ];
        Consumer::config(['classes' => [
            'socket' => '\li3_oauth\tests\mocks\extensions\service\MockSocket',
        ]]);
        $result = Consumer::post('search', [], compact('token'));
        $this->assertEqual($expected, $result);
    }
}
