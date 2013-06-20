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
use \slicedup_behaviors\models\behaviors\RecordTimestamp;

class RecordTimestampTest extends \lithium\test\Unit {

	protected static $model = '\slicedup_behaviors\tests\mocks\data\MockModel';

	protected static $otherModel = '\slicedup_behaviors\tests\mocks\data\MockOtherModel';

	public function _init() {
		Connections::add('mock-source', array('type' => '\lithium\tests\mocks\data\MockSource'));
	}

	public function testTimestamps(){}
}

?>