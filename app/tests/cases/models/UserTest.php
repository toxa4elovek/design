<?php

namespace app\tests\cases\models;

use app\models\User;

class UserTest extends \lithium\test\Unit {

    public function setUp() {
    }

    public function tearDown() {
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

}