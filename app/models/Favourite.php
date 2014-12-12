<?php

namespace app\models;

use \app\models\Pitch;
use \app\models\User;

class Favourite extends \app\models\AppModel {

    public $belongsTo = array('User', 'Pitch');

    public static function add($userId, $pitchId) {
        $user = User::first($userId);
        $pitch = Pitch::first($pitchId);
        if ((($user) && ($pitch)) && (!$fav = self::first(array('conditions' => array('user_id' => $userId, 'pitch_id' => $pitchId))))) {
            $fav = self::create();
            $fav->user_id = $userId;
            $fav->pitch_id = $pitchId;
            $fav->created = date('Y-m-d H:i:s');
            return $fav->save();
        } else {
            return false;
        }
    }

    public static function addUser($userId, $favUser) {
        $user = User::first($userId);
        $fav_user = User::first($favUser);
        if ((($user) && ($fav_user)) && (!$fav = self::first(array('conditions' => array('user_id' => $userId, 'pitch_id' => 0, 'fav_user_id' => $favUser))))) {
            $date = date('Y-m-d H:i:s');
            $fav = self::create(array(
                        'user_id' => $userId,
                        'created' => $date,
                        'fav_user_id' => $favUser
            ));
            if ($fav->save()) {
                Event::create(array(
                    'type' => 'FavUserAdded',
                    'created' => $date,
                    'user_id' => $userId,
                    'fav_user_id' => $favUser
                ))->save();
            }
            return true;
        } else {
            return false;
        }
    }

    public static function unfavUser($userId, $favUser) {
        $user = User::first($userId);
        $fav_user = User::first($favUser);
        if ((($user) && ($fav_user)) && ($fav = self::first(array('conditions' => array('user_id' => $userId, 'pitch_id' => 0, 'fav_user_id' => $favUser))))) {
            if ($fav->delete()) {
                if ($event = Event::first(array('conditions' => array('type'=> 'FavUserAdded', 'user_id' => $userId, 'fav_user_id' => $favUser)))) {
                    $event->delete();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public static function unfav($userId, $pitchId) {
        $user = User::first($userId);
        $pitch = Pitch::first($pitchId);
        if ((($user) && ($pitch)) && ($fav = self::first(array('conditions' => array('user_id' => $userId, 'pitch_id' => $pitchId))))) {
            $fav->delete();
            return true;
        } else {
            return false;
        }
    }

}
