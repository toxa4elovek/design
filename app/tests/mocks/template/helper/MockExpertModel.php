<?php

namespace app\tests\mocks\template\helper;


class MockExpertModel extends \lithium\core\StaticObject {

    public static function getExpertUserIds() {
        return array(1, 2, 3);
    }

}