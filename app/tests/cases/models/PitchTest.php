<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Category;
use app\models\Pitch;
use lithium\storage\Session;
use app\models\Grade;
use app\models\Note;
use app\models\Comment;
use app\models\Solution;
use app\models\Wincomment;
use app\models\User;
use app\extensions\storage\Rcache;
use app\extensions\helper\NameInflector;

class PitchTest extends AppUnit
{

    public function setUp()
    {
        Rcache::init();
        $this->rollUp(['Pitch', 'User', 'Solution', 'Comment', 'Transaction', 'Paymaster', 'Payanyway', 'Note', 'Grade', 'Category', 'Expert', 'Wincomment']);
    }

    public function tearDown()
    {
        Rcache::flushdb();
        $this->rollDown(['Pitch', 'User', 'Solution', 'Comment', 'Transaction', 'Paymaster', 'Payanyway', 'Note', 'Grade', 'Category', 'Expert', 'Wincomment']);
        Session::clear();
    }
/*
    public function testGetOwnerOfPitch() {
        // Тут надо убедиться, что строчкой ниже айди не существующй
        $result = Pitch::getOwnerOfPitch(10000);
        $this->assertFalse($result);
        $result = Pitch::getOwnerOfPitch(1);
        $this->assertTrue(is_object($result));
        $this->assertEqual(2, $result->id);
    }

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
    */
    /*
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
                // Ровно 0
                $this->assertEqual(array('price' => 0), Pitch::getQueryPriceFilter(4));
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
            }*/
/*
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
        }

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

        public function testGenerateNewPaytureId() {
            $pitch = Pitch::generateNewPaytureId(2);
            $pitchindb = Pitch::first(2);
            $this->assertTrue(preg_match('/_([0-9]*)$/', $pitch->payture_id));
            $this->assertTrue(is_string($pitch->payture_id));
            $this->assertTrue(mb_strlen($pitch->payture_id) == 50);
            $this->assertEqual($pitchindb->payture_id, $pitch->payture_id);
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
            $this->assertEqual(0, Pitch::getStatisticalAverages(1, 'good'));
            $this->assertEqual(0, Pitch::getStatisticalAverages(1, 'normal'));
            $this->assertEqual(7, Pitch::getStatisticalAverages(1, 'minimal'));
            $this->assertEqual(0, Pitch::getStatisticalAverages(3, 'good'));
            $this->assertEqual(0, Pitch::getStatisticalAverages(3, 'normal'));
            $this->assertEqual(3, Pitch::getStatisticalAverages(3, 'minimal'));
            // cache
            $this->assertEqual(0, Pitch::getStatisticalAverages(1, 'good'));
            $this->assertEqual(0, Pitch::getStatisticalAverages(1, 'normal'));
            $this->assertEqual(7, Pitch::getStatisticalAverages(1, 'minimal'));
        }

        public function testIsSubscriberProjectForCopyrighting() {
            $project = Pitch::first(3);
            $this->assertFalse($project->isSubscriberProjectForCopyrighting());
            $project->category_id = 7;
            $project->save();
            $this->assertFalse($project->isSubscriberProjectForCopyrighting());
            $project->category_id = 20;
            $project->save();
            $this->assertFalse($project->isSubscriberProjectForCopyrighting());
            $project->specifics = serialize(array('isCopyrighting' => 'true'));
            $project->save();
            $this->assertTrue($project->isSubscriberProjectForCopyrighting());
            $project->specifics = serialize(array('isCopyrighting' => true));
            $project->save();
            $this->assertTrue($project->isSubscriberProjectForCopyrighting());
        }

        public function testIsCopyrighting() {
            $project = Pitch::first(3);
            $this->assertFalse($project->isCopyrighting());
            $project->category_id = 7;
            $project->save();
            $this->assertTrue($project->isCopyrighting());
            $project->category_id = 20;
            $project->save();
            $this->assertFalse($project->isCopyrighting());
            $project->specifics = serialize(array('isCopyrighting' => true));
            $project->save();
            $this->assertTrue($project->isCopyrighting());
        }

        public function testMarkAsRefunded() {
            $this->assertFalse(Pitch::markAsRefunded(99999));

            $this->assertTrue(Pitch::markAsRefunded(4));

            $project = Pitch::first(4);
            $this->assertEqual(2, $project->status);
            $this->assertEqual(0, $project->awarded);

                    $note = Note::first(array('conditions' => array(
                        'pitch_id' => 4,
                        'status' => 2
                    )));

                    $this->assertTrue(is_object($note));
                    $this->assertEqual('lithium\data\entity\Record', get_class($note));
                    $comment = Comment::first(array('conditions' => array(
                        'user_id' => 108,
                        'pitch_id' => 4,
                        'public' => 1,
                    )));
                    $this->assertTrue(is_object($comment));
                    $this->assertEqual('lithium\data\entity\Record', get_class($comment));
                    $user = User::first($project->user_id);
                    $this->assertEqual($project->price, $user->balance);

                    $this->assertFalse(Pitch::markAsRefunded(4));
        }


    public function testGetAutoClosingWarningComment() {
        $project = Pitch::first(7);
        $project->status = 2;
        $project->category_id = 1;
        $project->awardedDate = date('Y-m-d H:i:s', time() - DAY);
        $project->awarded = 9;
        $project->save();
        $projectOwner = User::first($project->user_id);
        $solution = Solution::first($project->awarded);
        $designer = User::first($solution->user_id);
        $nameInflector = new NameInflector();
        $ownerFormatted = $nameInflector->renderName($projectOwner->first_name, $projectOwner->last_name);
        $designerFormatted = $nameInflector->renderName($designer->first_name, $designer->last_name);

        //$text = '<a href="#" class="mention-link" data-comment-to="' . $ownerFormatted . '">@' . $ownerFormatted . ',</a> Нам понравилось работать с вами, и мы хотим продолжить наше партнерство. Сотрудничайте с дизайнерами и копирайтерами без рисков дальше, корректируйте макеты без сервисных сборов, создавайте проекты от 500р. в течение года, став нашим абонентом. В течение недели мы предлагаем вам скидку 10% на <a href="/pages/subscribe" target="_blank">годовое обслуживание</a>.';
        $planDateToComplete = date('d.m.Y H:i', time() + 9 * DAY);
        $newPlanDateToComplete = date('d.m.Y H:i', time() + 11 * DAY);
        $planDaysDefault = 10;
        $expected = "@$ownerFormatted, cрок завершительного этапа длится $planDaysDefault дней, ваш проект должен был быть закрыт к $planDateToComplete.
        <br/><br/>Мы убедительно просим вас активизироваться на сайте, внести финальную правку, утвердить макеты и проверить исходники не позже $newPlanDateToComplete, в противном случае мы будем вынуждены инициировать завершение проекта согласно регламенту.
        <br/><br/>@$designerFormatted, мы просим вас выложить исходники в том виде, каком их последний раз утвердил заказчик, к $newPlanDateToComplete.
        <br/><br/>Спасибо за понимание и содействие!";
        $result = Pitch::getAutoClosingWarningComment(7);
        $this->assertIdentical($expected, $result);

        $project = Pitch::first(7);
        $project->status = 2;
        $project->category_id = 3;
        $project->awardedDate = date('Y-m-d H:i:s', time() - DAY);
        $project->awarded = 9;
        $project->save();
        $projectOwner = User::first($project->user_id);
        $solution = Solution::first($project->awarded);
        $designer = User::first($solution->user_id);
        $nameInflector = new NameInflector();
        $ownerFormatted = $nameInflector->renderName($projectOwner->first_name, $projectOwner->last_name);
        $designerFormatted = $nameInflector->renderName($designer->first_name, $designer->last_name);

        //$text = '<a href="#" class="mention-link" data-comment-to="' . $ownerFormatted . '">@' . $ownerFormatted . ',</a> Нам понравилось работать с вами, и мы хотим продолжить наше партнерство. Сотрудничайте с дизайнерами и копирайтерами без рисков дальше, корректируйте макеты без сервисных сборов, создавайте проекты от 500р. в течение года, став нашим абонентом. В течение недели мы предлагаем вам скидку 10% на <a href="/pages/subscribe" target="_blank">годовое обслуживание</a>.';
        $planDateToComplete = date('d.m.Y H:i', time() + 16 * DAY);
        $newPlanDateToComplete = date('d.m.Y H:i', time() + 18 * DAY);
        $planDaysDefault = 17;
        $expected = "@$ownerFormatted, cрок завершительного этапа длится $planDaysDefault дней, ваш проект должен был быть закрыт к $planDateToComplete.
        <br/><br/>Мы убедительно просим вас активизироваться на сайте, внести финальную правку, утвердить макеты и проверить исходники не позже $newPlanDateToComplete, в противном случае мы будем вынуждены инициировать завершение проекта согласно регламенту.
        <br/><br/>@$designerFormatted, мы просим вас выложить исходники в том виде, каком их последний раз утвердил заказчик, к $newPlanDateToComplete.
        <br/><br/>Спасибо за понимание и содействие!";
        $result = Pitch::getAutoClosingWarningComment(7);
        $this->assertIdentical($expected, $result);
    }

    public function testIsAutoClosingWarningPosted() {
        $project = Pitch::first(7);
        $project->status = 2;
        $project->category_id = 1;
        $project->awardedDate = date('Y-m-d H:i:s', time() - DAY);
        $project->awarded = 9;
        $project->save();
        $this->assertFalse(Pitch::isAutoClosingWarningPosted(7));

        $wincomment = Wincomment::create();
        $data = array(
            'user_id' => 108,
            'created' => date('Y-m-d H:i:s'),
            'solution_id' => 9,
            'step' => 3,
            'text' => "test test"
        );
        $wincomment->set($data);
        $wincomment->save();
        $this->assertFalse(Pitch::isAutoClosingWarningPosted(7));

        $wincomment = Wincomment::create();
        $data = array(
            'user_id' => 108,
            'created' => date('Y-m-d H:i:s'),
            'solution_id' => 9,
            'step' => 3,
            'text' => Pitch::getAutoClosingWarningComment(7)
        );
        $wincomment->set($data);
        $wincomment->save();
        $this->assertTrue(Pitch::isAutoClosingWarningPosted(7));

    }

    public function testIsNeededToPostClosingWarning() {
        $project = Pitch::first(7);

        $this->assertFalse(Pitch::isNeededToPostClosingWarning(7));
        $project->category_id = 1;
        $project->status = 2;
        $project->awardedDate = date('Y-m-d H:i:s', time() - DAY);
        $project->awarded = 9;
        $project->save();
        $this->assertFalse(Pitch::isNeededToPostClosingWarning(7));

        $project->awardedDate = date('Y-m-d H:i:s', time() - 13 * DAY);
        $project->save();
        $this->assertTrue(Pitch::isNeededToPostClosingWarning(7));

        $project->category_id = 3;
        $project->awardedDate = date('Y-m-d H:i:s', time() - 13 * DAY);
        $project->save();
        $this->assertFalse(Pitch::isNeededToPostClosingWarning(7));

        $project->awardedDate = date('Y-m-d H:i:s', time() - 15 * DAY);
        $project->save();
        $this->assertTrue(Pitch::isNeededToPostClosingWarning(7));

        $wincomment = Wincomment::create();
        $data = array(
            'user_id' => 108,
            'created' => date('Y-m-d H:i:s'),
            'solution_id' => 9,
            'step' => 3,
            'text' => Pitch::getAutoClosingWarningComment(7)
        );
        $wincomment->set($data);
        $wincomment->save();

        $this->assertFalse(Pitch::isNeededToPostClosingWarning(7));
    }

    public function testGetCurrentClosingStep() {
        $project = Pitch::first(7);
        $project->status = 2;
        $project->category_id = 1;
        $project->awardedDate = date('Y-m-d H:i:s', time() - DAY);
        $project->awarded = 9;
        $project->save();
        $solution = Solution::first(9);
        $solution->step = 0;
        $solution->save();
        $expected = 0;
        $result = Pitch::getCurrentClosingStep(7);
        $this->assertEqual($expected, $result);
        $solution->step = 3;
        $solution->save();
        $expected = 3;
        $result = Pitch::getCurrentClosingStep(7);
        $this->assertEqual($expected, $result);
    }

    public function testIsPenaltyNeededForProject() {
        $project = Pitch::first(7);
        $project->category_id = 1;
        $project->status = 1;
        $project->awardedDate = '0000-00-00 00:00:00';
        $project->finishDate = date('Y-m-d H:i:s', time() - 1 * DAY);
        $project->awarded = 0;
        $project->save();
        $this->assertFalse(Pitch::isPenaltyNeededForProject(7));
        $project->finishDate = date('Y-m-d H:i:s', time() - 7 * DAY);
        $project->save();
        $this->assertTrue(Pitch::isPenaltyNeededForProject(7));
        $project->category_id = 20;
        $project->save();
        $this->assertFalse(Pitch::isPenaltyNeededForProject(7));
        $project = Pitch::first(7);
        $project->category_id = 1;
        $project->status = 1;
        $project->awardedDate = '0000-00-00 00:00:00';
        $project->finishDate = date('Y-m-d H:i:s', time() - 1 * DAY);
        $project->awarded = 1;
        $project->save();
        $this->assertFalse(Pitch::isPenaltyNeededForProject(7));
        $project = Pitch::first(7);
        $project->category_id = 1;
        $project->status = 2;
        $project->awardedDate = '0000-00-00 00:00:00';
        $project->finishDate = date('Y-m-d H:i:s', time() - 1 * DAY);
        $project->awarded = 1;
        $project->save();
        $this->assertFalse(Pitch::isPenaltyNeededForProject(7));
        $project = Pitch::first(7);
        $project->category_id = 1;
        $project->status = 0;
        $project->awardedDate = '0000-00-00 00:00:00';
        $project->finishDate = date('Y-m-d H:i:s', time() - 1 * DAY);
        $project->awarded = 0;
        $project->save();
        $this->assertFalse(Pitch::isPenaltyNeededForProject(7));
    }

    public function testActivatePenalty() {
        $project = Pitch::first(7);
        $project->category_id = 1;
        $project->status = 0;
        $project->awardedDate = '0000-00-00 00:00:00';
        $project->awarded = 0;
        $project->finishDate = date('Y-m-d H:i:s', time() - 5 * DAY);
        $project->awarded = 0;
        $project->save();
        Session::write('user.id', 2);
        $solutionId = 9;
        $penaltyId = Pitch::getNextPenaltyId(2, $solutionId);
        $penalty = Pitch::first($penaltyId);
        $this->assertTrue(is_object($penalty));
        $this->assertEqual($solutionId, $penalty->awarded);
        $this->assertEqual(0, $penalty->billed);
        $this->assertTrue(Pitch::activatePenalty($penaltyId));
        $penalty = Pitch::first($penaltyId);
        $solution = Solution::first($solutionId);
        $this->assertEqual(1, $solution->nominated);
        $this->assertEqual(date('Y-m-d H:i:s'), $solution->change);
        $project = Pitch::first(7);
        $this->assertEqual(1, $project->status);
        $this->assertEqual(date('Y-m-d H:i:s'), $project->awardedDate);
        $this->assertEqual(date('Y-m-d H:i:s'), $penalty->started);
        $this->assertEqual(date('Y-m-d H:i:s'), $penalty->finishDate);
        $this->assertEqual($solutionId, $project->awarded);
        $penalty = Pitch::first($penaltyId);
        $this->assertEqual(1, $penalty->billed);
        $this->assertEqual(2, $penalty->status);
    }
*/
    public function testGetDaysForWinnerSelection()
    {
        $days = Pitch::getDaysForWinnerSelection(1);
        $this->assertEqual(4, $days);

        $project = Pitch::first(1);
        $project->category_id = 20;
        $finishDate = '2016-01-01 00:00:00';
        $endOfWinnerSelection = '2016-01-11 00:00:00';
        $project->finishDate = $finishDate;
        $project->chooseWinnerFinishDate = $endOfWinnerSelection;
        $project->save();
        $days = Pitch::getDaysForWinnerSelection(1);
        $this->assertEqual(10, $days);

        $project = Pitch::first(1);
        $project->category_id = 20;
        $finishDate = '2016-01-01 00:00:00';
        $endOfWinnerSelection = '2016-01-16 00:00:00';
        $project->finishDate = $finishDate;
        $project->chooseWinnerFinishDate = $endOfWinnerSelection;
        $project->save();
        $days = Pitch::getDaysForWinnerSelection(1);
        $this->assertEqual(15, $days);
    }

