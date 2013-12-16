<?php

namespace app\models;

use app\models\User;
use app\extensions\mailers\UserMailer;

class Moderation extends \app\models\AppModel {

    public static function __init() {
        parent::__init();
        self::applyFilter('save', function($self, $params, $chain){
            $result = $chain->next($self, $params, $chain);
            if ($result && ($penalty = $params['entity']->penalty)) {
                switch ($penalty) {
                    case 0:
                        // Nothing to do
                        break;
                    case 1:
                        // Block User
                        $user = $self::fetchModelUser($params['entity']->model_data);
                        if ($user) {
                            $user->banned = 1;
                            $user->save(null, array('validate' => false));
                            UserMailer::block(array('user' => $user->data()));
                        }
                        break;
                    default:
                        // Ban User Until
                        $user = $self::fetchModelUser($params['entity']->model_data);
                        if ($user) {
                            $term = ((int) $penalty) * DAY;
                            $user->silenceUntil = date('Y-m-d H:i:s', time() + $term);
                            $user->silenceCount += 1;
                            $user->save(null, array('validate' => false));
                            UserMailer::ban(array('user' => $user->data(), 'term' => (int) $penalty ));
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
    public static function fetchModelUser($modelData) {
        $data = unserialize($modelData);
        $user_id = $data['user_id'];
        if (!$user = User::first($user_id)) {
            return false;
        }
        return $user;
    }
}