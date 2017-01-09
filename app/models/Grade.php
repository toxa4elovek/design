<?php

namespace app\models;

use lithium\data\collection\RecordSet;
use lithium\data\entity\Record;

/**
 * Class Grade
 * @package app\models
 * @method Record|null first(array $conditions) static
 * @method int count(array $conditions) static
 * @method RecordSet|null all(array $conditions) static
 */
class Grade extends AppModel
{

    public $belongsTo = ['User', 'Pitch'];

    /**
     * Метод определяет, есть ли рейтинг, оставленный дизайнером для проекта $projectId
     *
     * @param $projectId
     * @return bool
     */
    public static function isDesignerRatingExistsForProject($projectId)
    {
        $grade = self::first(['conditions' => [
            'pitch_id' => $projectId,
            'type' => 'designer'
        ]]);
        return (bool) $grade;
    }
}
