<?php

namespace app\controllers;

use \Exception;
use app\models\SubscriptionPlan;

class SubscriptionPlansController extends AppController {

    /**
     * Метод для отображения страницы пополнения баланса личного кабинета и
     * активации тарифа
     */
    public function subscriber() {
        if($plan = SubscriptionPlan::getPlan((int) $this->request->params['id'])) {
            $receipt = array(
                'name' => 'Оплата тарифа',
                'amount' => $plan['price']
            );
            return compact('plan', 'receipt');
        }
        throw new Exception('Выбранного тарифа не существует.', 404);
    }
}