<?php
namespace app\extensions\mailers;

use app\models\Solution;
use app\models\Pitch;

class SolutionsMailer extends \li3_mailer\extensions\Mailer {

    public static function sendNewSolutionNotification($solutionId) {
        $solution = Solution::first($solutionId);
        $pitch = Pitch::first($solution->pitch_id);
        $user = Pitch::getOwnerOfPitch($pitch->id);
        if($user->email_newsol == 1){
            $data = array('user' => $user, 'pitch' => $pitch, 'solution' => $solution);
            return self::_mail(array(
                'to' => $user->email,
                'subject' => 'Добавлено новое решение!',
                'data' => $data
            ));
        }
        return false;
    }

}