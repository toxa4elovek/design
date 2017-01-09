<?php

namespace app\extensions\command;

use app\extensions\helper\MoneyFormatter;
use app\extensions\helper\NameInflector;
use app\extensions\mailers\PromoMailer;
use app\models\Comment;
use app\models\Grade;
use app\models\Pitch;
use app\models\Receipt;
use app\models\SubscriptionPlan;
use app\models\User;

class Step3MarketingSalesFunnel extends CronJob
{

    public $money = null;

    /**
     * Команда добавляет денег на счёт в рекламных целях и постит комментарий в ленту
     */
    public function run()
    {
        $this->_renderHeader();
        $arrayOfProjects = [];
        $finishDateDeltaStart = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() - (8 * DAY)));
        $finishDateDeltaEnd = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() -  7 *DAY));
        $projects = Pitch::all([
            'conditions' => [
                'Pitch.type' => '',
                'Pitch.status' => 2,
                'Pitch.billed' => 1,
                'Pitch.category_id' => ['!=' => '20'],
                'AND' => [
                    [sprintf("Pitch.totalFinishDate >=  '%s'", $finishDateDeltaStart->format(MYSQL_DATETIME_FORMAT))],
                    [sprintf("Pitch.totalFinishDate <=  '%s'", $finishDateDeltaEnd->format(MYSQL_DATETIME_FORMAT))],
                ],
                'User.subscription_status' => 0,
                'User.email_digest' => 1,
                'User.confirmed_email' => 1,
                'User.active' => 1
            ],
            'with' => ['User']
        ]);
        foreach ($projects as $project) {
            $arrayOfProjects[] = $project;
        }

        $arrayOfProjects = array_filter($arrayOfProjects, function ($project) {
            if (User::isSubscriptionActive($project->user->id)) {
                return false;
            }
            if (!$grade = Grade::first(['conditions' => [
                'Grade.type' => 'client',
                'Grade.pitch_id' => $project->id
            ]])) {
                return false;
            }
            if ((int) $grade->site_rating < 4) {
                return false;
            }
            return true;
        });

        array_walk($arrayOfProjects, function ($project) {
            $data = [
                'first_name' => $project->user->first_name,
                'email' => $project->user->email
            ];
            PromoMailer::sendStep3MarketingSalesFunnelEmail($data);
            User::setSubscriptionDiscount($project->user_id, 20, date('Y-m-d H:i:s', time() + (DAY * 7)));
            $this->money = new MoneyFormatter();
            if (!SubscriptionPlan::hasSubscriptionPlanDraft($project->user_id)) {
                $plan = SubscriptionPlan::getPlan(1);
                $paymentId = SubscriptionPlan::getNextSubscriptionPlanId($project->user->id);
                $receipt = [
                    [
                        'name' => 'Оплата тарифа «' . $plan['title'] . '»',
                        'value' => $plan['price']
                    ],
                    [
                        'name' => 'Пополнение счёта',
                        'value' => 0
                    ]
                ];
                $discount = 20;
                $discountValue = -1 * ($plan['price'] - $this->money->applyDiscount($plan['price'], $discount));
                $receipt = Receipt::addRow($receipt, "Скидка — $discount%", $discountValue);
                Receipt::updateOrCreateReceiptForProject($paymentId, $receipt);
                SubscriptionPlan::setTotalOfPayment($paymentId, Receipt::getTotalForProject($paymentId));
                SubscriptionPlan::setPlanForPayment($paymentId, $plan['id']);
                SubscriptionPlan::setFundBalanceForPayment($paymentId, 0);
            }
        });


        $this->_renderFooter(sprintf('%d projects processed', count($arrayOfProjects)));
    }
}
