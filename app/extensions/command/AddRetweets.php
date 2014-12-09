<?php

namespace app\extensions\command;

use app\models\Event;
use app\extensions\storage\Rcache;

class AddRetweets extends \app\extensions\command\CronJob {

    public function run() {
        Rcache::init();
        $string = base64_encode('8r9SEMoXAacbpnpjJ5v64A:I1MP2x7guzDHG6NIB8m7FshhkoIuD6krZ6xpN4TSsk');
        $tmhOAuth = new tmhOAuth(array(
            'consumer_key' => '8r9SEMoXAacbpnpjJ5v64A',
            'consumer_secret' => 'I1MP2x7guzDHG6NIB8m7FshhkoIuD6krZ6xpN4TSsk',
            'user_token' => '513074899-IvVlKCCD0kEBicxjrLGLjW2Pb7ZiJd1ZjQB9mkvN',
            'user_secret' => 'ldmaK6qmlzA3QJPQemmVWJGUpfST3YuxrzIbhaArQ9M'
        ));
        $tmhOAuth->headers['Authorization'] = 'Basic ' . $string;
        $params = array('grant_type' => 'client_credentials');
        $response = $tmhOAuth->request('POST', 'https://api.twitter.com/oauth2/token', $params, false
        );
        $data = json_decode($tmhOAuth->response['response'], true);
        $bearerToken = $data['access_token'];
        $tmhOAuth->headers['Authorization'] = 'Bearer ' . $bearerToken;
        $params = array('rpp' => 10, 'screen_name' => 'Go_Deer', 'include_entities' => false);
        $code = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/statuses/user_timeline.json', $params, false);
        if ($code == 200) {
            $data = json_decode($tmhOAuth->response['response'], true);
            $events = Event::all(array('conditions' => array('type' => 'RetweetAdded')));
            $trigger = true;
            $tweetsDump = array();
            foreach ($data as $tweet) {
                if (isset($tweet['retweeted_status'])) {
                    foreach ($events as $v) {
                        $params = array('rpp' => 10, 'id' => $tweet['id_str'], 'include_entities' => true);
                        $code = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/statuses/oembed.json', $params, false);
                        $data = json_decode($tmhOAuth->response['response'], true);
                        $tweetsDump[$tweet['id_str']] = $data['html']; //preg_replace('!<script[^>]*>(.)*</script>!Uis', '', $data['html']);
                        if ($v->tweet_id == $tweet['id_str']) {
                            $trigger = false;
                        }
                    }
                    if ($trigger) {
                        $date = new \DateTime($tweet['created_at']);
                        Event::create(array(
                            'type' => 'RetweetAdded',
                            'tweet_id' => $tweet['id_str'],
                            'created' => $date->format('Y-m-d H:i:s')
                        ))->save();
                    }
                }
            }
            Rcache::write('RetweetsFeed', $tweetsDump);
        }
    }

}
