<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\User;
use app\extensions\storage\Rcache;
use lithium\storage\Session;

class UserTest extends AppUnit
{

    public $models = ['Pitch', 'User', 'Solution', 'Category', 'Comment', 'Expert'];

    public function setUp()
    {
        Rcache::init();
        $this->rollUp($this->models);
        unset($_COOKIE['sref']);
    }

    public function tearDown()
    {
        Rcache::flushdb();
        $this->rollDown($this->models);
        //unset($_COOKIE['sref']);
    }
/*
    public function testGetAuthorsIds() {
        $authors = array(1, 2);
        User::$authors = $authors;
        $result = User::getAuthorsIds();
        $this->assertEqual($authors, $result);
    }

    public function testGetAdminsIds() {
        $admins = array(1, 2);
        User::$admins = $admins;
        $result = User::getAdminsIds();
        $this->assertEqual($admins, $result);
    }

    public function testGetEditorsIds() {
        $editors = array(1, 2);
        User::$editors = $editors;
        $result = User::getEditorsIds();
        $this->assertEqual($editors, $result);
    }

    public function testGetFeedAuthorsIds() {
        $feedAuthors = array(1, 2);
        User::$feedAuthors = $feedAuthors;
        $result = User::getFeedAuthorsIds();
        $this->assertEqual($feedAuthors, $result);
    }

    public function testSetLastActionTime() {
        $user = User::first(2);
        $user->setLastActionTime();
        $cachedDate = Rcache::read('user_2_LastActionTime');
        $this->assertEqual(date('Y-m-d H:i:s'), $cachedDate);
    }

    public function testGetLastActionTime() {
        $user = User::first(1);
        $result = $user->getLastActionTime();
        $this->assertEqual(strtotime($user->lastActionTime), $result);

        $user = User::first(2);
        $user->setLastActionTime();
        $time = time();
        $result = $user->getLastActionTime();
        $this->assertEqual($time, $result);
    } 
    
    public function testActivateUser() {
        $user = User::first(1);
        $user->activateUser();
        $this->assertEqual('', $user->token);
        $this->assertEqual(1, $user->confirmed_email);
        $user2 = User::first(1);
        $this->assertEqual('', $user2->token);
        $this->assertEqual(1, $user2->confirmed_email);
    }
    
    public function testSetUserToken() {
        // Токена нету
        $id=1;
        $user = User::first($id);
        $user->token = '';
        $user->save(null, array('validate' => false));
        $user2 = User::setUserToken($id);
        $this->assertNotEqual($user->token, $user2->token);	
        
        // Токен есть
        $id=2;
        $user3 = User::first($id);
        $user3->token = '52e72fbb58de8';
        $user3->save(null, array('validate' => false));
        $user4 = User::setUserToken($id);
        $this->assertEqual($user3->token, $user4->token);	
    }

    public function testGetUsersWinnerSolutionsIds() {
        $expected = array();
        $this->assertEqual($expected, User::getUsersWinnerSolutionsIds(1));

        $expected = array(4, 6);
        $this->assertEqual($expected, User::getUsersWinnerSolutionsIds(2));
    }

    public function testGetUsersWonProjectsIds() {
        $expected = array();
        $this->assertEqual($expected, User::getUsersWonProjectsIds(1));

        $expected = array(2, 6);
        $this->assertEqual($expected, User::getUsersWonProjectsIds(2));
    }*/
/*
    public function testRemoveExtraFields() {
        $user = User::first(1);
        $cleanUser = User::removeExtraFields($user);
        $data = $cleanUser->data();
        $this->assertFalse(isset($data['email']));
        $this->assertFalse(isset($data['oldemail']));
        $this->assertTrue(isset($data['last_name']));
        $this->assertFalse(isset($data['location']));
        $this->assertFalse(isset($data['birthdate']));
        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['confirmed_email']));
        $this->assertFalse(isset($data['token']));
        $this->assertFalse(isset($data['facebook_uid']));
        $this->assertFalse(isset($data['vkontakte_uid']));
        $this->assertFalse(isset($data['created']));
        $this->assertFalse(isset($data['invited']));
        $this->assertFalse(isset($data['paymentOptions']));
        $this->assertFalse(isset($data['userdata']));
        $this->assertFalse(isset($data['balance']));
        $this->assertFalse(isset($data['phone']));
        $this->assertFalse(isset($data['phone_operator']));
        $this->assertFalse(isset($data['phone_code']));
        $this->assertFalse(isset($data['phone_valid']));
        $this->assertFalse(isset($data['referal_token']));
        $this->assertFalse(isset($data['autologin_token']));
    }

    public function testActivateSubscription() {
        $plan =  array(
            'id' => 2,
            'price' => 69000,
            'title' => 'Фирменный',
            'duration' => YEAR
        );
        $user = User::first(3);
        $this->assertEqual(0, $user->subscription_status);
        User::activateSubscription(3, $plan);
        $user = User::first(3);
        $this->assertEqual(2, $user->subscription_status);
        $this->assertEqual(date('Y-m-d H:i:s', time() + YEAR), $user->subscription_expiration_date);

        $plan =  array(
            'id' => 2,
            'price' => 69000,
            'title' => 'Фирменный',
            'duration' => YEAR
        );
        User::activateSubscription(3, $plan);
        $user = User::first(3);
        $this->assertEqual(2, $user->subscription_status);
        $this->assertEqual(date('Y-m-d H:i:s', time() + 2 * YEAR), $user->subscription_expiration_date);
    }

    public function testIsSubscriptionActive() {
        $user = User::first(3);
        $this->assertEqual(0, $user->subscription_status);
        $this->assertFalse(User::isSubscriptionActive(3));
        $plan =  array(
            'id' => 2,
            'price' => 69000,
            'title' => 'Фирменный',
            'duration' => YEAR
        );
        User::activateSubscription(3, $plan);
        $this->assertTrue(User::isSubscriptionActive(3));
    }

    public function testGetSubscriptionExpireDate() {
        $user = User::first(3);
        $plan =  array(
            'id' => 2,
            'price' => 69000,
            'title' => 'Фирменный',
            'duration' => MONTH
        );
        User::activateSubscription($user->id, $plan);
        $this->assertEqual(date('d.m.Y H:i:s', time() + MONTH), User::getSubscriptionExpireDate($user->id));
    }

    public function testGetBalance() {
        $user = User::first(3);
        $this->assertEqual(23500, User::getBalance(3));
        $user->balance = 30000;
        $user->save(null, array('validate' => false));
        $this->assertEqual(30000, User::getBalance(3));
    }

    public function testGetShortCompanyName() {
        $user = User::first(3);
        $user->short_company_name = 'Проверка';
        $user->save(null, array('validate' => false));
        $this->assertEqual('Проверка', User::getShortCompanyName(3));
        $user->short_company_name = 'Проверка 2';
        $user->save(null, array('validate' => false));
        $this->assertEqual('Проверка 2', User::getShortCompanyName(3));
    }

    public function testGetFullCompanyName() {
        $user = User::first(3);
        $user->short_company_name = 'Проверка';
        $user->companydata = serialize(array('company_name' => 'Реальная проверка'));
        $user->save(null, array('validate' => false));
        $this->assertEqual('Реальная проверка', User::getFullCompanyName(3));

        $user->companydata = serialize(array('test' => false));
        $user->save(null, array('validate' => false));
        $this->assertEqual('Проверка', User::getFullCompanyName(3));

        $user->companydata = '';
        $user->save(null, array('validate' => false));
        $this->assertEqual('Проверка', User::getFullCompanyName(3));
    }

    public function testFillBalance() {
        $result = User::fillBalance(3, 20000);
        $this->assertTrue($result);
        $this->assertEqual(43500, User::getBalance(3));
        $user = User::first(3);
        $this->assertEqual(43500, $user->balance);
    }

    public function testReduceBalance() {
        $result = User::reduceBalance(3, 20000);
        $this->assertTrue($result);
        $this->assertEqual(3500, User::getBalance(3));
        $user = User::first(3);
        $this->assertEqual(3500, $user->balance);
        $result = User::reduceBalance(3, 20000);
        $this->assertFalse($result);
    }
*/
    public function testSetActiveSubscriptionDiscount()
    {
        $dateTime = date('Y-m-d H:i:s', time() + MONTH);
        $result = User::setSubscriptionDiscount(22222222, 40, $dateTime);
        $this->assertFalse($result);

        $result = User::setSubscriptionDiscount(2, 40, $dateTime);
        $this->assertTrue($result);
        $user = User::first(2);
        $this->assertEqual(40, $user->subscription_discount);
        $this->assertEqual($dateTime, $user->subscription_discount_end_date);
    }

