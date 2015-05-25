<?php

namespace app\models;

class Grade extends AppModel {

    public $belongsTo = array('User', 'Pitch');

    /**
     * Метод определяет, есть ли рейтинг, оставленный дизайнером для проекта $projectId
     *
     * @param $projectId
     * @return bool
     */
    public static function isDesignerRatingExistsForProject($projectId) {
        $grade = self::first(array('conditions' => array(
            'pitch_id' => $projectId,
            'type' => 'designer'
        )));
        return (bool) $grade;
    }

}