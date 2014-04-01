<?php

namespace app\models;

use \app\models\Pitch;

class Note extends \app\models\AppModel {

    public $belongsTo = array('Pitch');

}
