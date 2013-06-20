<?php

namespace app\models;

class Historycomment extends \app\models\AppModel {

    public $belongsTo = array('Pitch', 'User');

}