<?php

namespace app\controllers;

use app\extensions\helper\MoneyFormatter;
use \app\models\Pitch;
use app\extensions\billing\Payture;
use app\models\Payment;
use app\models\SubscriptionPlan;
use \lithium\analysis\Logger;
use \Exception;


class PaymentsController extends AppController {

    public $publicActions = array('payture_callback', 'startpayment');

    /**
     * Обработка платежных уведомлений от Payture
     */
    public function payture_callback() {
        if (!empty($this->request->data)) {
            if(($this->request->data['SessionType'] == 'Pay') && ($this->request->data['Success'] == 'True')) {
                $transaction = Payment::create();
                $transaction->set($this->request->data);
                $transaction->save();
                $paytureId = $this->request->data['OrderId'];
                Logger::write('info', $paytureId, array('name' => 'payture'));
                if ($pitch = Pitch::first(array('conditions' => array('payture_id' => $paytureId)))) {
                    if ($pitch->blank == 1) {
                        Pitch::activateLogoSalePitch($pitch->id);
                    }else {
                        if ($pitch->multiwinner != 0) {
                            Pitch::activateNewWinner($pitch->id);
                        } else {
                            Pitch::activate($pitch->id);
                        }
                    }
                } elseif ($addon = Addon::first(array('conditions' => array('payture_id' => $paytureId)))) {
                    Addon::activate($addon);
                }
            }
        }
        Logger::write('info', serialize($this->request->data), array('name' => 'payture'));
        header("HTTP/1.0 200 OK");
        die();
    }

    /**
     * Метод для инициации платежа через систему payture
     *
     * @responds_to redirect
     * @return object
     * @throws Exception
     */
    public function startpayment() {
        if($pitch = Pitch::first($this->request->id)) {
            $totalInCents = (int) $pitch->total * 100;
            $formatter = new MoneyFormatter();
            $pitch = Pitch::generateNewPaytureId($this->request->id);
            $result = Payture::init(array(
                'SessionType' => 'Pay',
                'OrderId' => $pitch->payture_id,
                'Amount' => $totalInCents,
                'Url' => 'http://godesigner.ru/users/mypitches',
                'Total' => $formatter->formatMoney($pitch->total, array('suffix' => '.00')),
                'Product' => 'Оплата проекта'
            ));
            $url = Payture::pay($result['SessionId']);
            return $this->redirect($url);
        }
        throw new \Exception('Public:Такого проекта не существует.', 404);
    }

}