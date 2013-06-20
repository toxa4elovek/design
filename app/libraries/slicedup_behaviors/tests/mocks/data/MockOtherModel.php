<?php
/**
 * Slicedup: a fancy tag line here
 *
 * @copyright	Copyright 2010, Paul Webster / Slicedup (http://slicedup.org)
 * @license 	http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace slicedup_behaviors\tests\mocks\data;

use slicedup_behaviors\tests\mocks\data\MockModelBehavior;

class MockOtherModel extends \lithium\data\Model {

	protected $_schema = array(
		'id' => array('type' => 'integer'),
		'title' => array('type' => 'string'),
		'created' => array('type' => 'datetime')
	);

	protected $_meta = array('connection' => 'mock-source');
}

?>