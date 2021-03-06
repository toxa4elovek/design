<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmitriynyu
 * Date: 12/15/11
 * Time: 2:23 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\extensions\helper;

if (!function_exists('mb_ucfirst')) {
    function mb_ucfirst($string, $enc = 'UTF-8')
    {
        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) .
            mb_substr($string, 1, mb_strlen($string, $enc), $enc);
    }
}

class PitchTitleFormatter extends \lithium\template\Helper
{

    public function renderTitle($title, $length = 0)
    {
        $title = mb_ucfirst($title);
        $title = preg_replace('@"(.+?)"@', '«$1»', $title);
        preg_match('@«(.+?)»@', $title, $matches);
        if ($matches) {
            $title = preg_replace('@' . $matches[1] . '@', mb_ucfirst($matches[1]), $title);
        }
        if ($length > 0 && mb_strlen($title, 'UTF-8') > $length) {
            $title = mb_substr($title, 0, $length - 1, 'UTF-8') . '…';
        }
        return $title;
    }
}
