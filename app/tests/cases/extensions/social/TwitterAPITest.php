<?php

namespace app\tests\cases\extensions\social;

use app\extensions\social\TwitterAPI;
use app\extensions\tests\AppUnit;

class TwitterAPITest extends AppUnit {

    public $api = null;

    public function setUp() {
        $this->api = new TwitterAPI();
    }

    function testPostMessageToPage() {
        $data = array(
            'message' => 'Заполним бриф',
            'picture' => '/Users/dima/www/godesigner/app/webroot/img/brief.png'
        );
        $result = $this->api->postMessageToPage($data);
        $this->assertTrue(is_string($result));
        $this->assertTrue(is_numeric($result));
    }

    /*
    public function testSearch() {
        $result = $this->api->search('Какой ты дизайнер на самом деле', function($object) {
            $decoded = json_decode($object->response['response'], true);
            $retweetsIds = array();
            $toRetweetIds = array();
            foreach ($decoded['statuses'] as $tweet) {
                if(($tweet['user']['id_str'] == '513074899') and (isset($tweet['retweeted_status']))) {
                    $retweetsIds[] = $tweet['retweeted_status']['id_str'];
                    continue;
                }
                if(in_array($tweet['id_str'], $retweetsIds)) {
                    continue;
                }
                $toRetweetIds[] = $tweet['id_str'];
            }
            foreach($toRetweetIds as $idStr) {
                $params = array('id' => $idStr);
                $object->user_request(array(
                    'method' => 'POST',
                    'url' => $object->url('1.1/statuses/retweet/' . $idStr . '.json'),
                    'params' => $params,
                ));
            }
            return count($toRetweetIds);
        });
    }
    */

}