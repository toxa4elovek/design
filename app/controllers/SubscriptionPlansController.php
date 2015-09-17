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
            $planRecordId = SubscriptionPlan::getNextFundBalanceId($this->userHelper->getId());
            $receipt = array(
                array(
                    'name' => 'Пополнение счёта',
                    'value' => 9000
                )
            );
            Receipt::updateOrCreateReceiptForProject($planRecordId, $receipt);
            SubscriptionPlan::setTotalOfPayment($planRecordId, Receipt::getTotalForProject($planRecordId));
            SubscriptionPlan::setPlanForPayment($planRecordId, 0);
            SubscriptionPlan::setFundBalanceForPayment($planRecordId, 9000);
            return compact('receipt', 'planRecordId');
        }else {
            if ($plan = SubscriptionPlan::getPlan((int)$this->request->params['id'])) {
                $planRecordId = SubscriptionPlan::getNextSubscriptionPlanId($this->userHelper->getId());
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
                Receipt::updateOrCreateReceiptForProject($planRecordId, $receipt);
                SubscriptionPlan::setTotalOfPayment($planRecordId, Receipt::getTotalForProject($planRecordId));
                SubscriptionPlan::setPlanForPayment($planRecordId, $plan['id']);
                SubscriptionPlan::setFundBalanceForPayment($planRecordId, 9000);
                return compact('plan', 'receipt', 'planRecordId');
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
    public function updateReceipt() {
        if($plan = SubscriptionPlan::first($this->request->data['projectId'])) {
            if($this->userHelper->isPitchOwner($plan->user_id)) {
                Receipt::updateOrCreateReceiptForProject($plan->id, $this->request->data['updatedReceipt']);
                SubscriptionPlan::setTotalOfPayment($plan->id, Receipt::getTotalForProject($plan->id));
                foreach($this->request->data['updatedReceipt'] as $receiptRow) {
                    if($receiptRow['name'] == 'Пополнение счёта') {
                        $updatedValue = $receiptRow['value'];
                        SubscriptionPlan::setFundBalanceForPayment($plan->id, (int) $updatedValue);
                    }
                }
                return $this->request->data;
            }
        }
    }

    /**
     * Метод для тестовой активации любого платежа
     */
    public function activate() {
        if(($paymentRecord = SubscriptionPlan::first($this->request->data['projectId'])) &&
        ($this->userHelper->isPitchOwner($paymentRecord->user_id))) {
            $result = SubscriptionPlan::activatePlanPayment($this->request->data['projectId']);
            if($result) {
                $data = array(
                    'message' => 'Платеж успешно активирован, проверить можно по адресу http://www.godesigner.ru/users/subscriber'
                );
            }else {
                $data = array(
                    'message' => 'Что-то пошло не так, платеж не удалось активировать'
                );
            }
        }else {
            $data = array(
                'message' => 'Что-то пошло не так, платеж не принадлежит текущему пользователю'
            );
        }
        return compact('data');
    }
}