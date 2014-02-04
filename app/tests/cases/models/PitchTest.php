<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Pitch;
use lithium\storage\Session;

class PitchTest extends AppUnit {

    public function setUp() {
        $this->rollUp(array('Pitch', 'User'));
    }

    public function tearDown() {
        $this->rollDown(array('Pitch', 'User'));
        Session::clear();
    }

    public function testGetOwnerOfPitch() {
        $result = Pitch::getOwnerOfPitch(4);
        $this->assertFalse($result);
        $result = Pitch::getOwnerOfPitch(1);
        $this->assertTrue(is_object($result));
        $this->assertEqual(2, $result->id);
    }

    public function testGetSolutionsSortingOrder() {
        // Пользователь не-владелец питча
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder();
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

        // Пользователь владелец и питч еще проводится
        $pitch2 = Pitch::first(2);
        $pitch2->finishDate = date('Y-m-d H:i:s', time() + 3600);
        $pitch2->save();
        Session::write('user.id', 2);
        $result = $pitch2->getSolutionsSortingOrder();
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

        // Пользователь владелец и питч уже не проводится
        $pitch3 = Pitch::first(3);
        Session::write('user.id', 2);
        $result = $pitch3->getSolutionsSortingOrder();
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc'), $result);

        // Пользователь не владелец питча и питч уже не проводится
        $pitch3 = Pitch::first(3);
        Session::write('user.id', 4);
        $result = $pitch3->getSolutionsSortingOrder();
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc'), $result);

    }

}