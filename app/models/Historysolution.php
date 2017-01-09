<?php

namespace app\models;

class Historysolution extends \app\models\AppModel
{

    public $belongsTo = ['Pitch', 'User'];
}
