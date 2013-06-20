<?php
/**
 * Slicedup: a fancy tag line here
 *
 * @copyright	Copyright 2010, Paul Webster / Slicedup (http://slicedup.org)
 * @license 	http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace slicedup_behaviors\models;

/**
 * Model class
 *
 * @description Extended model class to provide auto loading and convenience
 *              methods for attaching behaviors
 *
 * @package 	slicedup_behaviors
 */
class Model extends \lithium\data\Model {

	/**
	 * Behaviors to be aplied to this model
	 *
	 * @var array
	 */
	protected static $_behaviors = array();

	/**
	 * Overidden __init to attach behaviors
	 *
	 */
	public static function __init() {
		static::_isBase(__CLASS__, true);
		if (static::_isBase()) {
			return;
		}
		parent::__init();
		if (!empty(static::$_behaviors)) {
			foreach (static::$_behaviors as $behavior => $config) {
				if (is_numeric($behavior)) {
					$behavior = $config;
					$config = array();
				}
				if ($config === false) {
					$config = array('apply' => false);
				}
				static::attachBehavior($behavior, $config);
			}
		}
	}

	/**
	 * Fetch an instance of the specifeied behavior for this model
	 *
	 * @param string $behavior full name spaced classpath of the behavior
	 */
	public static function &behavior($behavior){
		$model = get_called_class();
		return $behavior::with($model);
	}

	/**
	 * Attach a behavior to this model
	 *
	 * @param string $behavior full name spaced classpath of the behavior
	 * @param array $config config options to assign to the behavior instance
	 */
	public static function &attachBehavior($behavior, array $config = array()){
		$model = get_called_class();
		return $behavior::attach($model, $config);
	}

	/**
	 * Detach behavior from this model
	 *
	 * @param string $behavior full name spaced classpath of the behavior
	 */
	public static function detachBehavior($behavior){
		$model = get_called_class();
		return $behavior::detach($model);
	}
}

?>