    public function testHasActiveSubscriptionDiscountForRecord()
    {
        User::setSubscriptionDiscount(2, 40, date('Y-m-d H:i:s', time() + MONTH));
        $userRecord = User::first(2);
        $result = $userRecord->hasActiveSubscriptionDiscountForRecord($userRecord->id);
        $this->assertTrue($result);
        User::setSubscriptionDiscount($userRecord->id, 40, date('Y-m-d H:i:s', time() - MONTH));
        $userRecord = User::first(2);
        $result = $userRecord->hasActiveSubscriptionDiscountForRecord($userRecord->id);
        $this->assertFalse($result);
    }

    public function testHasActiveSubscriptionDiscount()
    {
        User::setSubscriptionDiscount(22222222, 40, date('Y-m-d H:i:s', time() + MONTH));
        $result = User::hasActiveSubscriptionDiscount(2222222);
        $this->assertFalse($result);

        User::setSubscriptionDiscount(2, 40, date('Y-m-d H:i:s', time() + MONTH));
        $result = User::hasActiveSubscriptionDiscount(2);
        $this->assertTrue($result);

        User::setSubscriptionDiscount(2, 40, date('Y-m-d H:i:s', time() - MONTH));
        $result = User::hasActiveSubscriptionDiscount(2);
        $this->assertFalse($result);
    }

