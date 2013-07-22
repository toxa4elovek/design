<?php

namespace app\extensions\command;

use \tmhOAuth\tmhOAuth;
use \tmhOAuth\tmhUtilities;
use \lithium\storage\cache;

class TwitterStream extends \lithium\console\Command {

    public function run() {
        phpinfo();
        die();
        $this->header('Welcome to the Stream command!');
        $this->out('Stream is about to start');
        //require_once LITHIUM_APP_PATH . '/libraries/tmhOAuth/tmhOAuth.php';
        //require_once LITHIUM_APP_PATH . '/libraries/tmhOAuth/tmhUtilities.php';

        $tmhOAuth = new tmhOAuth(array(
            'consumer_key'    => '8r9SEMoXAacbpnpjJ5v64A',
            'consumer_secret' => 'I1MP2x7guzDHG6NIB8m7FshhkoIuD6krZ6xpN4TSsk',
            'user_token'      => '513074899-IvVlKCCD0kEBicxjrLGLjW2Pb7ZiJd1ZjQB9mkvN',
            'user_secret'     => 'ldmaK6qmlzA3QJPQemmVWJGUpfST3YuxrzIbhaArQ9M',
            'debug' => true
        ));
        $method = 'https://stream.twitter.com/1/statuses/search.json';

        $params = array('rpp' => 5, 'q' => 'godesigner');

        $code = $tmhOAuth->request('GET',
            'http://search.twitter.com/search.json',
            $params,
            false
        );

        if ($code == 200) {
            $data = json_decode($tmhOAuth->response['response'], true);
            $res = Cache::write('default', 'twitterstream', $data);
            var_dump($res);
            return true;
        }else {
            var_dump($tmhOAuth->response);
            return false;
        }
    }
}