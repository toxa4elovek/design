<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Pitch;
use lithium\storage\Session;
use app\models\Grade;
use app\extensions\storage\Rcache;

class PitchTest extends AppUnit {

    public function setUp() {
        Rcache::init();
        $this->rollUp(array('Pitch', 'User','Solution','Comment','Transaction','Paymaster','Payanyway', 'Note', 'Category'));
    }

    public function tearDown() {
        Rcache::flushdb();
        $this->rollDown(array('Pitch', 'User','Solution','Comment','Transaction','Paymaster','Payanyway', 'Note', 'Grade', 'Category'));
        Session::clear();
    }

    public function testGetOwnerOfPitch() {
        // Тут надо убедиться, что строчкой ниже айди не существующй
        $result = Pitch::getOwnerOfPitch(10000);
        $this->assertFalse($result);
        $result = Pitch::getOwnerOfPitch(1);
        $this->assertTrue(is_object($result));
        $this->assertEqual(2, $result->id);
    }
    /*
        public function testSimpleGetSolutionsSortingOrder() {
            // Пользователь не-владелец питча
            $pitch1 = Pitch::first(1);
            $result = $pitch1->getSolutionsSortingOrder();
            $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

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
            $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created' => 'desc'), $result);

            // Пользователь не владелец питча и питч уже не проводится
            $pitch3 = Pitch::first(3);
            Session::write('user.id', 4);
            $result = $pitch3->getSolutionsSortingOrder();
            $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created' => 'desc'), $result);
        }

        public function testGetSolutionsSortingOrderWithParamNonClient() {
            // Пользователь не-владелец питча, рейтинг
            $pitch1 = Pitch::first(1);
            $result = $pitch1->getSolutionsSortingOrder('rating');
            $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created' => 'desc'), $result);

            // Пользователь не-владелец питча, дата создания
            $pitch1 = Pitch::first(1);
            $result = $pitch1->getSolutionsSortingOrder('created');
            $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

            // Пользователь не-владелец питча, лайки
            $pitch1 = Pitch::first(1);
            $result = $pitch1->getSolutionsSortingOrder('likes');
            $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'likes' => 'desc', 'created' => 'desc'), $result);

            // Пользователь не-владелец питча, не существующий тип сортировки, питч еще идёт
            $pitch1 = Pitch::first(1);
            $result = $pitch1->getSolutionsSortingOrder('non-existing-type');
            $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

            // Пользователь не владелец питча, не существующий тип сортировки, питч уже не идет
            $pitch3 = Pitch::first(3);
            $result = $pitch3->getSolutionsSortingOrder('non-existing-type');
            $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created' => 'desc'), $result);
        }

        public function testGetSolutionsSortingOrderWithParamClient() {
            Session::write('user.id', 2);
            // Пользователь владелец питча, рейтинг
            $pitch1 = Pitch::first(1);
            $result = $pitch1->getSolutionsSortingOrder('rating');
            $this->assertEqual(array('awarded' => 'desc', 'hidden' => 'asc', 'nominated' => 'desc', 'rating' => 'desc', 'created' => 'desc'), $result);

            // Пользователь владелец питча, дата создания
            $pitch1 = Pitch::first(1);
            $result = $pitch1->getSolutionsSortingOrder('created');
            $this->assertEqual(array('awarded' => 'desc', 'hidden' => 'asc', 'nominated' => 'desc', 'created' => 'desc'), $result);

            // Пользователь владелец питча, лайки
            $pitch1 = Pitch::first(1);
            $result = $pitch1->getSolutionsSortingOrder('likes');
            $this->assertEqual(array('awarded' => 'desc', 'hidden' => 'asc', 'nominated' => 'desc', 'likes' => 'desc', 'created' => 'desc'), $result);
        }

        public function testGetSolutionsSortingOrderWithArrayParam() {
            // Пользователь не-владелец питча, лайки
            $pitch1 = Pitch::first(1);
            $result = $pitch1->getSolutionsSortingOrder(array('sorting' => 'likes'));
            $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'likes' => 'desc', 'created' => 'desc'), $result);

            // Пользователь не-владелец питча, не существующий тип сортировки, питч еще идёт
            $pitch1 = Pitch::first(1);
            $result = $pitch1->getSolutionsSortingOrder(array('sorting' => 'non-existing-type'));
            $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

            // Пользователь не-владелец питча, не существующий тип сортировки, питч еще идёт
            $pitch1 = Pitch::first(1);
            $result = $pitch1->getSolutionsSortingOrder(array('no-sorting-key' => false));
            $this->assertEqual(array('awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc'), $result);

            // Пользователь не-владелец питча, не существующий тип сортировки, питч уже не идёт
            $pitch3 = Pitch::first(3);
            $result = $pitch3->getSolutionsSortingOrder(array('sorting' => 'non-existing-type'));
            $this->assertEqual(array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc', 'created' => 'desc'), $result);
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
            $this->assertEqual(array(), Pitch::getQueryPriceFilter());
            // Цена от 3000 - 10000
            $this->assertEqual(array('price' => array('>' => 5000, '<=' => 10000)), Pitch::getQueryPriceFilter(1));
            // Цена от 10000 - 20000
            $this->assertEqual(array('price' => array('>' => 10000, '<=' => 20000)), Pitch::getQueryPriceFilter(2));
            // Цена больше 20000
            $this->assertEqual(array('price' => array('>' => 20000)), Pitch::getQueryPriceFilter(3));
            // Неопределенный диапозон
            $this->assertEqual(array(), Pitch::getQueryPriceFilter(200));
        }

        public function testGetQueryTimeframe() {
            // По умолчанию
            $this->assertEqual(array(), Pitch::getQueryTimeframe());
            // 3 дня
            $this->assertEqual(array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 3)))), Pitch::getQueryTimeframe(1));
            // 7 дней
            $this->assertEqual(array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 7)))), Pitch::getQueryTimeframe(2));
            // 10 дней
            $this->assertEqual(array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 10)))), Pitch::getQueryTimeframe(3));
            // 14 дней
            $this->assertEqual(array('finishDate' => array('=>' => date('Y-m-d H:i:s', time() + (DAY * 14)))), Pitch::getQueryTimeframe(4));
            // Неопределенный таймфрейм
            $this->assertEqual(array(), Pitch::getQueryTimeframe(200));
        }

        public function testGetQuerySearchTerm() {
            $this->assertEqual(array(), Pitch::getQuerySearchTerm(array()));
            $this->assertEqual(array('Pitch.title' => array('REGEXP' => 'тест тест тест|Тест тест тест|ТЕСТ ТЕСТ ТЕСТ')), Pitch::getQuerySearchTerm('Тест тест тест'));
            $this->assertEqual(array('Pitch.title' => array('REGEXP' => 'тест тест тест|Тест тест тест|ТЕСТ ТЕСТ ТЕСТ')), Pitch::getQuerySearchTerm('тест тест тест'));
            $this->assertEqual(array('Pitch.title' => array('REGEXP' => 'test test test|Test test test|TEST TEST TEST')), Pitch::getQuerySearchTerm('test test test'));
            $this->assertEqual(array(), Pitch::getQuerySearchTerm(array('test' => 'test')));
            $this->assertEqual(array(), Pitch::getQuerySearchTerm(''));
            $this->assertEqual(array(), Pitch::getQuerySearchTerm(null));
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
            // если у нас просмотр не текущих питчей, то бесплатные - вниз
            $this->assertEqual(array('price' => 'desc','started' => 'desc'),Pitch::getQueryOrder(array('price'=>'desc'), 'all'));
            $this->assertEqual(array('price' => 'asc','started' => 'desc'),Pitch::getQueryOrder(array('price'=>'asc'), 'all'));
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

        public function testGetFreePitch() {
            $pitch1 = Pitch::getFreePitch();
            $pitch2 = Pitch::getFreePitch();
            $this->assertEqual(1, count($pitch1));
            while ($pitch1->id == $pitch2->id) {
                $pitch2 = Pitch::getFreePitch();
            }
            $this->assertNotEqual($pitch2, $pitch1);
        }

        public function testGetPitchesForHomePage() {
            for ($i = 1; $i <= 3; $i++){
                $pitch = Pitch::first($i);
                $pitch->status = 0;
                $pitch->published = 1;
                $pitch->pinned = 1;
                $pitch->price = 12500+$i;
                $pitch->ideas_count = 10+$i;
                $pitch->save();
            }
            $pitches = Pitch::getPitchesForHomePage();
            $this->assertEqual(array(3,2,1),array_keys($pitches->data()));
            foreach ($pitches as $pitch){
                $this->assertEqual(0,$pitch->multiwinner);
            }
        }

        public function testCreateNewWinner() {
            $this->assertFalse(Pitch::createNewWinner(0));
            $this->assertTrue(Pitch::createNewWinner(2));
            $pitch = Pitch::first(8);
            $solution = Solution::first(array('order' => array('id' => 'DESC')));
            $this->assertEqual('2. Test title', $pitch->title);
            $this->assertEqual(0, $pitch->billed);
            $this->assertEqual(0, $pitch->published);
            $this->assertEqual(2, $pitch->multiwinner);
            $this->assertEqual($pitch->awarded, $solution->id);
        }

        public function testActivateNewWinner() {
            $this->assertFalse(Pitch::activateNewWinner(0));
            $this->assertTrue(Pitch::activateNewWinner(4));
            $pitch = Pitch::first(4);
            $solution = Solution::first(1);
            $pitch2 = Pitch::first(1);
            $comment = Comment::first(array('conditions'=>array('pitch_id'=>$pitch->multiwinner),'order'=>(array('id'=>'desc'))));
            $this->assertEqual('Друзья, выбран победитель',  substr($comment->text, 0, 47));
            $this->assertEqual('1. Проверка названия', $pitch2->title);
            $this->assertEqual(1,$solution->awarded);
            $this->assertEqual(1,$solution->nominated);
            $this->assertEqual(1,$pitch->billed);
            $this->assertEqual(1,$pitch->published);
        }

        public function testGetCountBilledMultiwinner() {
            // 0 результатов
            $count = Pitch::getCountBilledMultiwinner(2);
            $this->assertEqual(0, $count);
            // 1 - одна копия, сначала неоплаченная, потом оплаченная
            $firstCopyId = Pitch::createNewWinner(2);
            $count = Pitch::getCountBilledMultiwinner(2);
            $this->assertEqual(0, $count);
            Pitch::activateNewWinner($firstCopyId);
            $count = Pitch::getCountBilledMultiwinner(2);
            $this->assertEqual(1, $count);
            // 2 - две оплаченных копии
            $secondCopyId = Pitch::createNewWinner(4);
            Pitch::activateNewWinner($secondCopyId);
            $count = Pitch::getCountBilledMultiwinner(2);
            $this->assertEqual(2, $count);
        }


    public function testActivateLogosalePitch() {
        $data = Solution::addBlankPitchForLogosale(2, 0);
        $id = $data['pitch_id'];
        $this->assertEqual(8, $id);
        $this->assertFalse(Pitch::activateLogoSalePitch($id));
        $logosalePitch = Pitch::first($id);
        $logosalePitch->awarded = 3;
        $logosalePitch->save();
        $this->assertTrue(Pitch::activateLogoSalePitch($id));
        $logosalePitch = Pitch::first($id);
        $this->assertEqual(12, $logosalePitch->awarded);
        $this->assertEqual(1, $logosalePitch->billed);
        $this->assertEqual(1, $logosalePitch->published);
        $this->assertEqual(1, $logosalePitch->status);
        $this->assertEqual(0, $logosalePitch->confirmed);
        $this->assertEqual(date('Y-m-d H:i:s'), $logosalePitch->started);
        $this->assertEqual(date('Y-m-d H:i:s', time() + 10 * DAY), $logosalePitch->finishDate);
        $this->assertEqual('Test title 4', $logosalePitch->title);
    }

    public function testDeclineLogosalePitch() {
        $data = Solution::addBlankPitchForLogosale(2, 0);
        $id = $data['pitch_id'];
        $this->assertEqual(8, $id);
        $logosalePitch = Pitch::first($id);
        $logosalePitch->awarded = 3;
        $logosalePitch->save();
        $this->assertTrue(Pitch::activateLogoSalePitch($id));
        $this->assertFalse(Pitch::declineLogosalePitch($id, 1));
        $this->assertTrue(Pitch::declineLogosalePitch($id, 2));
        $logosalePitch = Pitch::first($id);
        $this->assertEqual(0, $logosalePitch->awarded);
        $this->assertEqual(0, $logosalePitch->billed);
        $this->assertEqual(0, $logosalePitch->published);
        $this->assertEqual(0, $logosalePitch->status);
        $this->assertEqual(0, $logosalePitch->confirmed);
        $this->assertEqual('0000-00-00 00:00:00', $logosalePitch->started);
        $this->assertEqual('0000-00-00 00:00:00', $logosalePitch->finishDate);
        $this->assertEqual('Logosale Pitch', $logosalePitch->title);
    }

    public function testAcceptLogosalePitch() {
        $data = Solution::addBlankPitchForLogosale(2, 0);
        $id = $data['pitch_id'];
        $this->assertEqual(8, $id);
        $logosalePitch = Pitch::first($id);
        $logosalePitch->awarded = 3;
        $logosalePitch->save();
        $this->assertTrue(Pitch::activateLogoSalePitch($id));
        $this->assertFalse(Pitch::acceptLogosalePitch($id, 1));
        $this->assertTrue(Pitch::acceptLogosalePitch($id, 2));
        $logosalePitch = Pitch::first($id);
        $this->assertEqual(1, $logosalePitch->confirmed);
    }*/

