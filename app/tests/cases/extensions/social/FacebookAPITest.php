<?php

namespace app\tests\cases\extensions\social;

use app\extensions\social\FacebookAPI;
use app\extensions\tests\AppUnit;

class FacebookAPITest extends AppUnit {

    public $api = null;

    public function setUp() {
        $this->api = new FacebookAPI();
    }
/*
    public function testGetAccessToken() {
        $expected = 'CAAC4agxXZBsMBAGHKxbBpuj5VxUTqIfYC1UJEie7Krc6ZCNqZCZC8RU3nuGShZCT3jt0joJQitVp8h9aTuVotxzhiFONZC6ST8cAKmomDwEVTWwre6qWJXUh0ZCzCPDkRm7dRXZB3H6ZAkihieQif0oIpAMWHURDgpHAu3zmfCZC7qUb9jwZCpI7hnVEipXFL1fPC0KDAVi7eEDvxD81b7OlVnl';
        $this->assertEqual($expected, $this->api->getAccessToken());
    }

    public function testPostMessageToPage() {
        $data = array('message' => 'Хорошая пара', 'picture' => 'http://tutdesign.ru/wp-content/uploads/2015/06/119-610x407.jpg');
        $result = $this->api->postMessageToPage($data);
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('id', $result));
        var_dump($result);
    }
*/
}