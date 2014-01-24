<?php

namespace app\tests\mocks\template\helper;


class MockUserModel extends \lithium\core\StaticObject {
    public static $admins = array(1, 2);
    public static $editors = array(1, 2, 3, 4);
}