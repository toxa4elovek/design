<?php
namespace app\extensions\mailers;

use app\models\Comment;
use app\models\Pitch;
use app\models\User;
use app\models\Solution;

class CommentsMailer extends \li3_mailer\extensions\Mailer {

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

    public static function sendNewCommentFromAdminNotification($commentId) {
        $comment = Comment::first($commentId);
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
        $emailsSent = 0;
        foreach($ids as $id) {
            var_dump($id);
            self::sendNewCommentFromAdminNotification($commentId, $id);
        }
        /*foreach($ids as $id) {
            if(CommentsMailer::sendNewCommentFromAdminNotification($commentId, $id)) {
                $emailsSent++;
            }
        }*/
        return $emailsSent;
    }

}