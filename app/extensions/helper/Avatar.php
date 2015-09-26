<?php
namespace app\extensions\helper;

use app\models\User as UserModel;

class Avatar extends \lithium\template\Helper {

    function show($data = array(), $large = false, $srcOnly = false) {
        if(isset($data['id'])) {
            $user = UserModel::first($data['id']);
            $data = $user->data();
        }
        $src = '/img/default_small_avatar.png';
        if($large) {
            $src = '/img/default_large_avatar.png';
        }
        if((isset($data['facebook_uid'])) && (!empty($data['facebook_uid']))) {
            $extra = '';
            if($large) {  
                $extra = '?type=large';
            }
            $src = 'http://graph.facebook.com/' . $data['facebook_uid'] . '/picture' . $extra;

            if((isset($data['images'])) && (isset($data['images']['avatar']))) {
                $src = $data['images']['avatar_small']['weburl'];
                if($large) {
                    $src = $data['images']['avatar_normal']['weburl'];
                }
            }else {

                switch(1):
                    case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.JPG')):
                        $src = '/avatars/' . $data['id'] . '_normal.JPG' ; break;
                    case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.JPEG')):
                        $src = '/avatars/' . $data['id'] . '_normal.JPEG' ; break;
                    case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.jpg')):
                        $src = '/avatars/' . $data['id'] . '_normal.jpg' ; break;
                    case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.jpeg')):
                        $src = '/avatars/' . $data['id'] . '_normal.jpeg' ; break;
                    case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.png')):
                        $src = '/avatars/' . $data['id'] . '_normal.png' ; break;
                    case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.gif')):
                        $src = '/avatars/' . $data['id'] . '_normal.gif' ; break;
                endswitch;
            }


            //die();
        }else {

            if((isset($data['images'])) && (isset($data['images']['avatar']))) {
                $src = $data['images']['avatar_small']['weburl'];
                if($large) {
                    $src = $data['images']['avatar_normal']['weburl'];
                }

            }else {
                if($large):
                    switch(1):
                        case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.JPG')):
                            $src = '/avatars/' . $data['id'] . '_normal.JPG' ; break;
                        case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.JPEG')):
                            $src = '/avatars/' . $data['id'] . '_normal.JPEG' ; break;
                        case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.jpg')):
                            $src = '/avatars/' . $data['id'] . '_normal.jpg' ; break;
                        case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.jpeg')):
                            $src = '/avatars/' . $data['id'] . '_normal.jpeg' ; break;
                        case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.png')):
                            $src = '/avatars/' . $data['id'] . '_normal.png' ; break;
                        case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.gif')):
                            $src = '/avatars/' . $data['id'] . '_normal.gif' ; break;
                    endswitch;
                else:
                    switch(1):
                        case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.JPG')):
                        $src = '/avatars/' . $data['id'] . '_small.JPG' ; break;
                        case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.JPEG')):
                        $src = '/avatars/' . $data['id'] . '_small.JPEG' ; break;
                        case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.jpg')):
                        $src = '/avatars/' . $data['id'] . '_small.jpg' ; break;
                        case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.jpeg')):
                        $src = '/avatars/' . $data['id'] . '_small.jpeg' ; break;
                        case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.png')):
                        $src = '/avatars/' . $data['id'] . '_small.png' ; break;
                        case (file_exists(LITHIUM_APP_PATH . '/webroot/avatars/' . $data['id'] . '.gif')):
                        $src = '/avatars/' . $data['id'] . '_small.gif' ; break;
                    endswitch;
                endif;
            }
        }

        if($srcOnly == false) {
            if(empty($extra)) {
                $string = '<img src="' . $src . '" alt="Портрет пользователя" width="41" height="41"/>';
                if($large) {
                   $string = '<img src="' . $src . '" alt="Портрет пользователя" width="180" height="180" id="photoselectpic"/>';
                }
            }else {
                $string = '<img src="' . $src . '" alt="Портрет пользователя" width="180" id="photoselectpic"/>';
            }
        }else {
            $string = $src;
        }
        return $string;
    }
}