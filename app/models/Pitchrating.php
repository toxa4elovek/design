<?php

namespace app\models;

use \app\models\Pitch;
use \app\models\User;

class Pitchrating extends \app\models\AppModel {

    public static function setRating($userId, $pitchId, $rating) {
        $pitch = Pitch::first($pitchId);
        $user = User::first($userId);
        if (!empty($user) && !empty($pitch)) {
            if (!$pitchRating = Pitchrating::first(array('conditions' => array('user_id' => $userId, 'pitch_id' => $pitchId)))) {
                $pitchRating = Pitchrating::create();
            }
            if ($pitchRating->rating != $rating) {
                $rating = ($rating > 5) ? 5 : $rating;
                $rating = ($rating < 1) ? 1 : $rating;
                $pitchRating->rating = $rating;
                $pitchRating->user_id = $user->id;
                $pitchRating->pitch_id = $pitch->id;
                if ($pitchRating->save())
                    return true;
            } return true;
        } else
            return false;
    }

    public static function takePart($userId, $pitchId) {
        $pitch = Pitch::first($pitchId);
        $user = User::first($userId);
        if (!empty($user) && !empty($pitch)) {
            if (!$pitchRating = Pitchrating::first(array('conditions' => array('user_id' => $userId, 'pitch_id' => $pitchId)))) {
                $pitchRating = Pitchrating::create();
            }
            $pitchRating->trigger = 1;
            $pitchRating->user_id = $user->id;
            $pitchRating->pitch_id = $pitch->id;
            if ($pitchRating->save()) {
                return true;
            }
        } else {
            return false;
        }
    }

    public static function getRating($userId, $pitchId) {
        $rating = Pitchrating::first(array('conditions' => array('user_id' => $userId, 'pitch_id' => $pitchId)));
        $rating = (!empty($rating)) ? $rating->rating :0;
        return $rating;
    }
}
