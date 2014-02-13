<?php

namespace app\models;

class Expert extends \app\models\AppModel {

    public $belongsTo = array('User');

    /**
     * Метод возвращает айди пользователей (users.id) из таблицы экспертов (experts.user_id)
     * @return array
     */
    public static function getExpertUserIds() {
        $experts = self::all(array('fields' => array('user_id')));
        $expertIds = array();
        $experts->each(function($record) use (&$expertIds){
           $expertIds[] = $record->user_id;
        });
        return $expertIds;
    }

    public static function getPitchExpertUserIds($expertIds) {
        $experts = self::all(array('fields' => array('user_id'), 'conditions' => array('id' => $expertIds)));
        $expertIds = array();
        $experts->each(function($record) use (&$expertIds){
            $expertIds[] = $record->user_id;
        });
        return $expertIds;
    }
}