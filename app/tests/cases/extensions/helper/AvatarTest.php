<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Avatar;
use app\extensions\tests\AppUnit;
use app\models\User;

class AvatarTest extends AppUnit {

    public $models = array('User');
    public $avatar = null;

    public function setUp() {
        $this->avatar = new Avatar();
        $this->rollUp($this->models);
        copy(LITHIUM_APP_PATH . '/webroot/img/default_small_avatar.png', LITHIUM_APP_PATH . '/webroot/avatars/1_small.png');
        copy(LITHIUM_APP_PATH . '/webroot/img/default_large_avatar.png', LITHIUM_APP_PATH . '/webroot/avatars/1_normal.png');
    }

    public function tearDown() {
        $this->rollDown($this->models);
        unlink(LITHIUM_APP_PATH . '/webroot/avatars/1_small.png');
        unlink(LITHIUM_APP_PATH . '/webroot/avatars/1_normal.png');
    }

    public function testShow() {
        $result = $this->avatar->show(1);
        $expected = '<img src="/img/default_small_avatar.png" alt="Портрет пользователя" width="41" height="41"/>';
        $this->assertEqual($expected, $result);

        $result = $this->avatar->show(1, true);
        $expected = '<img src="/img/default_large_avatar.png" alt="Портрет пользователя" width="180" height="180" id="photoselectpic"/>';
        $this->assertEqual($expected, $result);

        $result = $this->avatar->show(1, true, true);
        $expected = '/img/default_large_avatar.png';
        $this->assertEqual($expected, $result);

        $user = User::first(1);
        $data = $user->data();
        $data['id'] = null;
        $data['images'] = array(
            'avatar' => array(),
            'avatar_small' => array('weburl' => '/img/avatars/1_small.png'),
            'avatar_normal' => array('weburl' => '/img/avatars/1_normal.png'),
        );
        $result = $this->avatar->show($data);
        $expected = '<img src="/img/avatars/1_small.png" alt="Портрет пользователя" width="41" height="41"/>';
        $this->assertEqual($expected, $result);

        $result = $this->avatar->show($data, true);
        $expected = '<img src="/img/avatars/1_normal.png" alt="Портрет пользователя" width="180" height="180" id="photoselectpic"/>';
        $this->assertEqual($expected, $result);

        $user = User::first(2);
        $result = $this->avatar->show($user->data());
        $expected = '<img src="http://graph.facebook.com/1777051461/picture" alt="Портрет пользователя" width="41" height="41"/>';
        $this->assertEqual($expected, $result);
        $result = $this->avatar->show($user->data(), true);
        $expected = '<img src="http://graph.facebook.com/1777051461/picture?type=large" alt="Портрет пользователя" width="180" height="180" id="photoselectpic"/>';
        $this->assertEqual($expected, $result);

        $data = $user->data();
        $data['id'] = null;
        $data['images'] = array(
            'avatar' => array('true'),
            'avatar_small' => array('weburl' => '/img/avatars/1_small.png'),
            'avatar_normal' => array('weburl' => '/img/avatars/1_normal.png'),
        );
        $result = $this->avatar->show($data);
        $expected = '<img src="/img/avatars/1_small.png" alt="Портрет пользователя" width="41" height="41"/>';
        $this->assertEqual($expected, $result);

        $result = $this->avatar->show($data, true);
        $expected = '<img src="/img/avatars/1_normal.png" alt="Портрет пользователя" width="180" height="180" id="photoselectpic"/>';
        $this->assertEqual($expected, $result);
    }

}