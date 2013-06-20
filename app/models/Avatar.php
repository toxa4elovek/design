<?php

namespace app\models;

class Avatar extends \app\models\AppModel {

    public function clearOldAvatars($userId) {
        $avatars = self::all(array('conditions' => array('model_id' => $userId)));
        foreach($avatars as $avatar) {
            if(file_exists($avatar->filename)) {
                unlink($avatar->filename);
            }
            $avatar->delete();
        }
    }


}