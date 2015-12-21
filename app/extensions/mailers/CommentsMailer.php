<?php
namespace app\extensions\mailers;

use app\models\Comment;
use app\models\Pitch;
use app\models\Solution;
use app\models\User;
use lithium\data\entity\Record;

/**
 * Class CommentsMailer
 *
 * Класс для отправки уведомлений о публикации комментариев
 *
 * @package app\extensions\mailers
 */
class CommentsMailer extends \li3_mailer\extensions\Mailer
{

    /**
     * Метод отправляет пользоватлею уведомление о том, что GoDesigner оставил новый комментарий
     *
     * @param $commentRecord
     * @param $userRecord
     * @param $projectRecord
     * @return bool
     */
    public static function sendNewCommentFromAdminNotificationToUser(Record $commentRecord, Record $userRecord, Record $projectRecord)
    {
        $data = ['user' => $userRecord, 'pitch' => $projectRecord, 'comment' => $commentRecord];
        return self::_mail([
            'to' => $userRecord->email,
            'subject' => 'Go Designer оставил комментарий',
            'data' => $data
        ]);
    }

    /**
     * Метод получает пользователей (владельца питча и участников) и отправляет почтовые
     * уведомления о том, что GoDesigner оставил новый комментарий
     *
     * @param $commentId
     * @return int
     */
    public static function sendNewCommentFromAdminNotification($commentId)
    {
        $emailsSent = 0;
        if ($comment = Comment::first((int) $commentId)) {
            $ids = [];
            $project = Pitch::first($comment->pitch_id);
            $client = User::first($project->user_id);
            if ($client->email_newcomments == 1) {
                $ids[] = $client->id;
                if (self::sendNewCommentFromAdminNotificationToUser($comment, $client, $project)) {
                    $emailsSent++;
                }
            }
            $solutions = Solution::all(['fields' => ['user_id'], 'conditions' => ['pitch_id' => $project->id]]);
            foreach ($solutions as $solution) {
                $userRecord = User::first($solution->user_id);
                if (($userRecord->email_newcomments == 1) && (!in_array($userRecord->id, $ids))) {
                    if (self::sendNewCommentFromAdminNotificationToUser($comment, $userRecord, $project)) {
                        $emailsSent++;
                    }
                    $ids[] = $userRecord->id;
                }
            }
        }
        return $emailsSent;
    }

    /**
     * Метод пытается отправить уведомление о новом личном комментарии
     *
     * @param $commentId
     * @return bool
     */
    public static function sendNewPersonalCommentNotification($commentId)
    {
        if ($comment = Comment::first($commentId)) {
            $user = User::first($comment->reply_to);
            $pitch = Pitch::first($comment->pitch_id);
            if ($user->email_newcomments == 1) {
                $data = ['user' => $user, 'pitch' => $pitch, 'comment' => $comment];
                return self::_mail([
                    'to' => $user->email,
                    'use-smtp' => true,
                    'subject' => 'Вам оставлен новый комментарий!',
                    'data' => $data
                ]);
            }
        }
    }
}
