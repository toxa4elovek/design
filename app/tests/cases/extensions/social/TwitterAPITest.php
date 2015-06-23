<?php

namespace app\tests\cases\extensions\social;

use app\extensions\social\TwitterAPI;
use app\extensions\tests\AppUnit;

class TwitterAPITest extends AppUnit {

    public $api = null;

    public function setUp() {
        $this->api = new TwitterAPI();
    }

    public function testPostMessageToPage() {
        $data = array('message' => 'Фриланс под вишнями', 'picture' => '/var/godesigner/webroot/events/46219a254ac6c92dde243164a7398065_middleFeed.jpg');
        $result = $this->api->sendTweet($data['message'], $data['picture']);
        var_dump($result);
        $this->assertTrue(is_array($result));
        $this->assertTrue(is_string($result));
        var_dump($result);
    }

}