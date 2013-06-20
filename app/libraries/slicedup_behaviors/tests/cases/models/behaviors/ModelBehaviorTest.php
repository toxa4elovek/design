<?php
/**
 * Slicedup: a fancy tag line here
 *
 * @copyright	Copyright 2010, Paul Webster / Slicedup (http://slicedup.org)
 * @license 	http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace slicedup_behaviors\tests\cases\models\behaviors;

use \lithium\data\Model;
use \lithium\data\Connections;
use \lithium\analysis\Inspector;
use \slicedup_behaviors\tests\mocks\data\MockModel;
use \slicedup_behaviors\tests\mocks\data\MockOtherModel;
use \slicedup_behaviors\tests\mocks\data\MockModelBehavior;
use \slicedup_behaviors\tests\mocks\data\MockOtherModelBehavior;

class ModelBehaviorTest extends \lithium\test\Unit {

	protected static $model = '\slicedup_behaviors\tests\mocks\data\MockModel';
	protected static $otherModel = '\slicedup_behaviors\tests\mocks\data\MockOtherModel';

	public function _init() {
		Connections::add('mock-source', array('type' => '\lithium\tests\mocks\data\MockSource'));
	}

	public function testAssignment() {
		$behavior =& MockModelBehavior::attach(static::$model);
		$this->assertTrue($behavior instanceof MockModelBehavior);

		$with =& MockModelBehavior::with(static::$model);
		$this->assertIdentical($behavior, $with);
		$this->assertEqual(static::$model, $behavior->model());
	}

	public function testAttachment() {
		$this->assertTrue(MockModelBehavior::attached(static::$model));
		$this->assertTrue(MockModelBehavior::attached('MockModel'));

		MockModelBehavior::detach(static::$model);
		$this->assertFalse(MockModelBehavior::attached(static::$model));
		$this->assertFalse(MockModelBehavior::attached('MockModel'));
		$this->assertFalse(MockModelBehavior::with(static::$model));

		MockModelBehavior::attach(static::$model);
		$this->assertTrue(MockModelBehavior::attached(static::$model));
		$this->assertTrue(MockModelBehavior::attached('MockModel'));
		$this->assertTrue(MockModelBehavior::with(static::$model) instanceof MockModelBehavior);
	}

	public function testApply() {
		$behavior =& MockModelBehavior::with(static::$model);
		$this->assertTrue($behavior->applied());
		$this->assertFalse($behavior->apply());

		MockModelBehavior::detach(static::$model);
		$behavior =& MockModelBehavior::attach(static::$model, array('apply' => false));
		$this->assertTrue($behavior->applied());

		$otherBehavior =& MockOtherModelBehavior::attach(static::$model, array('apply' => false));
		$this->assertFalse($otherBehavior->applied());
		$otherBehavior->apply();
		$this->assertTrue($otherBehavior->applied());
	}

	public function testAliases() {
		$with =& MockModelBehavior::with(static::$model);
		$this->assertIdentical($with, MockModelBehavior::with('MockModel'));
		$this->assertIdentical($with, MockModelBehavior::MockModel());

		$behavior =& MockModelBehavior::attach(static::$otherModel, array(
			'alias' => 'AliasedModel'
		));
		$this->assertEqual(static::$otherModel, $behavior->model());

		$with =& MockModelBehavior::with(static::$otherModel);
		$this->assertIdentical($with, MockModelBehavior::with('AliasedModel'));
		$this->assertIdentical($with, MockModelBehavior::AliasedModel());
		$this->assertIdentical($with, $behavior);
		$this->assertFalse(MockModelBehavior::with('MockOtherModel'));
	}
}

?>