<?php

namespace app\models\behaviors\handlers;

class StaticHandler extends \lithium\core\StaticObject
{

    public static $defaults = [];

    public static function useHandler($behavior)
    {
        return false;
    }
}
