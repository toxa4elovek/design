<?php

namespace app\models;

class Historysolution extends \app\models\AppModel {

    public $belongsTo = array('Pitch', 'User');

}