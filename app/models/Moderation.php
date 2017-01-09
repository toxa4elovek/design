<?php

namespace app\models;

use app\models\Comment;
use app\models\Solution;
use app\models\User;
use app\extensions\storage\Rcache;
use app\extensions\mailers\UserMailer;
use lithium\analysis\Logger;

class Moderation extends AppModel
{

    public static function __init()
    {
        parent::__init();
        self::applyFilter('save', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if ($result) {
                $penalty = $params['entity']->penalty;
                $modelData = unserialize($params['entity']->model_data);
                $user = $self::fetchModelUser($params['entity']->model_user);
                $dataInfo = [
                    'user' => $user->data(),
                    'reason' => $params['entity']->reason,
                    'explanation' => $params['entity']->explanation,
                ];
                if ($params['entity']->model == '\app\models\Comment') {
                    $comment = Comment::first(['conditions' => ['Comment.id' => $params['entity']->model_id], 'with' => ['Pitch']]);
                    $dataInfo['pitch'] = $comment->pitch;
                    $dataInfo['text'] = $self::fetchModelText($modelData);
                    $dataInfo['image'] = null;
                    $cacheKey = 'commentsraw_' . $comment->pitch_id;
                    $comment->delete();
                    Rcache::delete($cacheKey);
                    $mailerTemplate = 'removecomment';
                } else {
                    $solution = Solution::first(['conditions' => ['Solution.id' => $params['entity']->model_id], 'with' => ['Pitch']]);
                    $dataInfo['pitch'] = $solution->pitch;
                    $dataInfo['solution_num'] = $solution->num;
                    $dataInfo['text'] = null;
                    $dataInfo['image'] = $self::fetchModelImage($modelData);
                    $mailerTemplate = 'removesolution';
                    $userHelper = new \app\extensions\helper\User();
                    $data = [
                        'id' => $solution->id,
                        'num' => $solution->num,
                        'user_who_deletes' => $userHelper->getId(),
                        'user_id' => $solution->user_id,
                        'date' => date('Y-m-d H:i:s'),
                        'isAdmin' => $userHelper->isAdmin()
                    ];
                    Logger::write('info', serialize($data), ['name' => 'deleted_solutions']);
                    if ($solution) {
                        $solution->delete();
                    }
                }
                if ($user) {
                    switch ($penalty) {
                        case 0:
                            // Just Remove
                            $dataInfo['term'] = null;
                            break;
                        case 1:
                            // Block User
                            $user->block();
                            $mailerTemplate = 'removeandblock';
                            break;
                        case 2:
                            // 30 days block user
                            $user->blockUntil();
                            $mailerTemplate = 'removeandblock30';
                            break;
                        case 3:
                            // Block User for project
                            if ($solution) {
                                $solutions = Solution::all([
                                    'conditions' => [
                                        'Solution.pitch_id' => $solution->pitch_id,
                                        'Solution.user_id' => $solution->user_id,
                                    ]
                                ]);
                                foreach ($solutions as $solutionToDelete) {
                                    $solutionToDelete->delete();
                                }
                            }
                            $mailerTemplate = 'removeandblockforproject';
                            break;
                        default:
                            // Ban User Until
                            $term = ((int) $penalty) * DAY;
                            $user->silenceUntil = date('Y-m-d H:i:s', time() + $term);
                            $user->silenceCount += 1;
                            $user->save(null, ['validate' => false]);
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
    public static function fetchModelUser($user_id)
    {
        if (!$user = User::first($user_id)) {
            return false;
        }
        return $user;
    }

    /**
     * Get Comment Text from Deleted Comment
     */
    public static function fetchModelText($data)
    {
        return (!empty($data['text'])) ? $data['text'] : null;
    }

    /**
     * Get Image from Deleted Solution
     */
    public static function fetchModelImage($data)
    {
        $file = null;
        if (!empty($data['image']) && file_exists($data['image'])) {
            $fileName = pathinfo($data['image'], PATHINFO_BASENAME);
            $file = 'http://godesigner.ru/solutions/deleted/' . $fileName;
        }
        return $file;
    }
}
