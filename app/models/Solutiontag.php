<?php

namespace app\models;

class Solutiontag extends \app\models\AppModel {
    public $hasOne = array('Tag');
}
