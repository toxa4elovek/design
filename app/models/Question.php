<?php

namespace app\models;

class Question extends \app\models\AppModel {

    public $hasMany = array('Variant');

}
