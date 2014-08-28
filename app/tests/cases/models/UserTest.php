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
}