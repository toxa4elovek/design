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
                $user = $self::fetchModelUser($modelData);
                $dataInfo = array(
                    'user' => $user->data(),
                    'reason' => $params['entity']->reason,
                    'explanation' => $params['entity']->explanation,
                );
                if ($params['entity']->model == '\app\models\Comment') {
                    $dataInfo['text'] = $self::fetchModelText($modelData);
                    $dataInfo['image'] = null;
                    $mailerTemplate = 'removecomment';
                } else {
                    $dataInfo['solution_id'] = $params['entity']->model_id;
                    $dataInfo['text'] = null;
                    $dataInfo['image'] = $self::fetchModelImage($modelData);
                    $mailerTemplate = 'removesolution';
                }
                if ($user) {
                    switch ($penalty) {
                        case 0:
                            // Just Remove
                            $dataInfo['term'] = null;
                            break;
                        case 1:
                            // Block User
                            $user->banned = 1;
                            $user->save(null, array('validate' => false));
                            $mailerTemplate = 'removeandblock';
                            break;
                        default:
                            // Ban User Until
                            $term = ((int) $penalty) * DAY;
                            $user->silenceUntil = date('Y-m-d H:i:s', time() + $term);
                            $user->silenceCount += 1;
                            $user->save(null, array('validate' => false));
                            $dataInfo['term'] = (int) $penalty;
                            break;
                    }
                }
                UserMailer::$mailerTemplate($dataInfo);
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