<?php
namespace app\extensions\mailers;

use app\models\Solution;
use app\models\Pitch;
use app\models\User;

class SolutionsMailer extends \li3_mailer\extensions\Mailer {

    /**
     * Метод отправки уведомления о новом решения для заказчика
     *
     * @param $solutionId
     * @return bool
     */
    public static function sendNewSolutionNotification($solutionId) {
        if($solution = Solution::first($solutionId)) {
            $pitch = Pitch::first($solution->pitch_id);
            $owner = Pitch::getOwnerOfPitch($pitch->id);
            if($owner->email_newsol == 1){
                $data = array('user' => $owner, 'pitch' => $pitch, 'solution' => $solution);
                return self::_mail(array(
                    'to' => $owner->email,
                    'subject' => 'Добавлено новое решение!',
                    'data' => $data
                ));
        }
        }
        return false;
    }

    /**
     * Метод отправки уведомлени о победе для дизайнера
     *
     * @param $solutionId
     * @return bool
     */
    public static function sendVictoryNotification($solutionId) {
        $solution = Solution::first($solutionId);
        $pitch = Pitch::first($solution->pitch_id);
        $designer = User::first($solution->user_id);
        $data = array('user' => $designer, 'solution' => $solution, 'pitch' => $pitch);
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $designer->email,
            'subject' => 'Ваше решение стало победителем!',
            'data' => $data
        ));
    }

    /**
     * Метод отправки уведомлени о выкупке решения для дизайнера
     *
     * @param $solutionId
     * @return bool
     */
    public static function sendSolutionBoughtNotification($solutionId) {
        $solution = Solution::first($solutionId);
        $pitch = Pitch::first($solution->pitch_id);
        $designer = User::first($solution->user_id);
        $data = array('user' => $designer, 'solution' => $solution, 'pitch' => $pitch);
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $designer->email,
            'subject' => 'Ваше решение хотят выкупить!',
            'data' => $data
        ));
    }

}