    public function testGetSubscriptionDiscountForRecord()
    {
        User::setSubscriptionDiscount(2, 40, date('Y-m-d H:i:s', time() + MONTH));
        $userRecord = User::first(2);
        $result = $userRecord->getSubscriptionDiscountForRecord(2);
        $this->assertEqual(40, $result);

        User::setSubscriptionDiscount(2, 40, date('Y-m-d H:i:s', time() - MONTH));
        $userRecord = User::first(2);
        $result = $userRecord->getSubscriptionDiscountForRecord(2);
        $this->assertNull($result);
    }

    public function testGetSubscriptionDiscount()
    {
        $result = User::getSubscriptionDiscount(2222222);
        $this->assertNull($result);

        User::setSubscriptionDiscount(2, 40, date('Y-m-d H:i:s', time() + MONTH));
        $result = User::getSubscriptionDiscount(2);
        $this->assertEqual(40, $result);

        User::setSubscriptionDiscount(2, 40, date('Y-m-d H:i:s', time() - MONTH));
        $result = User::getSubscriptionDiscount(2);
        $this->assertNull($result);
    }

    public function testGetSubscriptionDiscountEndTimeForRecord()
    {
        $dateTime = date('Y-m-d H:i:s', time() + MONTH);
        User::setSubscriptionDiscount(2, 40, $dateTime);
        $userRecord = User::first(2);
        $result = $userRecord->getSubscriptionDiscountEndTimeForRecord();
        $this->assertEqual($dateTime, $result);

        $dateTime = date('Y-m-d H:i:s', time() - MONTH);
        User::setSubscriptionDiscount(2, 40, $dateTime);
        $userRecord = User::first(2);
        $result = $userRecord->getSubscriptionDiscountEndTimeForRecord();
        $this->assertNull($result);
    }

