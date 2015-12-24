<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\tests\AppUnit;
use app\extensions\helper\Pitch;
use app\extensions\storage\Rcache;
use app\models\Pitch as PitchModel;
use app\models\SubscriptionPlan;

class PitchTest extends AppUnit
{

    #protected $_user_model = 'app\tests\mocks\template\helper\MockUserModel';

    /**
     * Test object instance.
     *
     * @var object
     */
    public $user = null;

    public $models = ['Pitch', 'Category', 'Solution'];

    /**
     * Initialize test by creating a new object instance with a default context.
     */
    public function setUp()
    {
        Rcache::init();
        SubscriptionPlan::config(['connection' => 'test']);
        $this->pitch = new Pitch();
        $this->rollUp($this->models);
    }

    public function tearDown()
    {
        Rcache::flushdb();
        $this->rollDown($this->models);
    }

    public function testIsReadyForLogosaleAsObject()
    {
        $pitch = PitchModel::first(1);
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

    public function testIsReadyForLogosaleAsArray()
    {
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

    public function testGetStatisticalAverages()
    {
        $this->assertEqual(0, $this->pitch->getStatisticalAverages(1, 'good'));
        $this->assertEqual(0, $this->pitch->getStatisticalAverages(1, 'normal'));
        $this->assertEqual(6, $this->pitch->getStatisticalAverages(1, 'minimal'));
        $this->assertEqual(0, $this->pitch->getStatisticalAverages(3, 'good'));
        $this->assertEqual(0, $this->pitch->getStatisticalAverages(3, 'normal'));
        $this->assertEqual(3, $this->pitch->getStatisticalAverages(3, 'minimal'));
        // cache
        $this->assertEqual(0, $this->pitch->getStatisticalAverages(1, 'good'));
        $this->assertEqual(0, $this->pitch->getStatisticalAverages(1, 'normal'));
        $this->assertEqual(6, $this->pitch->getStatisticalAverages(1, 'minimal'));
    }

    public function testGetChooseWinnerTime()
    {
        $project = PitchModel::first(7);
        $result = $this->pitch->getChooseWinnerTime($project);
        $this->assertNull($result);

        $project->status = 1;
        $project->awarded = 2222;
        $project->save();
        $result = $this->pitch->getChooseWinnerTime($project);
        $this->assertNull($result);

        $project->awarded = 0;
        $project->finishDate = '2016-10-10 00:00:00';
        $project->chooseWinnerFinishDate = '0000-00-00 00:00:00';
        $project->save();
        $result = $this->pitch->getChooseWinnerTime($project);
        $this->assertIdentical(strtotime($project->finishDate) + DAY * 4, $result);

        $project->chooseWinnerFinishDate = '2016-10-12 00:00:00';
        $project->save();
        $result = $this->pitch->getChooseWinnerTime($project);
        $this->assertIdentical(strtotime($project->chooseWinnerFinishDate), $result);
    }

    public function testGetPlanForPayment()
    {
        $id = SubscriptionPlan::getNextSubscriptionPlanId(1);
        SubscriptionPlan::setPlanForPayment($id, 2);
        $result = SubscriptionPlan::getPlanForPayment($id);
        $this->assertEqual(2, $result);

        $result = $this->pitch->getPlanForPayment($id);
        $this->assertEqual(2, $result);
    }

    public function testGetOpenGraphDescription()
    {
        $project = PitchModel::first(7);
        $result = $this->pitch->getOpenGraphDescription($project);
        $this->assertEqual("В течение 9 дней в проекте приняли участие 2 дизайнера, предложив 2 решения.", $result);
    }
}
