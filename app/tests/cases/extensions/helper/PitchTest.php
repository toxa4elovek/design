<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\tests\AppUnit;
use app\extensions\helper\Pitch;
use app\models\Pitch as PitchModel;

class PitchTest extends AppUnit {

    #protected $_user_model = 'app\tests\mocks\template\helper\MockUserModel';

    /**
     * Test object instance.
     *
     * @var object
     */
    public $user = null;

    /**
     * Initialize test by creating a new object instance with a default context.
     */
    public function setUp() {
        $this->pitch = new Pitch();
        $this->rollUp(array('Pitch'));
    }

    public function tearDown() {
        $this->rollDown(array('Pitch'));
    }

    public function testIsReadyForLogosaleAsObject() {
        $pitch = PitchModel::first(1);
        $pitch->status = 0;
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertFalse($result);
        $pitch->status = 1;
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertFalse($result);
        $pitch->status = 2;
        $pitch->totalFinishDate = date('Y-m-d H:i:s', time() - 29 * DAY);
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertFalse($result);

        $pitch->status = 2;
        $pitch->category_id = 7;
        $pitch->totalFinishDate = date('Y-m-d H:i:s', time() - 31 * DAY);
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertFalse($result);

        $pitch->status = 2;
        $pitch->category_id = 1;
        $pitch->private = 1;
        $pitch->totalFinishDate = date('Y-m-d H:i:s', time() - 31 * DAY);
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertFalse($result);

        $pitch->status = 2;
        $pitch->category_id = 2;
        $pitch->private = 0;
        $pitch->totalFinishDate = date('Y-m-d H:i:s', time() - 31 * DAY);
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertFalse($result);

        $pitch->status = 2;
        $pitch->category_id = 1;
        $pitch->private = 0;
        $pitch->totalFinishDate = date('Y-m-d H:i:s', time() - 31 * DAY);
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertTrue($result);
    }

    public function testIsReadyForLogosaleAsArray() {
        $pitch = PitchModel::first(1);
        $pitch = $pitch->data();
        $pitch['status'] = 0;
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertFalse($result);
        $pitch['status'] = 1;
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertFalse($result);
        $pitch['status'] = 2;
        $pitch['totalFinishDate'] = date('Y-m-d H:i:s', time() - 29 * DAY);
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertFalse($result);

        $pitch['status'] = 2;
        $pitch['category_id'] = 7;
        $pitch['totalFinishDate'] = date('Y-m-d H:i:s', time() - 31 * DAY);
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertFalse($result);

        $pitch['status'] = 2;
        $pitch['category_id'] = 1;
        $pitch['private'] = 1;
        $pitch['totalFinishDate'] = date('Y-m-d H:i:s', time() - 31 * DAY);
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertFalse($result);

        $pitch['status'] = 2;
        $pitch['category_id'] = 2;
        $pitch['private'] = 0;
        $pitch['totalFinishDate'] = date('Y-m-d H:i:s', time() - 31 * DAY);
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertFalse($result);

        $pitch['status'] = 2;
        $pitch['category_id'] = 1;
        $pitch['private'] = 0;
        $pitch['totalFinishDate'] = date('Y-m-d H:i:s', time() - 31 * DAY);
        $result = $this->pitch->isReadyForLogosale($pitch);
        $this->assertTrue($result);

        $fakePitch = new \stdClass;
        $result = $this->pitch->isReadyForLogosale($fakePitch);
        $this->assertFalse($result);
    }

}