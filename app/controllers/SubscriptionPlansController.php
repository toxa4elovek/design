<?php

namespace app\controllers;

use app\models\Url;
use \Exception;
use app\models\SubscriptionPlan;
use app\models\Receipt;
use app\models\User;

class SubscriptionPlansController extends AppController
{
    /**
     * @var array публичные методы
     */
    public $publicActions = [
        'subscriber', 'updatePhone', 'updateReceipt'
    ];

    /**
     * Метод для отображения страницы пополнения баланса личного кабинета и
     * активации тарифа
     */
    public function subscriber()
    {
        if (null === $this->request->params['id']) {
            if (!$this->userHelper->isLoggedIn()) {
                $this->redirect('/users/login');
            }
            $planRecordId = SubscriptionPlan::getNextFundBalanceId($this->userHelper->getId());
            $value = 9000;
            if (isset($this->request->query['amount']) && (!empty($this->request->query['amount']))) {
                $predefined = true;
                $value = (int) $this->request->query['amount'];
            }
            $receipt = [
                [
                    'name' => 'Пополнение счёта',
                    'value' => $value
                ]
            ];
            Receipt::updateOrCreateReceiptForProject($planRecordId, $receipt);
            SubscriptionPlan::setTotalOfPayment($planRecordId, Receipt::getTotalForProject($planRecordId));
            SubscriptionPlan::setPlanForPayment($planRecordId, 0);
            SubscriptionPlan::setFundBalanceForPayment($planRecordId, 9000);
            $shortUrl = $this->_getShortUrl();
            return compact('receipt', 'planRecordId', 'predefined', 'shortUrl');
        } else {
            if ($plan = SubscriptionPlan::getPlan((int)$this->request->params['id'])) {
                if ($this->userHelper->getId()) {
                    $planRecordId = SubscriptionPlan::getNextSubscriptionPlanId($this->userHelper->getId());
                } else {
                    $gaTracking = new \Racecore\GATracking\GATracking('UA-9235854-5');
                    if(isset($_COOKIE['sref'])) {
                        $promocode = $_COOKIE['sref'];
                    }else if($_COOKIE['sref2']) {
                        $promocode = $_COOKIE['sref2'];
                    }
                    $planRecordId = SubscriptionPlan::getNextSubscriptionPlanIdByGAId($gaTracking->getClientId(), $promocode);
                }

                $value = 9000;
                if (($savedValue = SubscriptionPlan::getFundBalanceForPayment($planRecordId)) && ($savedValue !== null)) {
                    $value = $savedValue;
                }
                $wording = 'тарифа';
                if ($plan['id'] === 4) {
                    $wording = 'бизнес-плана';
                }
                $receipt = [
                    [
                        'name' => 'Оплата ' . $wording . ' «' . $plan['title'] . '»',
                        'value' => $plan['price']
                    ],
                    [
                        'name' => 'Пополнение счёта',
                        'value' => $value
                    ]
                ];
                $discount = 0;
                if (User::hasActiveSubscriptionDiscount($this->userHelper->getId())) {
                    $discount = User::getSubscriptionDiscount($this->userHelper->getId());
                    $discountValue = -1 * ($plan['price'] - $this->money->applyDiscount($plan['price'], $discount));
                    $receipt = Receipt::addRow($receipt, "Скидка — $discount%", $discountValue);
                }
                if ((($this->discountForSubscriberReferal > 0) && isset($_COOKIE['sreftime'])) ||
                (in_array($plan['id'], [1, 2, 3], true) && ($this->discountForSubscriberReferal > 0) && isset($_COOKIE['sref2time']))){
                    if(isset($_COOKIE['sreftime'])) {
                        $startTimeCookieValue = $_COOKIE['sreftime'];
                    }else if($_COOKIE['sref2time']) {
                        $startTimeCookieValue = $_COOKIE['sref2time'];
                    }
                    $startTime = strtotime(date(MYSQL_DATETIME_FORMAT, $startTimeCookieValue));
                    $delta = (time() - $startTime);
                    if (floor($delta / DAY) < 10) {
                        $discount = $this->discountForSubscriberReferal;
                        $discountValue = -1 * ($plan['price'] - $this->money->applyDiscount($plan['price'], $discount));
                        $receipt = Receipt::addRow($receipt, "Скидка — $discount%", $discountValue);
                    }
                }
                Receipt::updateOrCreateReceiptForProject($planRecordId, $receipt);
                SubscriptionPlan::setTotalOfPayment($planRecordId, Receipt::getTotalForProject($planRecordId));
                SubscriptionPlan::setPlanForPayment($planRecordId, $plan['id']);
                SubscriptionPlan::setFundBalanceForPayment($planRecordId, $value);
                $shortUrl = $this->_getShortUrl();
                return compact('plan', 'receipt', 'planRecordId', 'discount', 'shortUrl');
            }
            throw new Exception('Выбранного тарифа не существует.', 404);
        }
    }

    private function _getShortUrl() {
        $shortUrl = '';
        if ($this->userHelper->isLoggedIn()) {
            if (empty($this->userRecord->subscriber2_referal_token)) {
                $this->userRecord->subscriber2_referal_token = User::generateSubscriberReferalToken(4, 'subscriber2_referal_token');
                $this->userRecord->save(null, ['validate' => false]);
            }
            $fullUrl = 'https://godesigner.ru/pages/subscribe?sref2=' . $this->userRecord->subscriber2_referal_token;
            $shortUrl = 'https://godesigner.ru/urls/' . Url::getShortUrlFor($fullUrl);
        }
        return $shortUrl;
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
            $gaTracking = new \Racecore\GATracking\GATracking('UA-9235854-5');
            if (($this->userHelper->isPitchOwner($plan->user_id)) || ($plan->ga_id === $gaTracking->getClientId())) {
                Receipt::updateOrCreateReceiptForProject($plan->id, $this->request->data['updatedReceipt']);
                SubscriptionPlan::setTotalOfPayment($plan->id, Receipt::getTotalForProject($plan->id));
                SubscriptionPlan::setFundBalanceForPayment($plan->id, (int) $this->request->data['newFundValue']);
            }
            $fundBalance = SubscriptionPlan::getFundBalanceForPayment((int) $this->request->data['projectId']);
            return compact('fundBalance');
        }
    }

    /**
     * Метод обновляет номер телефона для проекта определенного пользователя $gaId
     *
     * @return mixed
     */
    public function updatePhone()
    {
        $gaTracking = new \Racecore\GATracking\GATracking('UA-9235854-5');
        if ($draftPlan = SubscriptionPlan::first(['conditions' => [
            'id' => $this->request->data['projectId'],
            'ga_id' => $gaTracking->getClientId()
        ]])) {
            $draftPlan->{'phone-brief'} = $this->request->data['newPhone'];
            $draftPlan->save();
        }
        return $this->request->data;
    }
}
