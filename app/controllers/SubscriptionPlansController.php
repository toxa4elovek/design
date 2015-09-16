<?php

namespace app\controllers;

use \Exception;
use app\models\SubscriptionPlan;
use app\models\Receipt;

class SubscriptionPlansController extends AppController {

    /**
     * Метод для отображения страницы пополнения баланса личного кабинета и
     * активации тарифа
     */
    public function subscriber() {
        if(is_null($this->request->params['id'])) {
            $planId = SubscriptionPlan::getNextFundBalanceId($this->userHelper->getId());
            $receipt = array(
                array(
                    'name' => 'Пополнение счёта',
                    'value' => 9000
                )
            );
            Receipt::updateOrCreateReceiptForProject($planId, $receipt);
            SubscriptionPlan::setTotalOfPayment($planId, Receipt::getTotalForProject($planId));
            return compact('receipt', 'planId');
        }else {
            if ($plan = SubscriptionPlan::getPlan((int)$this->request->params['id'])) {
                $planId = SubscriptionPlan::getNextSubscriptionPlanId($this->userHelper->getId());
                $receipt = array(
                    array(
                        'name' => 'Оплата тарифа «' . $plan['title'] . '»',
                        'value' => $plan['price']
                    ),
                    array(
                        'name' => 'Пополнение счёта',
                        'value' => 9000
                    )
                );
                Receipt::updateOrCreateReceiptForProject($planId, $receipt);
                SubscriptionPlan::setTotalOfPayment($planId, Receipt::getTotalForProject($planId));
                return compact('plan', 'receipt', 'planId');
            }
            throw new Exception('Выбранного тарифа не существует.', 404);
        }
    }

    public function updateReceipt() {
        if($plan = SubscriptionPlan::first($this->request->data['projectId'])) {
            if($this->userHelper->isPitchOwner($plan->user_id)) {
                Receipt::updateOrCreateReceiptForProject($plan->id, $this->request->data['updatedReceipt']);
                SubscriptionPlan::setTotalOfPayment($plan->id, Receipt::getTotalForProject($plan->id));
                return $this->request->data;
            }
        }
    }
}