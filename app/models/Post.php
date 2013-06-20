<?php

namespace app\models;

class Post extends \app\models\AppModel {

    public $belongsTo = array('User');

    public static function increaseCounter($id) {
        $answer = self::first($id);
        $answer->views += 1;
        $answer->save();
        return $answer->views;
    }


}