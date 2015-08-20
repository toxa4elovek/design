<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmitriynyu
 * Date: 12/15/11
 * Time: 2:23 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\extensions\helper;

class NameInflector extends \lithium\template\Helper {

	static function renderName($first, $second = '') {
		if(preg_match('@\W\s\W@', $first)) {
			$exploded = explode(' ', $first);
			$first = $exploded[0];
			if(empty($second)) {
				$second = $exploded[1];
			}
		}


	    $dot = ($second == '') ? '' : '.';
		return strip_tags($first . ' ' . mb_substr($second, 0, 1, 'utf-8') . $dot);
	}

}