    public function testGetSubscriptionDiscountEndTime()
    {
        $result = User::getSubscriptionDiscountEndTime(2222222);
        $this->assertNull($result);
        $dateTime = date('Y-m-d H:i:s', time() + MONTH);
        User::setSubscriptionDiscount(2, 40, $dateTime);
        $result = User::getSubscriptionDiscountEndTime(2);
        $this->assertEqual($dateTime, $result);

        $dateTime = date('Y-m-d H:i:s', time() - MONTH);
        User::setSubscriptionDiscount(2, 40, $dateTime);
        $result = User::getSubscriptionDiscountEndTime(2);
        $this->assertNull($result);
    }
    /*
            public function testGetReferalPaymentsCount() {
                $count = User::getReferalPaymentsCount();
                $this->assertIdentical(0, $count);
                $user = User::first(3);
                $user->phone_valid = 1;
                $user->phone = '';
                $user->save(null, array('validate' => false));
                $count = User::getReferalPaymentsCount();
                $this->assertIdentical(0, $count);
                $user->phone = '1234';
                $user->save(null, array('validate' => false));
                $count = User::getReferalPaymentsCount();
                $this->assertIdentical(1, $count);
                $user->subscription_status = 1;
                $user->save(null, array('validate' => false));
                $count = User::getReferalPaymentsCount();
                $this->assertIdentical(0, $count);
            }

            public function testIsMemberOfVKGroup() {
                $this->assertFalse(User::isMemberOfVKGroup(999999));
                $this->assertTrue(User::isMemberOfVKGroup(2));
                $this->assertFalse(User::isMemberOfVKGroup(3));
                $this->assertFalse(User::isMemberOfVKGroup(4));
            }

            public function testIsUserRecordMemberOfVKGroup() {
                $user = User::first(2);
                $this->assertTrue($user->isUserRecordMemberOfVKGroup(2));
                $user = User::first(3);
                $this->assertFalse($user->isUserRecordMemberOfVKGroup(3));
                $user = User::first(4);
                $this->assertFalse($user->isUserRecordMemberOfVKGroup(4));
            }

            public function testIsEntrepreneur() {
                $user = User::first(2);
                $this->assertFalse($user->isEntrepreneur());
                $user->companydata = 'a:4:{s:12:"company_name";s:11:"13231312311";s:3:"inn";s:10:"1231231231";s:3:"kpp";s:9:"123123123";s:7:"address";s:4:"test";}';
                $user->save(null, ['validate' => false]);
                $this->assertFalse($user->isEntrepreneur());
                $user->companydata = serialize([
                    'company_name' => 'Дмитрий Александрович',
                    'inn' => '987654321012',
                    'address' => 'просто проверка'
                ]);
                $user->save(null, ['validate' => false]);
                $this->assertTrue($user->isEntrepreneur());
            }

            public function testIsCompany() {
                $user = User::first(2);
                $this->assertFalse($user->isCompany());
                $user->companydata = 'a:4:{s:12:"company_name";s:11:"13231312311";s:3:"inn";s:10:"1231231231";s:3:"kpp";s:9:"123123123";s:7:"address";s:4:"test";}';
                $user->save(null, ['validate' => false]);
                $this->assertTrue($user->isCompany());
                $user->companydata = serialize([
                    'company_name' => 'Дмитрий Александрович',
                    'inn' => '987654321012',
                    'address' => 'просто проверка'
                ]);
                $user->save(null, ['validate' => false]);
                $this->assertFalse($user->isCompany());
            }

        */

    public function testGetPitchCount()
    {
        $count = User::getPitchCount(1);
        $this->assertEqual(1, $count);
        $count = User::getPitchCount(2);
        $this->assertEqual(11, $count);
    }

    public function testIsValidReferalCodeForSubscribers()
    {
        $this->assertFalse(User::isValidReferalCodeForSubscribers('213121'));
        $this->assertFalse(User::isValidReferalCodeForSubscribers(false));
        $this->assertFalse(User::isValidReferalCodeForSubscribers([]));
        $this->assertFalse(User::isValidReferalCodeForSubscribers(null));
        $this->assertFalse(User::isValidReferalCodeForSubscribers(new \stdClass()));
        $this->assertTrue(User::isValidReferalCodeForSubscribers('fl18f'));
    }

    public function testSetReferalForSubscriberCookie()
    {
        $this->assertNoCookie(['key' => 'sref', 'value' => 'fl18f']);
        $this->assertFalse(isset($_COOKIE['sref']));
        $this->assertTrue(User::setReferalForSubscriberCookie('fl18f'));
        /**
         * @TODO - настроить проверку кукисов
         */
        //$this->assertCookie(['key' => 'sref', 'value' => 'fl18f']);
        //$this->assertTrue(isset($_COOKIE['sref']));
    }
}
