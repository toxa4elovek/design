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
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created'=>'desc'), $result);

        // Пользователь не владелец питча и питч уже не проводится
        $pitch3 = Pitch::first(3);
        Session::write('user.id', 4);
        $result = $pitch3->getSolutionsSortingOrder();
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created'=>'desc'), $result);
    }

    public function testGetSolutionsSortingOrderWithParamNonClient() {
        // Пользователь не-владелец питча, рейтинг
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('rating');
        $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created'=>'desc'), $result);

        // Пользователь не-владелец питча, дата создания
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('created');
        $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

        // Пользователь не-владелец питча, лайки
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('likes');
        $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'likes' => 'desc', 'created'=>'desc'), $result);

        // Пользователь не-владелец питча, не существующий тип сортировки, питч еще идёт
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('non-existing-type');
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

        // Пользователь не владелец питча, не существующий тип сортировки, питч уже не идет
        $pitch3 = Pitch::first(3);
        $result = $pitch3->getSolutionsSortingOrder('non-existing-type');
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created'=>'desc'), $result);
    }

    public function testGetSolutionsSortingOrderWithParamClient() {
        Session::write('user.id', 2);
        // Пользователь владелец питча, рейтинг
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('rating');
        $this->assertEqual(array('awarded' => 'desc', 'hidden' => 'asc', 'nominated' => 'desc', 'rating' => 'desc', 'created'=>'desc'), $result);

        // Пользователь владелец питча, дата создания
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('created');
        $this->assertEqual(array('awarded' => 'desc', 'hidden' => 'asc', 'nominated' => 'desc', 'created' => 'desc'), $result);

        // Пользователь владелец питча, лайки
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder('likes');
        $this->assertEqual(array('awarded' => 'desc', 'hidden' => 'asc', 'nominated' => 'desc', 'likes' => 'desc', 'created'=>'desc'), $result);
    }

    public function testGetSolutionsSortingOrderWithArrayParam() {
        // Пользователь не-владелец питча, лайки
        $pitch1 = Pitch::first(1);
        $result = $pitch1->getSolutionsSortingOrder(array('sorting' => 'likes'));
        $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'likes' => 'desc', 'created'=>'desc'), $result);

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
        $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created'=>'desc'), $result);
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

    public function testGetQueryPageNum() {
        // по умолчанию должна быть единичка
        $pageNum = Pitch::getQueryPageNum();
        $this->assertEqual(1, $pageNum);
        // если входящий параметр - не число, то возвращяем значение по умолчанию
        $pageNum = Pitch::getQueryPageNum(null);
        $this->assertEqual(1, $pageNum);
        $pageNum = Pitch::getQueryPageNum(false);
        $this->assertEqual(1, $pageNum);
        $pageNum = Pitch::getQueryPageNum('string');
        $this->assertEqual(1, $pageNum);
        // если вводим не целое или отрицальное число, возвращаем целое и положительно
        $pageNum = Pitch::getQueryPageNum(-5);
        $this->assertEqual(5, $pageNum);
        $pageNum = Pitch::getQueryPageNum(5.25);
        $this->assertEqual(5, $pageNum);
        // если вводим просто страницу, возвращаем её
        $pageNum = Pitch::getQueryPageNum(5);
        $this->assertEqual(5, $pageNum);
    }
	
	public function testGetQueryPriceFilter() {
		// По умолчанию
		$this->assertEqual(array(),Pitch::getQueryPriceFilter());
		// Цена от 3000 - 10000
		$this->assertEqual(array('price' => array('>' => 3000, '<=' => 10000)),Pitch::getQueryPriceFilter(1));
		// Цена от 10000 - 20000
		$this->assertEqual(array('price' => array('>' => 10000, '<=' => 20000)),Pitch::getQueryPriceFilter(2));
		// Цена больше 20000
		$this->assertEqual(array('price' => array('>' => 20000)),Pitch::getQueryPriceFilter(3));
        // Неопределенный диапозон
        $this->assertEqual(array(), Pitch::getQueryPriceFilter(200));
	}
	
	public function testGetQueryTimeframe() {
		// По умолчанию
		$this->assertEqual(array(),Pitch::getQueryTimeframe());
		// 3 дня
		$this->assertEqual(array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 3)))),Pitch::getQueryTimeframe(1));
		// 7 дней
		$this->assertEqual(array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 7)))),Pitch::getQueryTimeframe(2));
		// 10 дней
		$this->assertEqual(array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 10)))),Pitch::getQueryTimeframe(3));
		// 14 дней
		$this->assertEqual(array('finishDate' => array('=>' => date('Y-m-d H:i:s', time() + (DAY * 14)))),Pitch::getQueryTimeframe(4));
        // Неопределенный таймфрейм
        $this->assertEqual(array(), Pitch::getQueryTimeframe(200));
	}
	
	public function testGetQuerySearchTerm() {
		$this->assertEqual('',Pitch::getQuerySearchTerm());
		$this->assertEqual('',Pitch::getQuerySearchTerm(array()));
		$this->assertEqual(array('Pitch.title' => array('REGEXP' => 'тест тест тест|Тест тест тест|ТЕСТ ТЕСТ ТЕСТ')),Pitch::getQuerySearchTerm('Тест тест тест'));
		$this->assertEqual(array('Pitch.title' => array('REGEXP' => 'тест тест тест|Тест тест тест|ТЕСТ ТЕСТ ТЕСТ')),Pitch::getQuerySearchTerm('тест тест тест'));
		$this->assertEqual(array('Pitch.title' => array('REGEXP' => 'test test test|Test test test|TEST TEST TEST')),Pitch::getQuerySearchTerm('test test test'));
        $this->assertEqual(array(),Pitch::getQuerySearchTerm(array('test' => 'test')));
        $this->assertEqual(array(),Pitch::getQuerySearchTerm(null));
	}
	
	public function testGetQueryOrder() {
		$this->assertEqual(array('free' => 'desc','price' => 'desc','started' => 'desc'),Pitch::getQueryOrder(null));
		$this->assertEqual(array('free' => 'desc','price' => 'desc','started' => 'desc'),Pitch::getQueryOrder(false));
		$this->assertEqual(array('title' => 'desc','started' => 'desc'),Pitch::getQueryOrder(array('title'=>'desc')));
		$this->assertEqual(array('title' => 'asc','started' => 'desc'),Pitch::getQueryOrder(array('title'=>'asc')));
		$this->assertEqual(array('category_id' => 'desc','started' => 'desc'),Pitch::getQueryOrder(array('category'=>'desc')));
		$this->assertEqual(array('category_id' => 'asc','started' => 'desc'),Pitch::getQueryOrder(array('category'=>'asc')));
		$this->assertEqual(array('ideas_count' => 'desc','started' => 'desc'),Pitch::getQueryOrder(array('ideas_count'=>'desc')));
		$this->assertEqual(array('ideas_count' => 'asc','started' => 'desc'),Pitch::getQueryOrder(array('ideas_count'=>'asc')));
		$this->assertEqual(array('(finishDate - \'' . date('Y-m-d H:i:s') . '\')' => 'desc'),Pitch::getQueryOrder(array('finishDate'=>'desc')));
		$this->assertEqual(array('(finishDate - \'' . date('Y-m-d H:i:s') . '\')' => 'asc'),Pitch::getQueryOrder(array('finishDate'=>'asc')));
		$this->assertEqual(array('free' => 'desc','price' => 'desc','started' => 'desc'),Pitch::getQueryOrder(array('price'=>'desc')));
		$this->assertEqual(array('free' => 'desc','price' => 'asc','started' => 'desc'),Pitch::getQueryOrder(array('price'=>'asc')));
        $this->assertEqual(array('free' => 'desc','price' => 'desc','started' => 'desc'),Pitch::getQueryOrder(array('fake'=>'desc')));
        $this->assertEqual(array('free' => 'desc','price' => 'desc','started' => 'desc'),Pitch::getQueryOrder(array('fake'=>'asc')));
	}
	
	public function testGetQueryCategory() {
		$this->assertEqual(array(),Pitch::getQueryCategory(null));
		$this->assertEqual(array(),Pitch::getQueryCategory(false));
		$this->assertEqual(array(),Pitch::getQueryCategory(''));
		$this->assertEqual(array('category_id' => 2),Pitch::getQueryCategory(2));
        $this->assertEqual(array(),Pitch::getQueryCategory(200));
	}
	
	public function testGetQueryType() {
		// index
		$this->assertEqual(array('OR' => array(array('awardedDate >= \'' . date('Y-m-d H:i:s', time() - DAY) . '\''),array('status < 2 AND awarded = 0'))),Pitch::getQueryType(null));
		// Завершенные
		$this->assertEqual(array('OR' => array(array('status = 2'), array('(status = 1 AND awarded > 0)'))),Pitch::getQueryType('finished'));
		// Текущие
		$this->assertEqual(array('status' => array('<' => 2), 'awarded' => 0),Pitch::getQueryType('current'));
		// Все
		$this->assertEqual(array(),Pitch::getQueryType('all'));
        // неопределенный параметр
        $this->assertEqual(array('OR' => array(array('awardedDate >= \'' . date('Y-m-d H:i:s', time() - DAY) . '\''),array('status < 2 AND awarded = 0'))), Pitch::getQueryType('fakeParam'));
	}
}