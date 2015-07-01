<?php

namespace app\tests\cases\extensions\social;

use app\extensions\social\TwitterAPI;
use app\extensions\tests\AppUnit;

class TwitterAPITest extends AppUnit {

    public $api = null;

    public function setUp() {
        $this->api = new TwitterAPI();
    }

    /*public function testPostMessageToPage() {
        $data = array('message' => 'Заполним бриф', 'picture' => '/Users/dima/www/godesigner/app/webroot/img/brief.png');
        $result = $this->api->postMessageToPage($data);
        $this->assertTrue(is_string($result));
        $this->assertTrue(is_numeric($result));
    }*/

    public function testSearch() {
        $result = $this->api->search();
    }

}