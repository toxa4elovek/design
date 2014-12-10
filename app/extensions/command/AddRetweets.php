<?php

namespace app\extensions\command;

use app\models\Event;
use app\extensions\storage\Rcache;
use \tmhOAuth\tmhOAuth;
use \tmhOAuth\tmhUtilities;

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
        $params = array('count' => 100, 'screen_name' => 'Go_Deer', 'include_entities' => false);
        $code = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/statuses/user_timeline.json', $params, false);
        if ($code == 200) {
            $data = json_decode($tmhOAuth->response['response'], true);
            //var_dump($data);
            //$events = Event::all(array('conditions' => array('type' => 'RetweetAdded')));
            $tweetsDump = Rcache::read('RetweetsFeed');
            foreach ($data as $tweet) {
                if (isset($tweet['retweeted_status']) || (strpos($tweet['text'], ' победил в питче ') !== false || strpos($tweet['text'], ' заработал ') !== false)) {
                    $this->out('Dumping tweet data...');
                    var_dump($tweet);
                    $this->out('checking if tweet event exists in database...');
                    if(!$tweetEvent = Event::first(array('conditions' => array('tweet_id' => $tweet['id_str'])))) {
                        $this->out('Tweet ' . $tweet['id_str'] . ' is not exists in database');
                        $date = new \DateTime($tweet['created_at']);
                        $date->setTimeZone(new \DateTimeZone('Europe/Kaliningrad'));
                        Event::create(array(
                            'type' => 'RetweetAdded',
                            'tweet_id' => $tweet['id_str'],
                            'created' => $date->format('Y-m-d H:i:s')
                        ))->save();
                        $this->out('Event saved');
                    }else {
                        $this->out('Event already in database');
                    }
                    $this->out('checking if cache for tweet html exists in Rcache');
                    if(!isset($tweetsDump[$tweet['id_str']])) {
                        $this->out('Html cache is not exists');
                        $params = array('rpp' => 1, 'id' => $tweet['id_str'], 'include_entities' => false);
                        $code = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/statuses/oembed.json', $params, false);
                        if($code == 200) {
                            $this->out('Got the data, saving to cache');
                            $embeddata = json_decode($tmhOAuth->response['response'], true);
                            $tweetsDump[$tweet['id_str']] = $embeddata['html'];
                        }else {
                            $this->out('Error getting embed tweet');
                            var_dump(json_decode($tmhOAuth->response['response'], true));
                        }
                    }else {
                        $this->out('Html cache already exists');
                    }
                }
            }
            if(!empty($tweetsDump)) {
                $this->out('Rewriting cache');
                Rcache::write('RetweetsFeed', $tweetsDump);
            }else {
                $this->out('No data to write');
            }
        }else {
            $this->out('Error gettings latest tweets');
            var_dump(json_decode($tmhOAuth->response['response'], true));
        }
    }

}