<?php
namespace app\extensions\helper;

use \lithium\template\Helper;

/**
 * Class NameInflector
 *
 * Хелпер используется для отображение имени пользователя в скрытой форме,
 * например Дмитрий Иванов -> Дмитрий И.
 *
 * @package app\extensions\helper
 */
class NameInflector extends Helper {

	/**
	 * Метод вовзращяет две слитые строчки в олну строку, вторая строчка сокращаяется
	 *
	 * @param string $first
	 * @param string $second
	 * @param bool $companyName
	 * @return string
	 */
	public static function renderName($first, $second = '', $companyName = false) {
		if($companyName) {
			return $first;
		}
		if(strpos(trim($first), ' ')) {
			$exploded = explode(' ', $first);
			$first = $exploded[0];
			if(empty($second)) {
				$second = $exploded[1];
			}
		}

		if(strpos(trim($second), ' ') && empty($first)) {
			$exploded = explode(' ', $second);
			$first = $exploded[0];
			$second = $exploded[1];
		}

	    $dot = ($second == '') ? '' : '.';
		return trim(strip_tags($first . ' ' . mb_substr($second, 0, 1, 'utf-8') . $dot));
	}

}
