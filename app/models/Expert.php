<?php

namespace app\models;

use app\extensions\storage\Rcache;

/**
 * Class Expert
 *
 * Класс для работы с записями экспертов
 *
 * @package app\models
 */
class Expert extends AppModel
{

    /**
     * @var array связи
     */
    public $belongsTo = array('User');

    /**
     * Метод возвращает айди пользователей (users.id) из таблицы экспертов (experts.user_id)
     *
     * @param $expertIds array - список айдишников экспертов
     * @return array
     */
    public static function getExpertUserIds($expertIds = array())
    {
        $cacheKey = 'experts_ids_' . md5(serialize($expertIds));
        if (!$expertUserIds = Rcache::read($cacheKey)) {
            $conditions = array('Expert.user_id' => array('>' => 0));
            $expertUserIds = array();

            if ((is_array($expertIds)) && (count($expertIds) > 0)) {
                $conditions += array('id' => $expertIds);
            }

            $experts = self::all(array(
                'fields' => array('user_id'),
                'conditions' => $conditions
            ));

            $experts->each(function ($record) use (&$expertUserIds) {
                $expertUserIds[] = $record->user_id;
            });

            Rcache::write($cacheKey, $expertUserIds, array(), '+1 day');
        }
        return $expertUserIds;
    }

    /**
     * Статический метод позволяет определить, написал ли эксперт комментарий в проекте,
     * где его мнение было заказано
     *
     * @param $projectRecord
     * @param $expertId
     * @return bool
     */
    public static function isExpertNeedToWriteComment($projectRecord, $expertId)
    {
        if (($projectRecord->expert != 1) || ($projectRecord->status != 1) || ($projectRecord->awarded != 0)) {
            return false;
        }
        $chosenExperts = unserialize($projectRecord->{'expert-ids'});
        if (!in_array($expertId, $chosenExperts)) {
            return false;
        }
        $expertRecord = self::first($expertId);
        $comments = Comment::count([
            'fields' => ['id'],
            'conditions' => [
                'user_id' => $expertRecord->user_id,
                'created' => ['>=' => $projectRecord->finishDate],
            ]]);
        return !(bool) $comments;
    }
}
