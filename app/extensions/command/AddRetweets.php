<?php

namespace app\extensions\command;

use app\models\Event;
use app\extensions\storage\Rcache;
use app\extensions\social\TwitterAPI;

class AddRetweets extends \app\extensions\command\CronJob {

    public function run() {
        Rcache::init();
        $twitterAPI = new TwitterAPI();
        $params = array('count' => 100, 'screen_name' => 'Go_Deer', 'include_entities' => true);
        $code = $twitterAPI->apiObject->user_request(array(
            'method' => 'GET',
            'url' => $twitterAPI->apiObject->url('1.1/statuses/user_timeline.json'),
            'params' => $params
        ));
        if ($code == 200) {
            $data = json_decode($twitterAPI->apiObject->response['response'], true);
        }
        $hashTags = array('работадлядизайнеров');
        $x = 0;
        $url = '';
        $countTags = count($hashTags);
        foreach ($hashTags as $tag) {
            ++$x;
            $url .= $countTags > $x ? '%23' . urlencode($tag) . '+' : '%23' . urlencode($tag);
        }
        $params = array('rpp' => 100, 'q' => $url, 'include_entities' => true);
        $codeTag = $twitterAPI->apiObject->user_request(array(
            'method' => 'GET',
            'url' => $twitterAPI->apiObject->url('https://api.twitter.com/1.1/search/tweets.json'),
            'params' => $params
        ));
        if ($code == 200 && $codeTag == 200) {
            $dataTag = json_decode($twitterAPI->apiObject->response['response'], true);
            foreach ($dataTag['statuses'] as $tweet) {
                $data[] = $tweet;
            }
            //$events = Event::all(array('conditions' => array('type' => 'RetweetAdded')));
            $tweetsDump = Rcache::read('RetweetsFeed');
            foreach ($data as $tweet) {
                if (isset($tweet['retweeted_status']) || self::in_array_r('работадлядизайнеров', $tweet['entities']['hashtags']) || (strpos($tweet['text'], ' победил в проекте ') !== false || strpos($tweet['text'], ' заработал ') !== false)) {
                    $this->out('Dumping tweet data...');
                    //var_dump($tweet);
                    $this->out('checking if tweet event exists in database...');
                    if (!$tweetEvent = Event::first(array('conditions' => array('tweet_id' => $tweet['id_str'])))) {
                        $this->out('Tweet ' . $tweet['id_str'] . ' is not exists in database');
                        $date = new \DateTime($tweet['created_at']);
                        $date->setTimeZone(new \DateTimeZone('Europe/Kaliningrad'));
                        Event::create(array(
                            'type' => 'RetweetAdded',
                            'tweet_id' => $tweet['id_str'],
                            'created' => $date->format('Y-m-d H:i:s')
                        ))->save();
                        $this->out('Event saved');
                    } else {
                        $this->out('Event already in database');
                    }
                    $this->out('checking if cache for tweet html exists in Rcache');
                    if (!isset($tweetsDump[$tweet['id_str']])) {
                        $this->out('Html cache is not exists');
                        $params = array('rpp' => 1, 'id' => $tweet['id_str'], 'maxwidth' => '550', 'include_entities' => false);
                        $code = $twitterAPI->apiObject->user_request(array(
                            'method' => 'GET',
                            'url' => $twitterAPI->apiObject->url('1.1/statuses/oembed.json'),
                            'params' => $params
                        ));
                        if ($code == 200) {
                            $this->out('Got the data, saving to cache');
                            $embeddata = json_decode($twitterAPI->apiObject->response['response'], true);
                            $tweetsDump[$tweet['id_str']] = $embeddata['html'];
                        } else {
                            $this->out('Error getting embed tweet');
                            var_dump(json_decode($twitterAPI->apiObject->response['response'], true));
                        }
                    } else {
                        $this->out('Html cache already exists');
                    }
                }
            }
            if (!empty($tweetsDump)) {
                $this->out('Rewriting cache');
                Rcache::write('RetweetsFeed', $tweetsDump);
            } else {
                $this->out('No data to write');
            }
        } else {
            $this->out('Error gettings latest tweets');
            var_dump(json_decode($twitterAPI->apiObject->response['response'], true));
        }
    }

    private function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $k => $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array_r($needle, $item, $strict))) {
                return $k;
            }
        }
        return false;
    }

}
