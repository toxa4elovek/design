<?php
namespace app\extensions\helper;

use app\models\User as UserModel;

/**
 * Class Avatar
 *
 * Класс помощник для вывода аваторов пользователя
 *
 * @package app\extensions\helper
 */
class Avatar extends \lithium\template\Helper {

    /**
     * Метод возвращяет веб-адрес аватарки или тег с картинкой
     *
     * @param array $data
     * @param bool|false $large
     * @param bool|false $srcOnly
     * @return string
     */
    public function show($data = array(), $large = false, $srcOnly = false) {
        if(isset($data['id'])) {
            $user = UserModel::first($data['id']);
            $data = $user->data();
        }
        $src = $this->__getDefaultAvatarSource($large);
        if((isset($data['facebook_uid'])) && (!empty($data['facebook_uid']))) {
            $src = $this->__getFacebookAvatarSource($data['facebook_uid'], $large);
        }
        if((isset($data['images'])) && (isset($data['images']['avatar']))) {
            $src = $this->__getNormalAvatarSource($data, $large);
        }
        if(!$src) {
            $src = $this->__getDefaultAvatarSource($large);
        }
        if($srcOnly == false) {
            if($large) {
                $src = '<img src="' . $src . '" alt="Портрет пользователя" width="180" height="180" id="photoselectpic"/>';
            }else {
                $src = '<img src="' . $src . '" alt="Портрет пользователя" width="41" height="41"/>';
            }
        }
        return $src;
    }

    /**
     * Метод возвращяет адрес для аватарвки из фейсбука
     *
     * @param $facebookUid
     * @param bool|false $large
     * @return string
     */
    private function __getFacebookAvatarSource($facebookUid, $large = false) {
        $extra = '';
        if($large) {
            $extra = '?type=large';
        }
        $src = 'http://graph.facebook.com/' . $facebookUid . '/picture' . $extra;
        return $src;
    }

    /**
     * Метод возвращяет адрес аватарки из файловой системы
     *
     * @param $data
     * @param bool|false $large
     * @return mixed
     */
    private function __getNormalAvatarSource($data, $large = false) {
        $src = $data['images']['avatar_small']['weburl'];
        if($large) {
            $src = $data['images']['avatar_normal']['weburl'];
        }
        return $src;
    }

    /**
     * Метод вовзращяет адрес автатарки по умолчанию
     *
     * @param bool|false $large
     * @return string
     */
    private function __getDefaultAvatarSource($large = false) {
        $src = '/img/default_small_avatar.png';
        if($large) {
            $src = '/img/default_large_avatar.png';
        }
        return $src;
    }

}