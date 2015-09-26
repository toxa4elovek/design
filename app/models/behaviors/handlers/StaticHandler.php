<?php

namespace app\models\behaviors\handlers;

class StaticHandler extends \lithium\core\StaticObject {

	static public $defaults = array();

	static public function useHandler($behavior){
		return false;
	}
		
}