    public function testGetEndOfWinnerSelectionDateTime()
    {
        // Проверяем обычный проект
        $project = Pitch::first(1);
        $finishDate = '2016-01-01 00:00:00';
        $project->finishDate = $finishDate;
        $project->category_id = 1;
        $project->save();

        $project = Pitch::first(1);
        $expected = new \DateTime('2016-01-05 00:00:00');
        $result = $project->getEndOfWinnerSelectionDateTime();
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof \DateTime);
        $this->assertEqual($result->format('Y-m-d H:i:s'), $expected->format('Y-m-d H:i:s'));

        // Проверяем абонентский проект
        $project = Pitch::first(1);
        $finishDate = '2016-01-01 00:00:00';
        $endOfWinnerSelection = '2016-01-20 00:00:00';
        $project->finishDate = $finishDate;
        $project->chooseWinnerFinishDate = $endOfWinnerSelection;
        $project->category_id = 20;
        $project->save();

        $project = Pitch::first(1);
        $expected = new \DateTime('2016-01-20 00:00:00');
        $result = $project->getEndOfWinnerSelectionDateTime();
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof \DateTime);
        $this->assertEqual($result->format('Y-m-d H:i:s'), $expected->format('Y-m-d H:i:s'));
    }

    public function testIsOkToSendSmsForFinishWinnerSelectionWarning()
    {
        $project = Pitch::first(1);
        $finishDate = '2016-01-01 12:00:00';
        $project->finishDate = $finishDate;
        $project->save();
        $this->assertTrue($project->isOkToSendSmsForFinishWinnerSelectionWarning());

        $finishDate = '2016-01-01 15:00:00';
        $project->finishDate = $finishDate;
        $project->save();
        $this->assertTrue($project->isOkToSendSmsForFinishWinnerSelectionWarning());

        $finishDate = '2016-01-01 02:00:00';
        $project->finishDate = $finishDate;
        $project->save();
        $this->assertTrue($project->isOkToSendSmsForFinishWinnerSelectionWarning());

        $finishDate = '2016-01-01 05:00:00';
        $project->finishDate = $finishDate;
        $project->save();
        $this->assertFalse($project->isOkToSendSmsForFinishWinnerSelectionWarning());

        $finishDate = '2016-01-01 07:00:00';
        $project->finishDate = $finishDate;
        $project->save();
        $this->assertFalse($project->isOkToSendSmsForFinishWinnerSelectionWarning());

        $finishDate = '2016-01-01 10:00:00';
        $project->finishDate = $finishDate;
        $project->save();
        $this->assertFalse($project->isOkToSendSmsForFinishWinnerSelectionWarning());
    }

    public function testGetMinimalAwardForCategoryForDate()
    {
        $result = Pitch::getMinimalAwardForCategoryForDate(1, new \DateTime('2016-07-12 12:00:00'));
        $this->assertEqual((int) Category::first(1)->minAward, $result);

        $result = Pitch::getMinimalAwardForCategoryForDate(3, new \DateTime('2016-07-12 12:00:00'));
        $this->assertEqual((int) Category::first(3)->minAward, $result);

        $result = Pitch::getMinimalAwardForCategoryForDate(1, new \DateTime('2016-07-09 12:00:00'));
        $this->assertEqual((int) Category::first(1)->discountPrice, $result);

        $result = Pitch::getMinimalAwardForCategoryForDate(3, new \DateTime('2016-07-10 12:00:00'));
        $this->assertEqual((int) Category::first(3)->discountPrice, $result);
    }

    public function testIsAwardValidForDate()
    {
        $project = Pitch::first(1);
        $this->assertFalse($project->isAwardValidForDate(new \DateTime('2016-07-12 12:00:00')));
        $project->price = 10000;
        $project->save();
        $this->assertTrue($project->isAwardValidForDate(new \DateTime('2016-07-12 12:00:00')));

        $project = Pitch::first(1);
        $project->price = 6000;
        $project->save();
        $this->assertTrue($project->isAwardValidForDate(new \DateTime('2016-07-10 09:00:00')));
        $project->price = 10000;
        $project->save();
        $this->assertTrue($project->isAwardValidForDate(new \DateTime('2016-07-12 12:00:00')));
    }
}
