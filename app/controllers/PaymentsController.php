<?php

namespace app\controllers;

use app\extensions\helper\MoneyFormatter;
use \app\models\Pitch;
use app\models\Addon;
use app\extensions\billing\Payture;
use app\models\Payment;
use app\models\SubscriptionPlan;
use \lithium\analysis\Logger;
use \Exception;

class PaymentsController extends AppController
{

    public $publicActions = ['payture_callback', 'startpayment'];

    /**
     * Обработка платежных уведомлений от Payture
     */
    public function payture_callback()
    {
        error_reporting(0);
        @ini_set('display_errors', 0);
        if (!empty($this->request->data)) {
            if (($this->request->data['SessionType'] == 'Pay') && ($this->request->data['Success'] == 'True')) {
                $transaction = Payment::create();
                $transaction->set($this->request->data);
                $transaction->save();
                $paytureId = $this->request->data['OrderId'];
                Logger::write('info', $paytureId, ['name' => 'payture']);
                if ($pitch = Pitch::first(['conditions' => ['payture_id' => $paytureId]])) {
                    if (($pitch->type == 'plan-payment') || ($pitch->type == 'fund-balance')) {
                        SubscriptionPlan::activatePlanPayment($pitch->id);
                    } elseif ($pitch->type == 'penalty') {
                        Pitch::activatePenalty($pitch->id);
                    } else {
                        if ($pitch->blank == 1) {
                            Pitch::activateLogoSalePitch($pitch->id);
                        } else {
                            if ($pitch->multiwinner != 0) {
                                Pitch::activateNewWinner($pitch->id);
                            } else {
                                Pitch::activate($pitch->id);
                            }
                        }
                    }
                } elseif ($addon = Addon::first(['conditions' => ['payture_id' => $paytureId]])) {
                    Addon::activate($addon);
                }
            }
        }
        Logger::write('info', serialize($this->request->data), ['name' => 'payture']);
        //header("HTTP/1.0 200 OK");
        die();
    }

    /**
     * Метод для инициации платежа через систему payture
     *
     * @responds_to redirect
     * @return object
     * @throws Exception
     */
    public function startpayment()
    {
        if ((isset($this->request->id)) && ($this->request->id === '0')) {
            return $this->redirect('/users/login');
        }
        if ($pitch = Pitch::first((int) $this->request->id)) {
            $totalInCents = (int) $pitch->total * 100;
            $formatter = new MoneyFormatter();
            $pitch = Pitch::generateNewPaytureId($this->request->id);
            $type = 'Pay';
            $url = 'https://godesigner.ru/users/mypitches';
            if($pitch->type === '1on1') {
                $type = 'Block';
                if ($data = unserialize($pitch->specifics)) {
                    $url = 'https://godesigner.ru/users/hireDesigner/' . $data['designer_id'];
                }
            }
            $result = Payture::init([
                'SessionType' => $type,
                'OrderId' => $pitch->payture_id,
                'Amount' => $totalInCents,
                'Url' => $url,
                'Total' => $formatter->formatMoney($pitch->total, ['suffix' => '.00']),
                'Product' => 'Оплата проекта'
            ]);
            $url = Payture::pay($result['SessionId']);
            return $this->redirect($url);
        }
        throw new \Exception('Public:Такого проекта не существует.', 404);
    }
}
