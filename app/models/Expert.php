<?php

namespace app\models;

use app\extensions\storage\Rcache;

class Expert extends \app\models\AppModel {

    public $belongsTo = array('User');

    /**
     * Метод возвращает айди пользователей (users.id) из таблицы экспертов (experts.user_id)
     * @return array
     */
    public static function getExpertUserIds() {
        $cacheKey = 'experts_ids';
        if(!$expertIds = Rcache::read($cacheKey)) {
            $experts = self::all(array(
                'conditions' => array('Expert.user_id' => array('>' => 0)),
                'fields' => array('user_id')
            ));
            $expertIds = array();
            $experts->each(function($record) use (&$expertIds){
               $expertIds[] = $record->user_id;
            });
            Rcache::write($cacheKey, $expertIds, array(), '+1 day');
        }
        return $expertIds;
    }

    /**
     * Метод возвращаяет объекты экспертов
     * @return bool|mixed
     */
    public static function getExperts() {
        $cacheKey = 'experts_objects';
        if(!$experts = Rcache::read($cacheKey)) {
            $experts = Expert::all(array('conditions' => array('Expert.user_id' => array('>' => 0))));
            Rcache::write($cacheKey, $experts, array(), '+1 day');
        }
        return $experts;
    }

    public static function getPitchExpertUserIds($expertIds) {
        $expertUserIds = array();
        if((is_array($expertIds)) && (count($expertIds) > 0)) {
            $experts = self::all(array('fields' => array('user_id'), 'conditions' => array('id' => $expertIds)));
            $experts->each(function($record) use (&$expertUserIds){
                $expertUserIds[] = $record->user_id;
            });
        }
        return $expertUserIds;
    }
}