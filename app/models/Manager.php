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

    public static function init()
    {
        Validator::add('userMustExists', function ($id) {
            return (bool) User::count(['conditions' => ['User.id' => (int) $id]]);
        });
    }

    public $validates = [
        'manager_id' => [
            ['userMustExists', 'message' => 'Требуется ID менеджера'],
        ],
        'subscriber_id' => [
            ['userMustExists', 'message' => 'Требуется ID абонента'],
        ],
    ];

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
            $data = ['manager_id' => (int) $managerId, 'subscriber_id' => (int) $subscriberId];
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
    public static function getTeamLeaderIfofManager($managerId)
    {
        if (self::isUserManager($managerId)) {
            $managerRecord = self::first(['fields' => ['Manager.subscriber_id'], 'conditions' => [
                'Manager.manager_id' => (int) $managerId
            ]]);
            return (int) $managerRecord->subscriber_id;
        }
        return 0;
    }
}

Manager::init();
