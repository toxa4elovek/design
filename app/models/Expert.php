<?php

namespace app\models;

use app\extensions\storage\Rcache;
use lithium\data\collection\RecordSet;
use lithium\data\entity\Record;

/**
 * Class Expert
 *
 * Класс для работы с записями экспертов
 *
 * @package app\models
 * @method Record|null first(array $conditions) static
 * @method int count(array $conditions) static
 * @method RecordSet|null all(array $conditions = []) static
 */
class Expert extends AppModel
{

    /**
     * @var array связи
     */
    public $belongsTo = ['User'];

    /**
     * Метод возвращает айди пользователей (users.id) из таблицы экспертов (experts.user_id)
     *
     * @param $expertIds array - список айдишников экспертов
     * @return array
     */
    public static function getExpertUserIds($expertIds = [])
    {
        $cacheKey = 'experts_ids_' . md5(serialize($expertIds));
        if (!$expertUserIds = Rcache::read($cacheKey)) {
            $conditions = ['Expert.user_id' => ['>' => 0]];
            $expertUserIds = [];

            if ((is_array($expertIds)) && (count($expertIds) > 0)) {
                $conditions += ['id' => $expertIds];
            }

            $experts = self::all([
                'fields' => ['user_id'],
                'conditions' => $conditions
            ]);

            $experts->each(function ($record) use (&$expertUserIds) {
                $expertUserIds[] = $record->user_id;
            });

            Rcache::write($cacheKey, $expertUserIds, [], '+1 day');
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
