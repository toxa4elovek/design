<?php

namespace app\controllers;

use \Exception;
use app\models\SubscriptionPlan;
use app\models\Receipt;
use app\models\User;

class SubscriptionPlansController extends AppController
{

    /**
     * Метод для отображения страницы пополнения баланса личного кабинета и
     * активации тарифа
     */
    public function subscriber()
    {
        if (is_null($this->request->params['id'])) {
            $planRecordId = SubscriptionPlan::getNextFundBalanceId($this->userHelper->getId());
            $value = 9000;
            if ((isset($this->request->query['amount'])) && (!empty($this->request->query['amount']))) {
                $predefined = true;
                $value = (int) $this->request->query['amount'];
            }
            $receipt = array(
                array(
                    'name' => 'Пополнение счёта',
                    'value' => $value
                )
            );
            Receipt::updateOrCreateReceiptForProject($planRecordId, $receipt);
            SubscriptionPlan::setTotalOfPayment($planRecordId, Receipt::getTotalForProject($planRecordId));
            SubscriptionPlan::setPlanForPayment($planRecordId, 0);
            SubscriptionPlan::setFundBalanceForPayment($planRecordId, 9000);
            return compact('receipt', 'planRecordId', 'predefined');
        } else {
            if ($plan = SubscriptionPlan::getPlan((int)$this->request->params['id'])) {
                $planRecordId = SubscriptionPlan::getNextSubscriptionPlanId($this->userHelper->getId());
                $value = 9000;
                if (($savedValue = SubscriptionPlan::getFundBalanceForPayment($planRecordId)) && ($savedValue !== null)) {
                    $value = $savedValue;
                }
                $receipt = array(
                    array(
                        'name' => 'Оплата тарифа «' . $plan['title'] . '»',
                        'value' => $plan['price']
                    ),
                    array(
                        'name' => 'Пополнение счёта',
                        'value' => $value
                    )
                );
                $discount = 0;
                if (User::hasActiveSubscriptionDiscount($this->userHelper->getId())) {
                    $discount = User::getSubscriptionDiscount($this->userHelper->getId());
                    $discountValue = -1 * ($plan['price'] - $this->money->applyDiscount($plan['price'], $discount));
                    $receipt = Receipt::addRow($receipt, "Скидка — $discount%", $discountValue);
                }
                Receipt::updateOrCreateReceiptForProject($planRecordId, $receipt);
                SubscriptionPlan::setTotalOfPayment($planRecordId, Receipt::getTotalForProject($planRecordId));
                SubscriptionPlan::setPlanForPayment($planRecordId, $plan['id']);
                SubscriptionPlan::setFundBalanceForPayment($planRecordId, $value);
                return compact('plan', 'receipt', 'planRecordId', 'discount');
            }
            throw new Exception('Выбранного тарифа не существует.', 404);
        }
    }

    /**
     * Метод вызывается при обновлении поля для ввода суммы пополения личного кабинета
     * json
     *
     * @return mixed
     */
    public function updateReceipt()
    {
        if ($plan = SubscriptionPlan::first($this->request->data['projectId'])) {
            if ($this->userHelper->isPitchOwner($plan->user_id)) {
                Receipt::updateOrCreateReceiptForProject($plan->id, $this->request->data['updatedReceipt']);
                SubscriptionPlan::setTotalOfPayment($plan->id, Receipt::getTotalForProject($plan->id));
                SubscriptionPlan::setFundBalanceForPayment($plan->id, (int) $this->request->data['newFundValue']);
                $fundBalance = SubscriptionPlan::getFundBalanceForPayment((int) $this->request->data['projectId']);
                return compact('fundBalance');
            }
        }
    }
}
