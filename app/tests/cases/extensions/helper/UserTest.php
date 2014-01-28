<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\User;

class UserTest extends \lithium\test\Unit {

    protected $_user_model = 'app\tests\mocks\template\helper\MockUserModel';
    protected $_expert_model = 'app\tests\mocks\template\helper\MockExpertModel';
    protected $_inflector = 'app\extensions\helper\NameInflector';

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
        $this->user = new User(array(
            'userModel' => $this->_user_model,
            'expertModel' => $this->_expert_model,
            'inflector' => $this->_inflector
        ));
    }

    public function tearDown() {
        $this->user->clear();
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

    public function testIsLoggedIn() {
        $this->assertFalse($this->user->isLoggedIn());
        $this->user->write('user.id', 4);
        $this->assertTrue($this->user->isLoggedIn());
    }

    public function testIsPitchOwner() {
        $pitchOwnerId = 1;
        $randomUserId = 4;
        $this->assertFalse($this->user->isPitchOwner($pitchOwnerId));
        $this->user->write('user.id', $randomUserId);
        $this->assertFalse($this->user->isPitchOwner($pitchOwnerId));
        $this->user->write('user.id', $pitchOwnerId);
        $this->assertTrue($this->user->isPitchOwner($pitchOwnerId));
    }

    public function testIsSolutionAuthor() {
        $solutionOwnerId = 1;
        $randomUserId = 4;
        $this->assertFalse($this->user->isSolutionAuthor($solutionOwnerId));
        $this->user->write('user.id', $randomUserId);
        $this->assertFalse($this->user->isSolutionAuthor($solutionOwnerId));
        $this->user->write('user.id', $solutionOwnerId);
        $this->assertTrue($this->user->isSolutionAuthor($solutionOwnerId));
    }

    public function testIsEditor() {
        $this->assertFalse($this->user->isEditor());
        $this->user->write('user.id', 5);
        $this->assertFalse($this->user->isEditor());
        $this->user->write('user.id', 4);
        $this->assertTrue($this->user->isEditor());
    }

    public function testIsAuthor() {
        $this->assertFalse($this->user->isAuthor());
        $this->user->write('user.id', 4);
        $this->assertFalse($this->user->isAuthor());
        $this->user->write('user.id', 5);
        $this->assertTrue($this->user->isAuthor());
    }

    public function testGetId() {
        $this->assertFalse($this->user->getId());
        $this->user->write('user.id', '1');
        $this->assertIdentical(1, $this->user->getId());
    }

    public function testGetFirstname() {
        $this->assertFalse($this->user->getFirstname());
        $this->user->write('user.first_name', 'Дмитрий');
        $this->assertEqual('Дмитрий', $this->user->getFirstname());
    }

    public function testGetLastname() {
        $this->assertFalse($this->user->getLastname());
        $this->user->write('user.last_name', 'Васильев');
        $this->assertEqual('Васильев', $this->user->getLastname());
    }

    public function testGetEmail() {
        $this->assertFalse($this->user->getEmail());
        $this->user->write('user.email', 'nyudmitriy@gmail.com');
        $this->assertEqual('nyudmitriy@gmail.com', $this->user->getEmail());
    }

    public function testIsCommentAuthor() {
        $commentAuthorId = 1;
        $randomUserId = 4;
        $this->assertFalse($this->user->isCommentAuthor($commentAuthorId));
        $this->user->write('user.id', $randomUserId);
        $this->assertFalse($this->user->isCommentAuthor($commentAuthorId));
        $this->user->write('user.id', $commentAuthorId);
        $this->assertTrue($this->user->isCommentAuthor($commentAuthorId));
    }

    public function testGetFormattedName() {
        $this->assertFalse($this->user->getFormattedName());
        $this->user->write('user.first_name', 'Дмитрий');
        $this->user->write('user.last_name', 'Васильев');
        $this->assertEqual('Дмитрий В.', $this->user->getFormattedName());
    }

    public function testGetFormattedNameWithParams() {
        $this->assertFalse($this->user->getFormattedName());
        $this->assertEqual('Дмитрий В.', $this->user->getFormattedName('Дмитрий', 'Васильев'));
    }

    public function testIsPostAuthor() {
        $postAuthorId = 4;
        $randomUserId = 1;
        $this->assertFalse($this->user->isPostAuthor($postAuthorId));
        $this->user->write('user.id', $randomUserId);
        $this->assertFalse($this->user->isPostAuthor($postAuthorId));
        $this->user->write('user.id', $postAuthorId);
        $this->assertTrue($this->user->isPostAuthor($postAuthorId));
    }

    public function testIsAllowedToComment() {
        $this->assertFalse($this->user->isAllowedToComment());
        $timeInFuture = date('Y-m-d H:i:s', time() + 3000);
        $timeInPast = date('Y-m-d H:i:s', time() - 3000);
        $this->user->write('user.silenceUntil', $timeInFuture);
        $this->assertFalse($this->user->isAllowedToComment());
        $this->user->write('user.silenceUntil', $timeInPast);
        $this->assertTrue($this->user->isAllowedToComment());
    }

    public function testGetFullname() {
        $this->assertFalse($this->user->getFullname());
        $this->user->write('user.first_name', 'Дмитрий');
        $this->user->write('user.last_name', 'Васильев');
        $this->assertEqual('Дмитрий Васильев', $this->user->getFullname());
    }

    public function testIsSocialNetworkUser() {
        $this->assertFalse($this->user->isSocialNetworkUser());
        $this->user->write('user.social', 0);
        $this->assertFalse($this->user->isSocialNetworkUser());
        $this->user->write('user.social', 1);
        $this->assertTrue($this->user->isSocialNetworkUser());
    }

}