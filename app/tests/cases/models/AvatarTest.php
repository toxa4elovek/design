<?php

namespace app\tests\cases\models;

use app\extensions\storage\Rcache;
use app\extensions\tests\AppUnit;
use app\models\Avatar;
use app\models\User;

class AvatarTest extends AppUnit
{

    public function setUp()
    {
        Rcache::init();
        $this->rollUp(array('Avatar', 'User'));
        copy(
            '/Users/dima/www/godesigner/app/resources/tmp/tests/avatar.png',
            '/Users/dima/www/godesigner/app/resources/tmp/avatar.png'
        );
        copy(
            '/Users/dima/www/godesigner/app/resources/tmp/tests/avatar_normal.png',
            '/Users/dima/www/godesigner/app/resources/tmp/avatar_normal.png'
        );
        copy(
            '/Users/dima/www/godesigner/app/resources/tmp/tests/avatar_small.png',
            '/Users/dima/www/godesigner/app/resources/tmp/avatar_small.png'
        );
    }

    public function tearDown()
    {
        Rcache::flushdb();
        $this->rollDown(array('Avatar', 'User'));
        if (file_exists('/Users/dima/www/godesigner/app/resources/tmp/avatar.png')) {
            unlink('/Users/dima/www/godesigner/app/resources/tmp/avatar.png');
        }
        if (file_exists('/Users/dima/www/godesigner/app/resources/tmp/avatar_normal.png')) {
            unlink('/Users/dima/www/godesigner/app/resources/tmp/avatar_normal.png');
        }
        if (file_exists('/Users/dima/www/godesigner/app/resources/tmp/avatar_small.png')) {
            unlink('/Users/dima/www/godesigner/app/resources/tmp/avatar_small.png');
        }
    }

    public function testClearOldAvatars()
    {
        $user = User::first(1);
        $images = $user->data()['images'];
        $this->assertTrue(is_array($images));
        $this->assertEqual(0, count($images));

        $user = User::first(2);
        $images = $user->data()['images'];
        $this->assertTrue(is_array($images));
        $this->assertEqual(3, count($images));
        $this->assertTrue(isset($images['avatar']));
        $this->assertTrue(isset($images['avatar_normal']));
        $this->assertTrue(isset($images['avatar_small']));
        $avatarFile = $images['avatar']['filename'];
        $avatarNormalFile = $images['avatar_normal']['filename'];
        $avatarSmallFile = $images['avatar_small']['filename'];
        $this->assertTrue(file_exists($avatarFile));
        $this->assertTrue(file_exists($avatarNormalFile));
        $this->assertTrue(file_exists($avatarSmallFile));
        Avatar::removeAllAvatarsOfUser(2);
        $cacheKey = 'avatars_2';
        $this->assertFalse(Rcache::exists($cacheKey));
        $user = User::first(2);
        $images = $user->data()['images'];
        $this->assertTrue(is_array($images));
        $this->assertEqual(0, count($images));
        $this->assertFalse(file_exists($avatarFile));
        $this->assertFalse(file_exists($avatarNormalFile));
        $this->assertFalse(file_exists($avatarSmallFile));
        $this->assertTrue(Rcache::exists($cacheKey));
    }

    public function testFindWithCache()
    {
        $cacheKey = 'avatars_2';
        $this->assertFalse(Rcache::exists($cacheKey));
        $user = User::first(2);
        $images = $user->data()['images'];
        $this->assertTrue(is_array($images));
        $this->assertEqual(3, count($images));
        $this->assertTrue(Rcache::exists($cacheKey));
        $user = User::first(2);
        $images = $user->data()['images'];
        $this->assertTrue(is_array($images));
        $this->assertEqual(3, count($images));
        $this->assertTrue(Rcache::exists($cacheKey));
    }

    public function testGetFbAvatar()
    {
        $cacheKey = 'avatars_2';
        $userRecord = User::first(2);
        $results = Avatar::getFbAvatar($userRecord);
        $this->assertEqual(3, count($results));
        $this->assertTrue(Rcache::exists($cacheKey));
    }
/*
    public function testGetVkAvatar()
    {
        $cacheKey = 'avatars_3';
        $userRecord = User::first(3);
        $userRecord->vk_image_link = 'https://api.vk.com/method/users.get?user_id=96385962&fields=photo_max_orig&v=5.27&access_token=9f3a1787cf07739487983f245f68e1680182f69f72e5ff973b0ac2d3283236203c6f96b69ea2be8c7010a';
        $images = $userRecord->data()['images'];
        $this->assertTrue(is_array($images));
        $this->assertEqual(0, count($images));

        $results = Avatar::getVKAvatar($userRecord);
        $this->assertEqual(3, count($results));
        $this->assertTrue(Rcache::exists($cacheKey));

        $userRecord = User::first(3);
        $images = $userRecord->data()['images'];
        $this->assertTrue(is_array($images));
        $this->assertEqual(3, count($images));
    }
*/
    public function testFindCountAndCache()
    {
        $cacheKey = 'avatars_2';
        $userRecord = User::first(2);
        $results = Avatar::getFbAvatar($userRecord);
        $this->assertEqual(3, count($results));
        $this->assertTrue(Rcache::exists($cacheKey));
        $count = Avatar::count(['conditions' => ['model_id' => 2]]);
        $this->assertIdentical(3, $count);
        Rcache::delete($cacheKey);
        $count = Avatar::count(['conditions' => ['model_id' => 2]]);
        $this->assertIdentical(3, $count);
    }
}
