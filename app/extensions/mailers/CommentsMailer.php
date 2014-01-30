<?php
namespace app\extensions\mailers;

use app\models\Comment;
use app\models\Pitch;
use app\models\User;
use app\models\Solution;

class CommentsMailer extends \li3_mailer\extensions\Mailer {

    /**
     * Метод отправляет пользоватлею уведомление о том, что GoDesigner оставил новый комментарий
     *
     * @param $commentId
     * @param $userId
     * @return bool
     */
    public static function sendNewCommentFromAdminNotificationToUser($commentId, $userId) {
        $comment = Comment::first($commentId);
        $pitch = Pitch::first($comment->pitch_id);
        $user = User::first($userId);
        $data = array('user' => $user, 'pitch' => $pitch, 'comment' => $comment);
        return self::_mail(array(
            'to' => $user->email,
            'subject' => 'Go Designer оставил комментарий',
            'data' => $data
        ));
    }

    /**
     * Метод получает пользователей (владельца питча и участников) и отправляет почтовые
     * уведомления о том, что GoDesigner оставил новый комментарий
     *
     * @param $commentId
     * @return int
     */
    public static function sendNewCommentFromAdminNotification($commentId) {
        $emailsSent = 0;
        if($comment = Comment::first($commentId)) {
            $pitch = Pitch::first($comment->pitch_id);
            $client = User::first($pitch->user_id);
            $ids = array();
            if($client->email_newcomments == 1) {
                $ids[] = $client->id;
            }
            $solutions = Solution::all(array('conditions' => array('pitch_id' => $pitch->id)));
            foreach($solutions as $solution) {
                $user = User::first($solution->user_id);
                if($user->email_newcomments == 1) {
                    $ids[] = $user->id;
                }
            }

            foreach($ids as $id) {
                if(CommentsMailer::sendNewCommentFromAdminNotificationToUser($commentId, $id)) {
                    $emailsSent++;
                }
            }
        }
        return $emailsSent;
    }

}