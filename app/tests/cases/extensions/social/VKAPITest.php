<?php

namespace app\tests\cases\extensions\social;

use app\extensions\social\VKAPI;
use app\extensions\tests\AppUnit;

class VKAPITest extends AppUnit
{

    public $api = null;

    public function setUp()
    {
        $this->api = new VKAPI();
    }

    public function testPostMessageToPage()
    {
        $data = ['message' => 'Заполним бриф', 'picture' => 'http://godesigner.ru/img/brief.png'];
        $result = $this->api->postMessageToPage($data);
        $this->assertTrue(is_numeric($result));
    }
}
