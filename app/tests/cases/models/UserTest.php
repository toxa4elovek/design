<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\User;
use app\extensions\storage\Rcache;
use lithium\storage\Session;

class UserTest extends AppUnit {

    public function setUp() {
		Rcache::init();
        $this->rollUp(array('Pitch', 'User', 'Solution', 'Category', 'Comment'));
    }

    public function tearDown() {
        Rcache::flushdb();
        $this->rollDown(array('Pitch', 'User', 'Solution', 'Category', 'Comment'));
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
        $user = User::first(3);
        $this->assertEqual(0, $user->subscription_status);
        User::activateSubscription(3, MONTH);
        $user = User::first(3);
        $this->assertEqual(1, $user->subscription_status);
        $this->assertEqual(date('Y-m-d H:i:s', time() + MONTH), $user->subscription_expiration_date);

        User::activateSubscription(3, 2 * MONTH);
        $user = User::first(3);
        $this->assertEqual(1, $user->subscription_status);
        $this->assertEqual(date('Y-m-d H:i:s', time() + 3 * MONTH), $user->subscription_expiration_date);
    }

    public function testIsSubscriptionActive() {
        $user = User::first(3);
        $this->assertEqual(0, $user->subscription_status);
        $this->assertFalse(User::isSubscriptionActive(3));
        User::activateSubscription(3, MONTH);
        $this->assertTrue(User::isSubscriptionActive(3));
    }

    public function testGetSubscriptionExpireDate() {
        $user = User::first(3);
        User::activateSubscription($user->id, MONTH);
        $this->assertEqual(date('d.m.Y H:i:s', time() + MONTH), User::getSubscriptionExpireDate($user->id));
    }

    public function testGetBalance() {
        $user = User::first(3);
        $this->assertEqual(23500, User::getBalance(3));
        $user->balance = 30000;
        $user->save(null, array('validate' => false));
        $user = User::first(3);
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

}