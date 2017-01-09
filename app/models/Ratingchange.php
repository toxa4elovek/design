<?php

namespace app\models;

class Ratingchange extends \app\models\AppModel
{

    public $belongsTo = ['User', 'Solution'];

    public static function increaseCounter($id)
    {
    }
}
