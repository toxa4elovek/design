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

    public function testSimpleGetSolutionsSortingOrder() {
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

    public function testGetSolutionsSortingOrderWithParamNonClient() {
        // Пользователь не-владелец питча, рейтинг
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('rating');
        $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc'), $result);

        // Пользователь не-владелец питча, дата создания
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('created');
        $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

        // Пользователь не-владелец питча, лайки
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('likes');
        $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'likes' => 'desc'), $result);

        // Пользователь не-владелец питча, не существующий тип сортировки, питч еще идёт
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('non-existing-type');
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

        // Пользователь не владелец питча, не существующий тип сортировки, питч уже не идет
        $pitch3 = Pitch::first(3);
        $result = $pitch3->getSolutionsSortingOrder('non-existing-type');
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc'), $result);
    }

    public function testGetSolutionsSortingOrderWithParamClient() {
        Session::write('user.id', 2);
        // Пользователь владелец питча, рейтинг
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('rating');
        $this->assertEqual(array('awarded' => 'desc', 'hidden' => 'asc', 'nominated' => 'desc', 'rating' => 'desc'), $result);

        // Пользователь владелец питча, дата создания
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('created');
        $this->assertEqual(array('awarded' => 'desc', 'hidden' => 'asc', 'nominated' => 'desc', 'created' => 'desc'), $result);

        // Пользователь владелец питча, лайки
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('likes');
        $this->assertEqual(array('awarded' => 'desc', 'hidden' => 'asc', 'nominated' => 'desc', 'likes' => 'desc'), $result);
    }

    public function testGetSolutionsSortingOrderWithArrayParam() {
        // Пользователь не-владелец питча, лайки
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder(array('sorting' => 'likes'));
        $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'likes' => 'desc'), $result);

        // Пользователь не-владелец питча, не существующий тип сортировки, питч еще идёт
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder(array('sorting' => 'non-existing-type'));
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

        // Пользователь не-владелец питча, не существующий тип сортировки, питч еще идёт
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder(array('no-sorting-key' => false));
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

        // Пользователь не-владелец питча, не существующий тип сортировки, питч уже не идёт
        $pitch3 = Pitch::first(3);
        $result = $pitch3->getSolutionsSortingOrder(array('sorting' => 'non-existing-type'));
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc'), $result);
    }

    public function testGetSolutionsSortTypeWithParams() {
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortName('rating');
        $this->assertEqual('rating', $result);

        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortName('created');
        $this->assertEqual('created', $result);

        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortName('likes');
        $this->assertEqual('likes', $result);

        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortName('non-existant-type');
        $this->assertEqual('created', $result);
    }

    public function testGetSolutionSortNameWithoutParams() {
        // Пользователь не-владелец питча
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortName();
        $this->assertEqual('created', $result);

        // Пользователь владелец и питч еще проводится
        $pitch2 = Pitch::first(2);
        $pitch2->finishDate = date('Y-m-d H:i:s', time() + 3600);
        $pitch2->save();
        Session::write('user.id', 2);
        $result = $pitch2->getSolutionsSortName();
        $this->assertEqual('created', $result);

        // Пользователь владелец и питч уже не проводится
        $pitch3 = Pitch::first(3);
        Session::write('user.id', 2);
        $result = $pitch3->getSolutionsSortName();
        $this->assertEqual('rating', $result);

        // Пользователь не владелец питча и питч уже не проводится
        $pitch3 = Pitch::first(3);
        Session::write('user.id', 4);
        $result = $pitch3->getSolutionsSortName();
        $this->assertEqual('rating', $result);
    }

    public function testGetSolutionsSortNameWithArray() {
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortName(array('sorting' => 'likes'));
        $this->assertEqual('likes', $result);

        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortName(array('sorting' => 'non-existant-type'));
        $this->assertEqual('created', $result);

        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortName(array('no-sorting-key' => false));
        $this->assertEqual('created', $result);
    }

}