<?php

namespace app\extensions\command;

use app\extensions\helper\MoneyFormatter;
use app\models\SubscriptionPlan;
use app\models\User;
use app\models\Receipt;
use ByteUnits\Binary;
use Symfony\Component\Stopwatch\Stopwatch;

class UpdateOldSubscriptionPlans extends CronJob
{

    public function run()
    {
        $stopWatch = new Stopwatch();
        $stopWatch->start('command');
        $this->header('{:purple}Starting UpdateOldSubscriptionPlans...{:end}');
        $draftsOfPlans = SubscriptionPlan::all(
            [
                'fields' => ['id', 'user_id', 'specifics'],
                'conditions' => [
                'type' => 'plan-payment',
                'billed' => 0
                ]
            ]
        );
        foreach ($draftsOfPlans as $planRecord) {
            $planId = $planRecord->getPlanForPaymentForRecord();
            $userRecord = User::first(
                [
                    'fields' => ['id', 'subscription_discount', 'subscription_discount_end_date'],
                    'conditions' => ['id' => $planRecord->user_id]
                ]
            );
            if ($userRecord) {
                $currentTotal = Receipt::getTotalForProject($planRecord->id);
                $plan = SubscriptionPlan::getPlan($planId);
                $addedBalance = $planRecord->getFundBalanceForPaymentForRecord();
                $receipt = array(
                    array(
                        'name' => 'Оплата тарифа «' . $plan['title'] . '»',
                        'value' => $plan['price']
                    ),
                    array(
                        'name' => 'Пополнение счёта',
                        'value' => $addedBalance
                    )
                );
                $discount = (int) $userRecord->getSubscriptionDiscountForRecord();
                if ($discount > 0) {
                    $moneyHelper = new MoneyFormatter();
                    $discountValue = -1 * ($plan['price'] - $moneyHelper->applyDiscount($plan['price'], $discount));
                    $receipt = Receipt::addRow($receipt, "Скидка — $discount%", $discountValue);
                }
                $totalAfterNewDiscount = Receipt::getTotalFromArray($receipt);
                if ($currentTotal !== $totalAfterNewDiscount) {
                    Receipt::updateOrCreateReceiptForProject($planRecord->id, $receipt);
                    $planRecord->setTotalOfPaymentForRecord($totalAfterNewDiscount);
                    $this->out("Updating plan record #$planRecord->id, set total as $totalAfterNewDiscount");
                }
            }
        }
        $period = $stopWatch->stop('command');
        $duration = $period->getDuration();
        $memoryUsage = $period->getMemory();
        $memoryUsageString = Binary::bytes($memoryUsage)->format('MB', ' ');
        $this->out('{:yellow}Ending process...{:end}');
        $this->hr();
        $color = 'green';
        if ($duration > 2500) {
            $color = 'red';
        }
        $this->out("{:$color}Took $duration ms, $memoryUsageString{:end}");
        $this->hr();
    }
}
