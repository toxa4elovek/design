<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Url;
use app\extensions\storage\Rcache;

class UrlTest extends AppUnit {

    public function setUp() {
        $this->Url = new Url();
        Rcache::init();
        $this->rollUp(array('Url'));
    }

    public function tearDown() {
        Rcache::flushdb();
        $this->rollDown(array('Url'));
    }

    public function testCheck() {
        $result = $this->Url->check('http://www.yandex.ru');
        $this->assertFalse($result);

        $result = $this->Url->check('http://www.google.com');
        $this->assertTrue($result);
    }

    public function testGet() {
        $result = $this->Url->get('aS1fd3');
        $this->assertEqual('http://www.google.com', $result);

        $result = $this->Url->get('fakeShortUrl');
        $this->assertIdentical(null, $result);
    }

    public function testCreateNew() {
        $result = Url::createNew('http://www.google.com');
        $this->assertEqual('', $result);
    }

    public function testGenerateUrl () {
        $result = $this->Url->generateUrl();
        $this->assertTrue(is_string($result));
        $this->assertTrue(mb_strlen($result, 'UTF-8'));
    }

}