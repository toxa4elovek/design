<?php

namespace app\tests\mocks\template\helper;


class MockUserModel extends \lithium\core\StaticObject {
    public static $admins = array(1, 2);
    public static $editors = array(1, 2, 3, 4);
    public static $authors = array(5);

    public static function getAuthorsIds() {
        return self::$authors;
    }

    public static function getAdminsIds() {
        return self::$admins;
    }

    public static function getEditorsIds() {
        return self::$editors;
    }
}