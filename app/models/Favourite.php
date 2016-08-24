<?php

namespace app\models;

/**
 * Class Favourite
 *
 * Метод отвечает за контроль записий добавления в избранное проектов и пользователей
 *
 * @package app\models
 */
class Favourite extends AppModel
{

    /**
     * @var array связи
     */
    public $belongsTo = ['User', 'Pitch'];

    /**
     * Метод добавляет в избранное для пользоватея $userId проект $projectId
     *
     * @param $userId integer
     * @param $projectId integer
     * @return bool
     */
    public static function add($userId, $projectId)
    {
        $user = User::count((int) $userId);
        $pitch = Pitch::count((int) $projectId);
        if (($user) && ($pitch) && (!$fav = self::first(['conditions' => ['user_id' => $userId, 'pitch_id' => $projectId]]))) {
            $data = [
                'user_id' => $userId,
                'pitch_id' => $projectId,
                'created' => date(MYSQL_DATETIME_FORMAT)
            ];
            return self::create($data)->save();
        }
        return false;
    }

    /**
     * Метод удаляет проект $projectId из избранного пользоватея $userId
     *
     * @param $userId integer
     * @param $projectId integer
     * @return bool
     */
    public static function unfav($userId, $projectId)
    {
        $user = User::count((int) $userId);
        $pitch = Pitch::count((int) $projectId);
        if (($user) && ($pitch) && ($fav = self::first([
            'conditions' => [
                'user_id' => $userId,
                'pitch_id' => $projectId
            ]]))) {
            return $fav->delete();
        }
        return false;
    }

    /**
     * Метод добавляет в избранное для пользоватея $userId пользователя $favUserId
     *
     * @param $userId
     * @param $favUserId
     * @return bool
     */
    public static function addUser($userId, $favUserId)
    {
        $user = User::count((int) $userId);
        $favUser = User::count((int) $favUserId);
        if (($user) && ($favUser) && (!$record = self::first([
            'conditions' => [
                'user_id' => (int) $userId,
                'pitch_id' => 0,
                'fav_user_id' => (int) $favUserId]
            ]))) {
            $date = date(MYSQL_DATETIME_FORMAT);
            $record = self::create([
                        'user_id' => $userId,
                        'created' => $date,
                        'fav_user_id' => $favUserId
            ]);
            if ($record->save()) {
                Event::create([
                    'type' => 'FavUserAdded',
                    'created' => $date,
                    'user_id' => $userId,
                    'fav_user_id' => $favUserId
                ])->save();
            }
            return true;
        }
        return false;
    }

    /**
     * Метод удаляет пользователя $favUserId из избранного $userId
     *
     * @param $userId
     * @param $favUserId
     * @return bool
     */
    public static function unfavUser($userId, $favUserId)
    {
        $user = User::count((int) $userId);
        $favUser = User::count((int) $favUserId);
        if (($user) && ($favUser) && ($fav = self::first(['conditions' => [
            'user_id' => (int) $userId,
                'pitch_id' => 0,
                'fav_user_id' => (int) $favUserId
            ]]))) {
            if ($fav->delete()) {
                if ($event = Event::first(['conditions' => [
                    'type' => 'FavUserAdded',
                    'user_id' => (int) $userId,
                    'fav_user_id' => (int) $favUserId]
                ])) {
                    $event->delete();
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Метод возвращяет количество людей добавивших в избранное пользователя $userId
     *
     * @param $userId int
     * @return int
     */
    public static function getNumberOfTimesAddedToFavourite($userId)
    {
        return (int) self::count(['conditions' => [
            'pitch_id' => 0,
            'fav_user_id' => (int) $userId]
        ]);
    }

    /**
     * Метод возвращяет количество людей, добавивших в избранное пользователя $userId
     *
     * @param $userId int
     * @return int
     */
    public static function getCountFavoriteUsersForUser($userId)
    {
        return (int) self::count(['conditions' => [
            'pitch_id' => 0,
            'user_id' => (int) $userId]
        ]);
    }

    /**
     * Метод возвращяет список айдишников проектов, которые пользователь $userId
     * добавил себе в избранное.
     *
     * @param $userId
     * @return array
     */
    public static function getFavouriteProjectsIdsForUser($userId)
    {
        $favs = self::all(['fields' => ['pitch_id'], 'conditions' => [
            'user_id' => (int) $userId
        ]]);
        $result = array_map(function ($fav) {
            return $fav['pitch_id'];
        }, $favs->data());
        return $result;
    }
}
