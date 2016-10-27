<?php

namespace app\extensions\command;

use app\models\User;

/**
 * Class CheckSubscription
 *
 * Класс для обнуление устаравшой подписки абонентов по крону
 *
 * @package app\extensions\command
 */
class CheckSubscription extends CronJob
{

    public function run()
    {
        $this->_renderHeader();
        $subscribers = User::all(['conditions' => [
            'User.subscription_status' => ['>' => 0],
            'User.subscription_expiration_date' => ['<=' => date(MYSQL_DATETIME_FORMAT)]
        ]]);
        $count = count($subscribers);
        foreach ($subscribers as $subscriber) {
            $this->out($subscriber->id);
            $subscriber->subscription_status = 0;
            $subscriber->save(null, ['validate' => false]);
        }
        $this->_renderFooter("$count subscriptions expired");
    }
}
