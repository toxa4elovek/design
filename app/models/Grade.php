<?php
namespace app\models;

//use \app\models\Pitch;
//use \app\models\User;

class Grade extends \app\models\AppModel {

    public $belongsTo = array('User', 'Pitch');

}