<?php

namespace app\models;

use lithium\data\collection\RecordSet;
use lithium\data\entity\Record;

/**
 * Class User
 * @package app\models
 * @method Record|null first(array $conditions) static
 * @method int count(array $conditions) static
 * @method RecordSet|null all(array $conditions = []) static
 */
class TextMessage extends AppModel {

    /**
     * @var array связи
     */
    public $belongsTo = ['User'];

}