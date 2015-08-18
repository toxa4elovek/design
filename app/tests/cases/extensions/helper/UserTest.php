<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\tests\AppUnit;
use app\extensions\helper\User;
use app\models\User as UserModel;

class UserTest extends AppUnit {

    protected $_user_model = 'app\tests\mocks\template\helper\MockUserModel';
    protected $_expert_model = 'app\tests\mocks\template\helper\MockExpertModel';
    protected $_inflector = 'app\extensions\helper\NameInflector';
    protected $_real_user_model = 'app\models\User';

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
        $this->user->clear();
        $this->rollUp(array('User'));
    }

    public function tearDown() {
        $this->rollDown(array('User'));
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
        // Составные имена вроде: Вячеслав Инвест-резерв
        $this->user->write('user.first_name', 'Вячеслав');
        $this->user->write('user.last_name', 'Инвест-резерв');
        $this->assertEqual('Вячеслав И.', $this->user->getFormattedName());
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

    public function testHasFavouritePitches() {
        $this->assertFalse($this->user->hasFavouritePitches());
        $this->user->write('user.id', 1);
        $this->assertFalse($this->user->hasFavouritePitches());
        $this->user->write('user.faves', array(1, 2, 3));
        $this->assertTrue($this->user->hasFavouritePitches());
    }

    public function testIsPitchFavourite() {
        $favPitch = 2;
        $notFavPitch = 4;
        $favs = array(1, 2);
        $this->assertFalse($this->user->isPitchFavourite($favPitch));
        $this->user->write('user.faves', $favs);
        $this->assertFalse($this->user->isPitchFavourite($notFavPitch));
        $this->assertTrue($this->user->isPitchFavourite($favPitch));
    }

    public function testGetCurrentPitches() {
        $pitches = array(1, 2, 3);
        $this->assertFalse($this->user->getCurrentPitches());
        $this->user->write('user.id', 1);
        $this->assertFalse($this->user->getCurrentPitches());
        $this->user->write('user.currentpitches', $pitches);
        $this->assertEqual($pitches, $this->user->getCurrentPitches());
    }

    public function testGetCountOfCurrentPitches() {
        $pitches = array(1, 2, 3);
        $this->assertIdentical(0, $this->user->getCountOfCurrentPitches());
        $this->user->write('user.id', 1);
        $this->assertIdentical(0, $this->user->getCountOfCurrentPitches());
        $this->user->write('user.currentpitches', $pitches);
        $this->assertIdentical(3 , $this->user->getCountOfCurrentPitches());
    }

    public function testGetCurrentDesignersPitches() {
        $pitches = array(1, 2, 3);
        $this->assertFalse($this->user->getCurrentDesignersPitches());
        $this->user->write('user.id', 1);
        $this->assertFalse($this->user->getCurrentDesignersPitches());
        $this->user->write('user.currentdesignpitches', $pitches);
        $this->assertEqual($pitches, $this->user->getCurrentDesignersPitches());
    }

    public function testGetCountOfCurrentDesignersPitches() {
        $pitches = array(1, 2, 3, 4);
        $this->assertIdentical(0, $this->user->getCountOfCurrentDesignersPitches());
        $this->user->write('user.id', 1);
        $this->assertIdentical(0, $this->user->getCountOfCurrentDesignersPitches());
        $this->user->write('user.currentdesignpitches', $pitches);
        $this->assertIdentical(4 , $this->user->getCountOfCurrentDesignersPitches());
    }

    public function testGetNewBlogpostCount() {
        $this->assertIdentical(0, $this->user->getNewBlogpostCount());
        $this->user->write('user.blogpost.count', 3);
        $this->assertIdentical(3, $this->user->getNewBlogpostCount());
    }

    public function testGetNewEventsCount() {
        $this->assertIdentical(0, $this->user->getNewEventsCount());
        $this->user->write('user.events.count', 3);
        $this->assertIdentical(3, $this->user->getNewEventsCount());
    }

    public function testGetAvatarUrl() {
        $this->assertEqual('/img/default_small_avatar.png', $this->user->getAvatarUrl());
        $this->user->write('user.images.avatar_small.weburl', '/img/custom_avatar.png');
        $this->assertEqual('/img/custom_avatar.png', $this->user->getAvatarUrl());
    }

    public function testIsFeedWriter() {
        UserModel::$feedAuthors = array('1');
        $this->assertFalse($this->user->isFeedWriter());
        $this->user->write('user.id', 4);
        $this->assertFalse($this->user->isFeedWriter());
        $this->user->write('user.id', 1);
        $this->assertTrue($this->user->isFeedWriter());
    }

    public function testNeedToChangeEmail() {
        $this->user->write('user.email', 'nyudmitriy@gmail.com');
        $this->assertFalse($this->user->needToChangeEmail());
        $this->user->write('user.email', 'nyudmitriy@mail.ru');
        $this->assertTrue($this->user->needToChangeEmail());
        $this->user->write('user.email', 'nyudmitriy@bk.ru');
        $this->assertTrue($this->user->needToChangeEmail());
        $this->user->write('user.email', 'nyudmitriy@inbox.ru');
        $this->assertTrue($this->user->needToChangeEmail());
        $this->user->write('user.email', 'nyudmitriy@list.ru');
        $this->assertTrue($this->user->needToChangeEmail());
        $this->user->write('user.email', 'nyudmitriy@mail.rutest');
        $this->assertFalse($this->user->needToChangeEmail());
    }

    public function testGetMaskedEmail() {
        $this->user->write('user.email', 'nyudmitriy@gmail.com');
        $this->assertEqual('n*********@gmail.com', $this->user->getMaskedEmail());
        $this->user->write('user.email', 'fake@address.ru');
        $this->assertEqual('f***@address.ru', $this->user->getMaskedEmail());
    }

    public function testGetBalance() {
        $this->user = new User(array(
            'userModel' => $this->_real_user_model,
            'expertModel' => $this->_expert_model,
            'inflector' => $this->_inflector
        ));
        $userId = 3;
        $user = UserModel::first($userId);
        $this->user->write('user', $user->data());
        $this->assertEqual(23500, $user->balance);
        $this->assertEqual(23500, $this->user->getBalance());
        $user->balance = 3000;
        $user->save(null, array('validate' => false));
        $user = UserModel::first($userId);
        $this->assertEqual(3000, $user->balance);
        $this->assertEqual(3000, $this->user->getBalance());
    }

    public function testGetShortCompanyName() {
        $this->user = new User(array(
            'userModel' => $this->_real_user_model,
            'expertModel' => $this->_expert_model,
            'inflector' => $this->_inflector
        ));
        $user = UserModel::first(3);
        $this->user->write('user', $user->data());
        $this->assertEqual('ООО Проверка', $this->user->getShortCompanyName());
    }

    public function testIsSubscriptionActive() {
        $this->user = new User(array(
            'userModel' => $this->_real_user_model,
            'expertModel' => $this->_expert_model,
            'inflector' => $this->_inflector
        ));
        $user = UserModel::first(3);
        $this->user->write('user', $user->data());
        $this->assertEqual(0, $user->subscription_status);
        $this->assertFalse($this->user->isSubscriptionActive());
        UserModel::activateSubscription(3, MONTH);
        $this->assertTrue($this->user->isSubscriptionActive());
    }

    public function testGetSubscriptionExpireDate() {
        $this->user = new User(array(
            'userModel' => $this->_real_user_model,
            'expertModel' => $this->_expert_model,
            'inflector' => $this->_inflector
        ));
        $user = UserModel::first(3);
        $this->user->write('user', $user->data());
        UserModel::activateSubscription($user->id, MONTH);
        $this->assertEqual(date('d.m.Y H:i:s', time() + MONTH), $this->user->getSubscriptionExpireDate());
    }

}