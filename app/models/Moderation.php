<?php

namespace app\models;

use app\models\User;
use app\extensions\mailers\UserMailer;

class Moderation extends \app\models\AppModel {

    public static function __init() {
        parent::__init();
        self::applyFilter('save', function($self, $params, $chain){
            $result = $chain->next($self, $params, $chain);
            if ($result) {
                $penalty = $params['entity']->penalty;
                $modelData = unserialize($params['entity']->model_data);
                $explanation = $params['entity']->explanation;
                switch ($penalty) {
                    case 0:
                        // Just Remove
                        $user = $self::fetchModelUser($modelData);
                        if ($user) {
                            if ($params['entity']->model == '\app\models\Comment') {
                                UserMailer::removecomment(array(
                                    'user' => $user->data(),
                                    'term' => null,
                                    'reason' => $params['entity']->reason,
                                    'text' => $self::fetchModelText($modelData),
                                    'explanation' => $explanation,
                                ));
                            } else {
                                UserMailer::removesolution(array(
                                    'user' => $user->data(),
                                    'term' => null,
                                    'solution_id' => $params['entity']->model_id,
                                    'reason' => $params['entity']->reason,
                                    'image' => $self::fetchModelImage($modelData),
                                    'explanation' => $explanation,
                                ));
                            }
                        }
                        break;
                    case 1:
                        // Block User
                        $user = $self::fetchModelUser($modelData);
                        if ($user) {
                            $user->banned = 1;
                            $user->save(null, array('validate' => false));
                            UserMailer::removeandblock(array('user' => $user->data(), 'reason' => $params['entity']->reason));
                        }
                        break;
                    default:
                        // Ban User Until
                        $user = $self::fetchModelUser($modelData);
                        if ($user) {
                            $term = ((int) $penalty) * DAY;
                            $user->silenceUntil = date('Y-m-d H:i:s', time() + $term);
                            $user->silenceCount += 1;
                            $user->save(null, array('validate' => false));
                            if ($params['entity']->model == '\app\models\Comment') {
                                UserMailer::removecomment(array(
                                    'user' => $user->data(),
                                    'term' => (int) $penalty,
                                    'reason' => $params['entity']->reason,
                                    'text' => $self::fetchModelText($modelData),
                                    'explanation' => $explanation,
                                ));
                            } else {
                                UserMailer::removesolution(array(
                                    'user' => $user->data(),
                                    'term' => (int) $penalty,
                                    'solution_id' => $params['entity']->model_id,
                                    'reason' => $params['entity']->reason,
                                    'image' => $self::fetchModelImage($modelData),
                                    'explanation' => $explanation,
                                ));
                            }
                        }
                        break;
                }
            }
            return $result;
        });
    }

    /**
     * Get User from Deleted Comment or Solution
     */
    public static function fetchModelUser($data) {
        $user_id = $data['user_id'];
        if (!$user = User::first($user_id)) {
            return false;
        }
        return $user;
    }

    /**
     * Get Comment Text from Deleted Comment
     */
    public static function fetchModelText($data) {
        return (!empty($data['text'])) ? $data['text'] : null;
    }

    /**
     * Get Image from Deleted Solution
     */
    public static function fetchModelImage($data) {
        $file = null;
        if (!empty($data['image']) && file_exists($data['image'])) {
            $fileName = pathinfo($data['image'], PATHINFO_BASENAME);
            $file = 'http://godesigner.ru/solutions/deleted/' . $fileName;
        }
        return $file;
    }
}