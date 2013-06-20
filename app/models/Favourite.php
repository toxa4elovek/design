<?php
namespace app\models;

use \app\models\Pitch;
use \app\models\User;

class Favourite extends \app\models\AppModel {

    public $belongsTo = array('User', 'Pitch');

    public static function add($userId, $pitchId) {
        $user = User::first($userId);
        $pitch = Pitch::first($pitchId);
        if((($user) && ($pitch)) && (!$fav = self::first(array('conditions' => array('user_id' => $userId, 'pitch_id' => $pitchId))))) {
            $fav = self::create();
            $fav->user_id = $userId;
            $fav->pitch_id = $pitchId;
            $fav->created = date('Y-m-d H:i:s');
            return $fav->save();
        }else {
            return false;
        }
    }

    public static function unfav($userId, $pitchId) {
        $user = User::first($userId);
        $pitch = Pitch::first($pitchId);
        if((($user) && ($pitch)) && ($fav = self::first(array('conditions' => array('user_id' => $userId, 'pitch_id' => $pitchId))))) {
            $fav->delete();
            return true;
        }else {
            return false;
        }
    }

}