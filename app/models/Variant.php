<?php

namespace app\models;

class Variant extends \app\models\AppModel {

    public $belongsTo = array('Question');

}
