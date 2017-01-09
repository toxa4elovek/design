<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmitriynyu
 * Date: 12/15/11
 * Time: 2:23 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\extensions\helper;

class NumInflector extends \lithium\template\Helper
{

    public function formatString($value, $strings)
    {
        $defaults = [
            'string' => '',
            'first' => '',
            'second' => '',
            'third' => ''];
        $strings += $defaults;
        $root = $strings['string'];
        if ($value % 10 == 1 && $value % 100 != 11) {
            $index = 'first';
        } elseif ($value % 10 >= 2 && $value % 10 <= 4 && ($value % 100 < 10 || $value % 100 >= 20)) {
            $index = 'second';
        } else {
            $index = 'third';
        }
        if (is_array($strings['string'])) {
            $root = $strings['string'][$index];
        }
        $string = $root . $strings[$index];
        return $string;
    }
}
