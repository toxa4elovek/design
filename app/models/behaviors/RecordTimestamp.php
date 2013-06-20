<?php
/**
 * Slicedup: a fancy tag line here
 *
 * @copyright	Copyright 2010, Paul Webster / Slicedup (http://slicedup.org)
 * @license 	http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\models\behaviors;

/**
 * RecordTimestamp Behavior
 *
 * @description	RecordTimestamp is a model behavior that auto populates record
 * 				fields with formated timestamps on save operations when records
 * 				are created & updated.
 *
 * @package 	slicedup_behaviors
 */
class RecordTimestamp extends \app\models\behaviors\ModelBehavior{

	/**
	 * Configured timestamp fields and format
	 *
	 * @var array
	 */
	protected $_config = array(
		'create' => array(
			'field' => 'created',
			'format' => null,
		),
		'update' => array(
			'field' => 'updated',
			'format' => null,
		),
		'format' => 'Y-m-d H:i:s'
	);

	/**
	 * Callbacks for this behavior
	 *
	 * @var array
	 */
	protected static $_callbacks = array(
		'save' => array(
			'before' => true,
			'after' => true
		)
	);

	/**
	 * Initialize this behavior
	 *
	 */
	protected function _init() {
		$model = $this->_model;
		$schema = $model::schema();
		$config = $this->config();
		foreach (array('create', 'update') as $action) {
			if ($config[$action]) {
				$field = is_array($config[$action]) ? $config[$action]['field'] : $config[$action];
				if (!array_key_exists($field, $schema)) {
					$config[$action] = false;
				}
			}
		}
		$this->config($config);
		if ($config['create'] || $config['update']) {
			$this->apply(array(true));
		}
	}

	/**
	 * Before save call back to create/update the timestamps
	 *
	 * @param array $params
	 */
	public function beforeSave($params) {		
		$model = $this->_model;
		$id = $model::key($params['record']);
		$config = $this->config();
		extract($config);
		$time = time();
		if ($update) {
			if (!is_array($update)) {
				$update = array('field' => $update, 'format' => null);
			}
			$params['record']->{$update['field']} = date(($update['format']?:$format), $time);
		}
		if (isset($params['record']->{$id}) && $params['record']->{$id}) {
			$create = false;
		}
		if ($create) {
			if (!is_array($create)) {
					$create = array('field' => $create, 'format' => null);
			}
			$params['record']->{$create['field']} = date(($create['format']?:$format), $time);
		}
		return $params;
	}
}

?>