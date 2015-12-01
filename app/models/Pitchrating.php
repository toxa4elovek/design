<?php

namespace app\models;

/**
 * Class Pitchrating
 *
 * Класс для работы с записями оценок брифов пользователями
 * @package app\models
 */
class Pitchrating extends AppModel {

    /**
     * Метод устанавливает рейтинг для проекта $projectId и пользователя $userId
     *
     * @param $userId integer айди пользователя
     * @param $projectId integer айди проекта
     * @param $rating integer рейтинг
     * @return bool результат операции
     */
    public static function setRating($userId, $projectId, $rating) {
        $pitch = Pitch::first($projectId);
        $user = User::first($userId);
        if (!empty($user) && !empty($pitch)) {
            if (!$pitchRating = self::first(array('conditions' => array('user_id' => $userId, 'pitch_id' => $projectId)))) {
                $pitchRating = self::create();
            }
            if ($pitchRating->rating != $rating) {
                $rating = ($rating > 5) ? 5 : $rating;
                $rating = ($rating < 1) ? 1 : $rating;
                $pitchRating->set(array('rating' => $rating, 'user_id' => $userId, 'pitch_id' => $projectId));
                return (bool) $pitchRating->save();
            }
            return true;
        }
        return false;
    }

    /**
     * Метод помечает участие пользователя $userId в проекте $projectId
     *
     * @param $userId
     * @param $projectId
     * @return bool
     */
    public static function takePart($userId, $projectId) {
        $pitch = Pitch::first($projectId);
        $user = User::first($userId);
        if (!empty($user) && !empty($pitch)) {
            if (!$pitchRating = self::first(array('conditions' => array('user_id' => $userId, 'pitch_id' => $projectId)))) {
                $pitchRating = self::create();
            }
            $pitchRating->set(array('trigger' => 1, 'user_id' => $userId, 'pitch_id' => $projectId));
            return (bool) $pitchRating->save();
        } else {
            return false;
        }
    }

    /**
     * Метод возвращяет рейтинг проекта $projectId для пользователя $userId
     *
     * @param $userId
     * @param $projectId
     * @return int
     */
    public static function getRating($userId, $projectId) {
        $rating = self::first(array('conditions' => array('user_id' => $userId, 'pitch_id' => $projectId)));
        $rating = (!empty($rating)) ? $rating->rating : 0;
        return (int) $rating;
    }

}
