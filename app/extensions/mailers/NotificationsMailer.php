<?php

namespace app\extensions\mailers;

use app\models\SubscriptionPlan;
use app\models\Pitch;

class NotificationsMailer extends \li3_mailer\extensions\Mailer {

    public static function sendFillBalanceSuccess($user, $plan) {
        $value = SubscriptionPlan::extractFundBalanceAmount($plan->id);
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $user->email,
            'subject' => 'Ваш счёт успешно пополнен!',
            'data' => compact('user', 'value')
        ));
    }

    public static function sendLongFinishNotification($user, $project) {
        $step = Pitch::getCurrentClosingStep($project->id);
        if($step < 2) {
            $step = 2;
        }
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $user->email,
            'subject' => 'Завершение затянулось',
            'data' => compact('user', 'project', 'step')
        ));
    }

    public static function penaltyNotification($project, $penalty) {
        return self::_mail(array(
            'use-smtp' => true,
            'to' => 'm.elenevskaya@godesigner.ru',
            //'to' => 'nyudmitriy@gmail.com',
            'subject' => 'Штраф более 4500 рублей',
            'data' => compact('project', 'penalty')
        ));
    }

}