    public function testGetPaymentId() {
        $paymentId = Pitch::getPaymentId(1);
        $this->assertEqual('101', $paymentId);
        $paymentId = Pitch::getPaymentId(2);
        $this->assertEqual('102', $paymentId);
        $paymentId = Pitch::getPaymentId(3);
        $this->assertEqual(null, $paymentId);
        $paymentId = Pitch::getPaymentId(4);
        $this->assertEqual('103', $paymentId);
        $paymentId = Pitch::getPaymentId(5);
        $this->assertEqual('104', $paymentId);
    }

    public function testIsMoneyBack() {
        $this->assertFalse(Pitch::isMoneyBack(1));
        $this->assertTrue(Pitch::isMoneyBack(2));
    }

    public function testHadDesignerLeftRating() {
        $this->assertFalse(Pitch::hadDesignerLeftRating(1));
        $pitchForTest = Pitch::first(6);
        $pitchForTest->status = 2;
        $pitchForTest->save();
        $this->assertFalse(Pitch::hadDesignerLeftRating(6));
        $clientGrade = Grade::create(array(
            'pitch_id' => 6,
            'user_id' => 2,
            'type' => 'client'
        ));
        $clientGrade->save();
        $this->assertFalse(Pitch::hadDesignerLeftRating(6));
        $this->assertFalse($pitchForTest->hadDesignerLeftRating());
        $designerGrade = Grade::create(array(
            'pitch_id' => 6,
            'user_id' => 3,
            'type' => 'designer'
        ));
        $designerGrade->save();
        $this->assertTrue(Pitch::hadDesignerLeftRating(6));
        $this->assertTrue($pitchForTest->hadDesignerLeftRating());
    }

    public function testGetStatisticalAverages() {
        $this->assertEqual(12, Pitch::getStatisticalAverages(1, 'good'));
        $this->assertEqual(11, Pitch::getStatisticalAverages(1, 'normal'));
        $this->assertEqual(3, Pitch::getStatisticalAverages(1, 'minimal'));
        $this->assertEqual(0, Pitch::getStatisticalAverages(3, 'good'));
        $this->assertEqual(3, Pitch::getStatisticalAverages(3, 'normal'));
        $this->assertEqual(0, Pitch::getStatisticalAverages(3, 'minimal'));
        // cache
        $this->assertEqual(12, Pitch::getStatisticalAverages(1, 'good'));
        $this->assertEqual(11, Pitch::getStatisticalAverages(1, 'normal'));
        $this->assertEqual(3, Pitch::getStatisticalAverages(1, 'minimal'));
    }

}
