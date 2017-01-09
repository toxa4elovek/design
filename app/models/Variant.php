<?php

namespace app\models;

class Variant extends \app\models\AppModel
{

    public $belongsTo = ['Question'];
}
