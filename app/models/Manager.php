<?php

namespace app\models;

use lithium\util\Validator;

/**
 * Class Manager
 * @package app\models
 * @method Record|null first(array $conditions) static
 * @method integer count(array $conditions) static
 */
class Manager extends AppModel
{
    /**
     * @var array правила валидации
     */
    public $validates = [
        'project_id' => [
            ['projectRecordMustExist', 'message' => 'Запись проекта1 должна существовать']
        ],
        'manager_id' => [
            ['userRecordMustExist', 'message' => 'Запись менеджера должна существовать'],
        ],
        'subscriber_id' => [
            ['userRecordMustExist', 'message' => 'Запись абонента должна существовать'],
        ],
    ];

    /**
     * Инициализация статического объекта
     */
    public static function init()
    {
        Validator::add('userRecordMustExist', function ($userId) {
            return (bool) User::count(['conditions' => ['User.id' => (int) $userId]]);
        });

        Validator::add('projectRecordMustExist', function ($projectId) {
            if ((int) $projectId !== 0) {
                return (bool) Pitch::count(['conditions' => ['Pitch.id' => (int) $projectId]]);
            } else {
                return true;
            }
        });
    }

    /**
     * Место добавлят менеджера в команду абоненту
     *
     * @param $managerId
     * @param $subscriberId
     * @return bool
     */
    public static function addManagerForSubscriber($managerId, $subscriberId)
    {
        if (!self::isManagerInTeamOfSubscriber($managerId, $subscriberId)) {
            $data = [
                'manager_id' => (int) $managerId,
                'subscriber_id' => (int) $subscriberId,
                'project_id' => 0
            ];
            $record = self::create($data);
            return $record->save();
        }
        return false;
    }

    /**
     * Метод удаляет менеджера из команды абонента
     *
     * @param $managerId
     * @param $subscriberId
     * @return bool
     */
    public static function removeManagerForSubscriber($managerId, $subscriberId)
    {
        if (self::isManagerInTeamOfSubscriber($managerId, $subscriberId)) {
            $managerRecord = self::first(['conditions' => [
                'Manager.manager_id' => (int) $managerId,
                'Manager.subscriber_id' => (int) $subscriberId
            ]]);
            if ($managerRecord) {
                return $managerRecord->delete();
            }
        }
        return false;
    }

    /**
     * Метод определяет, если менеджер в команде абонента
     *
     * @param $managerId
     * @param $subscriberId
     * @return bool
     */
    public static function isManagerInTeamOfSubscriber($managerId, $subscriberId)
    {
        return (bool) self::count(['conditions' => [
            'Manager.manager_id' => (int) $managerId,
            'Manager.subscriber_id' => (int) $subscriberId
        ]]);
    }

    /**
     * Метод определяет, является ли пользователь с номером $possibleManagerId менеджером
     *
     * @param $possibleManagerId
     * @return bool
     */
    public static function isUserManager($possibleManagerId)
    {
        return (bool) self::count(['conditions' => [
            'Manager.manager_id' => (int) $possibleManagerId
        ]]);
    }

    /**
     * Метод возвращяет АйДи абонента для менеджера
     *
     * @param $managerId
     * @return int
     */
    public static function getTeamLeaderOfManager($managerId)
    {
        if (self::isUserManager($managerId)) {
            $managerRecord = self::first(['fields' => ['Manager.subscriber_id'], 'conditions' => [
                'Manager.manager_id' => (int) $managerId
            ]]);
            return (int) $managerRecord->subscriber_id;
        }
        return 0;
    }

    /**
     * Метод назначает менеджера на проект
     *
     * @param $managerId
     * @param $projectId
     * @return bool
     */
    public static function assignManagerToProject($managerId, $projectId)
    {
        if (self::isUserManager((int) $managerId)) {
            $managerRecord = self::first(['conditions' => [
                'Manager.manager_id' => (int) $managerId
            ]]);
            $managerRecord->project_id = (int) $projectId;
            return $managerRecord->save();
        }
        return false;
    }

    /**
     * Метод убирает назначение менеджера на проект
     *
     * @param $managerId
     * @param $projectId
     * @return bool
     */
    public static function removeManagerFromProject($managerId, $projectId)
    {
        if (self::isManagerAssignedToProject((int) $managerId, (int) $projectId)) {
            $managerRecord = self::first(['conditions' => [
                'Manager.manager_id' => (int) $managerId
            ]]);
            $managerRecord->project_id = 0;
            return $managerRecord->save();
        }
        return false;
    }

    /**
     * Проверяет, ялвяется ли менеджер назначенным менеджером для проекта
     *
     * @param $managerId
     * @param $projectId
     * @return bool
     */
    public static function isManagerAssignedToProject($managerId, $projectId)
    {
        return (bool) self::count(['conditions' => [
            'Manager.manager_id' => (int) $managerId,
            'Manager.project_id' => (int) $projectId
        ]]);
    }
}

Manager::init();
