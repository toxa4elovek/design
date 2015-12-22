<?php

namespace app\extensions\command;

use app\extensions\mailers\SpamMailer;
use app\models\Solution;
use app\models\User;

/**
 * Class DiscountDesigner
 *
 * Команда для рассылки дизайнерам рекламы
 * @package app\extensions\command
 */
class DiscountDesigner extends CronJob
{

    /**
     * Основной метод
     */
    public function run()
    {
        $this->header('Welcome to the DiscountDesigner command!');
        $users = User::first(['conditions' => [
                        'isDesigner' => 1,
                        'confirmed_email' => 1]]);
        $count = 0;
        foreach ($users as $user) {
            $user->solutions = Solution::all(['conditions' => ['user_id' => $user->id]]);
            if (count($user->solutions)) {
                $data = [
                    'email' => $user->email,
                    'user' => $user,
                    'subject' => 'Зарабатывай больше!',
                ];
                SpamMailer::discountDesigners($data);
                $count++;
            }
        }
        $this->out("$count users emailed");
    }
}
