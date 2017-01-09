<?php

namespace app\tests\mocks\template\helper;

class MockExpertModel extends \lithium\core\StaticObject
{

    public static function getExpertUserIds()
    {
        return [1, 2, 3];
    }
}
