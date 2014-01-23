<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\User;

class UserTest extends \lithium\test\Unit {

    protected $_user_model = 'app\tests\mocks\template\helper\MockUserModel';
    protected $_expert_model = 'app\tests\mocks\template\helper\MockExpertModel';

    /**
     * Test object instance.
     *
     * @var object
     */
    public $user = null;

    /**
     * Initialize test by creating a new object instance with a default context.
     */
    public function setUp() {
        $this->user = new User(array('userModel' => $this->_user_model, 'expertModel' => $this->_expert_model));
    }

    public function tearDown() {
        $this->user->clear();
    }

    public function testInitialization() {
        $this->assertEqual(array(1, 2), $this->user->adminIds);
        $this->assertEqual(array(1, 2, 3), $this->user->expertIds);
    }

    public function testIsAdmin() {
        $this->assertFalse($this->user->isAdmin());
        $this->user->write('user.id', 4);
        $this->assertFalse($this->user->isAdmin());
        $this->user->write('user.id', 1);
        $this->assertTrue($this->user->isAdmin());
    }

    public function testIsExpert() {
        $this->assertFalse($this->user->isExpert());
        $this->user->write('user.id', 4);
        $this->assertFalse($this->user->isExpert());
        $this->user->write('user.id', 3);
        $this->assertTrue($this->user->isExpert());
    }

}