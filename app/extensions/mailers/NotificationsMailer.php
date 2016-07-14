<?php

namespace app\extensions\mailers;

use app\models\SubscriptionPlan;
use app\models\Pitch;
use app\models\User;

class NotificationsMailer extends \li3_mailer\extensions\Mailer
{

    /**
     * Метод отправляет почтовое уведомление об успешном пополнении баланса
     *
     * @param $user
     * @param $plan
     * @return bool|mixed
     */
    public static function sendFillBalanceSuccess($user, $plan)
    {
        $value = SubscriptionPlan::extractFundBalanceAmount($plan->id);
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $user->email,
            'subject' => 'Ваш счёт успешно пополнен!',
            'data' => compact('user', 'value')
        ));
    }

    /**
     * Метод отправляет почтовое уведомление о долгом завершении администраторам
     *
     * @param $user
     * @param $project
     * @return bool|mixed
     */
    public static function sendLongFinishNotification($user, $project)
    {
        $step = Pitch::getCurrentClosingStep($project->id);
        if ($step < 2) {
            $step = 2;
        }
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $user->email,
            'subject' => 'Завершение затянулось',
            'data' => compact('user', 'project', 'step')
        ));
    }

    /**
     * Метод отправляет почтовое уведомление о большом штрафе администратору
     *
     * @param $project
     * @param $penalty
     * @return bool|mixed
     */
    public static function penaltyNotification($project, $penalty)
    {
        return self::_mail(array(
            'use-smtp' => true,
            'to' => 'm.elenevskaya@godesigner.ru',
            //'to' => 'nyudmitriy@gmail.com',
            'subject' => 'Штраф более 4500 рублей',
            'data' => compact('project', 'penalty')
        ));
    }

    /**
     * Метод отправляет почтовое уведомление о наступающем штрафном периоде для негарантированного проекта,
     * где можно вернуть деньги
     *
     * @param $user
     * @param $project
     * @param $time
     * @return bool|mixed
     */
    public static function penaltyClientNotificationNonGuarantee($user, $project, $time)
    {
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $user->email,
            //'to' => 'nyudmitriy@gmail.com',
            'subject' => 'Проект на GoDesigner: примите решение',
            'data' => compact('user', 'project', 'time')
        ));
    }

    /**
     * Метод отправляет почтовое уведомление о скором наступлении штрафного периода для гарантированного проекта,
     * или для проекта, где плохая активность
     *
     * @param $user
     * @param $project
     * @param $time
     * @return bool|mixed
     */
    public static function penaltyClientNotificationGuarantee($user, $project, $time)
    {
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $user->email,
            //'to' => 'nyudmitriy@gmail.com',
            'subject' => 'Проект на GoDesigner: примите решение',
            'data' => compact('user', 'project', 'time')
        ));
    }

    /**
     * Метод отправляет почтовое уведомление абоненту, у которого скоро закончится срок выбора победителя
     *
     * @param $project
     * @return bool|mixed
     */
    public static function sendSubscriberChooseWinnerWarning($project)
    {
        $user = User::first($project->user_id);
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $user->email,
            //'to' => 'nyudmitriy@gmail.com',
            'subject' => 'Время на выбор победителя истекает!',
            'data' => compact('user', 'project')
        ));
    }

    /**
     * Метод отправляет почтовое уведомление заказчику о том, что у него начала этап выбора победителя,
     * проект негарантированный, можно вернуть деньги
     *
     * @param $project
     * @return bool|mixed
     */
    public static function sendChooseWinnerNotificationForNonGuarantee($project)
    {
        $user = User::first($project->user_id);
        return self::_mail(array(
                'use-smtp' => true,
                'to' => $user->email,
                //'to' => 'nyudmitriy@gmail.com',
                'subject' => 'Проект на GoDesigner: 4 дня на выбор лучшего решения',
                'data' => compact('user', 'project')
            ));
    }

    /**
     * Метод отправляет почтовое уведомление заказчику о том, что у него начался этап выбора победителя,
     * проект гарантированный или низкая активность
     *
     * @param $project
     * @return bool|mixed
     */
    public static function sendChooseWinnerNotificationForGuarantee($project)
    {
        $user = User::first($project->user_id);
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $user->email,
            //'to' => 'nyudmitriy@gmail.com',
            'subject' => 'Проект на GoDesigner: 4 дня на выбор лучшего решения',
            'data' => compact('user', 'project')
        ));
    }

    /**
     * Метод отправляет почтовое уведомление заказчику о том, что у него активен штраф
     *
     * @param $project
     * @param $penalty
     * @return bool|mixed
     */
    public static function sendPenaltyActiveReminder($project, $penalty)
    {
        $user = User::first($project->user_id);
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $user->email,
            //'to' => 'nyudmitriy@gmail.com',
            'subject' => sprintf('Напоминание про штраф: %d рублей', $penalty),
            'data' => compact('user', 'project')
        ));
    }

    /**
     * Метод отправляет почтовое уведомление заказчику о том, что у него начался штрафной период (в первый час после
     * наступления периода)
     *
     * @param $project
     * @return bool|mixed
     */
    public static function sendStartPenaltyNotification($project)
    {
        $user = User::first($project->user_id);
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $user->email,
            //'to' => 'nyudmitriy@gmail.com',
            'subject' => 'Проект на GoDesigner: время на выбор истекло',
            'data' => compact('user', 'project')
        ));
    }

    /**
     * Метод отправляет почтовое уведомление заказчику о том, что через 12 часов у него окончится штрафной период
     *
     * @param $project
     * @return bool|mixed
     */
    public static function sendPenaltyEndsSoonReminder($project)
    {
        $user = User::first($project->user_id);
        $time = (strtotime($project->finishDate) + 10 * DAY);
        return self::_mail(array(
            'use-smtp' => true,
            'to' => $user->email,
            //'to' => 'nyudmitriy@gmail.com',
            'subject' => sprintf('Проект на GoDesigner будет закрыт %s', date('d.m.Y H:i:s', $time)),
            'data' => compact('user', 'project', 'time')
        ));
    }
}
