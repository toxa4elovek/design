<?php
namespace app\models;

use lithium\data\entity\Record;

/**
 * Class SubscriptionPlan
 *
 * Модель для работы с тарифными планами
 *
 * @package app\models
 * @method Record|null first(int|array $conditions) static
 */
class SubscriptionPlan extends Pitch
{

    /**
     * Для хранения используем таблицу pitches
     *
     * @var array
     */
    protected $_meta = [
        'source' => 'pitches',
    ];

    /**
     * Данные тарифных планов
     *
     * @var array
     */
    protected static $_plans = [
        1 => [
            'id' => 1,
            'price' => 49000,
            'title' => 'Предпринимательский',
            'duration' => YEAR,
            'free' => [],
        ],
        2 => [
            'id' => 2,
            'price' => 69000,
            'title' => 'Фирменный',
            'duration' => YEAR,
            'free' => ['chooseWinnerFinishDate', 'hideproject'],
        ],
        3 => [
            'id' => 3,
            'price' => 89000,
            'title' => 'Корпоративный',
            'duration' => YEAR,
            'free' => ['chooseWinnerFinishDate', 'hideproject', 'phonebrief', 'pinproject'],
        ],
        4 => [
            'id' => 4,
            'price' => 39000,
            'title' => 'Золотая рыбка',
            'duration' => YEAR,
            'free' => ['chooseWinnerFinishDate', 'hideproject'],
        ]
    ];

    /**
     * Метод возвращяет запрошенный тарифный план
     *
     * @param $id
     * @return null|array
     */
    public static function getPlan($id)
    {
        $id = (int) $id;
        if (isset(self::$_plans[$id])) {
            return self::$_plans[$id];
        }
        return null;
    }

    /**
     * Метод возвращяет следующий зарезервированный айди для платежа за тарифный план
     *
     * @param $userId int пользователя
     * @return int
     */
    public static function getNextSubscriptionPlanId($userId)
    {
        if (!$payment = self::first([
            'conditions' => [
                'user_id' => $userId,
                'billed' => 0,
                'type' => 'plan-payment'
            ]
        ])) {
            $gatracking = new \Racecore\GATracking\GATracking('UA-9235854-5');
            $gaId = $gatracking->getClientId();
            $data = [
                'user_id' => $userId,
                'type' => 'plan-payment',
                'category' => 100,
                'title' => 'Оплата абонентского обслуживания',
                'ga_id' => $gaId
            ];
            $payment = self::create($data);
            $payment->save();
        }
        return $payment->id;
    }

    /**
     * Метод возвращяет следующий зарезервированный айди для платежа за тарифный план для анонимных пользователей
     *
     * @param $googleAnalyticsId
     * @param $promocode
     * @return mixed
     */
    public static function getNextSubscriptionPlanIdByGAId($googleAnalyticsId, $promocode)
    {
        if (!$payment = self::first([
            'conditions' => [
                'ga_id' => $googleAnalyticsId,
                'billed' => 0,
                'type' => 'plan-payment'
            ]
        ])) {
            $data = [
                'type' => 'plan-payment',
                'category' => 100,
                'title' => 'Оплата абонентского обслуживания',
                'promocode' => $promocode,
                'ga_id' => $googleAnalyticsId
            ];
            $payment = self::create($data);
            $payment->save();
        }
        return $payment->id;
    }

    public static function hasSubscriptionPlanDraft($userId)
    {
        return (bool) self::first([
            'conditions' => [
                'user_id' => $userId,
                'billed' => 0,
                'type' => 'plan-payment'
            ]
        ]);
    }

    /**
     * Метод возвращяет следующий зарезервированный айди для платежа за тарифный план
     *
     * @param $userId int пользователя
     * @return int
     */
    public static function getNextFundBalanceId($userId)
    {
        if (!$payment = self::first([
            'conditions' => [
                'user_id' => $userId,
                'billed' => 0,
                'type' => 'fund-balance'
            ]
        ])) {
            $gatracking = new \Racecore\GATracking\GATracking('UA-9235854-5');
            $gaId = $gatracking->getClientId();
            $data = [
                'user_id' => $userId,
                'type' => 'fund-balance',
                'category' => 99,
                'title' => 'Пополнение счёта',
                'ga_id' => $gaId
            ];
            $payment = self::create($data);
            $payment->save();
        }
        return $payment->id;
    }

    /**
     * Метод устанавливает и сохраняет новую сумму для записи
     *
     * @param $payment
     * @param $value int
     * @return bool
     */
    public function setTotal($payment, $value)
    {
        $payment->total = (int) $value;
        $payment->price = (int) $value;
        return $payment->save();
    }

    /**
     * @param $record Record
     * @param $value int
     * @return mixed
     */
    public function setTotalOfPaymentForRecord(Record $record, $value)
    {
        return $record->setTotal((int) $value);
    }

    /**
     * Метод устаналивает и сохраняет новую сумму для проекта
     *
     * @param $id int
     * @param $value int
     * @return mixed
     */
    public static function setTotalOfPayment($id, $value)
    {
        $payment = self::first($id);
        return $payment->setTotalOfPaymentForRecord((int) $value);
    }

    /**
     * Метод активаирует тарифный план
     *
     * @param $id
     * @return bool
     */
    public static function activatePlanPayment($id)
    {
        if ($paymentPlan = self::first($id)) {
            $finalResult = false;
            $paymentPlan->billed = 1;
            $paymentPlan->started = date('Y-m-d H:i:s');
            $paymentPlan->totalFinishDate = date('Y-m-d H:i:s');
            $paymentPlan->billed_date = date('Y-m-d H:i:s');
            $companyName = User::getFullCompanyName($paymentPlan->user_id);
            if ($companyName != '') {
                $paymentPlan->title = $paymentPlan->title . ' (' . $companyName . ')';
            }
            $paymentPlan->status = 2;
            $paymentPlan->save();
            if ($planId = self::getPlanForPayment($id)) {
                if ($plan = self::getPlan($planId)) {
                    $finalResult = User::activateSubscription($paymentPlan->user_id, $plan);
                }
            }
            if (($fundBalance = self::getFundBalanceForPayment($id)) && ($paymentPlan !== 'fund-balance') && ($paymentPlan->getPlanForPaymentForRecord() !== 0)) {
                $result = User::fillBalance($paymentPlan->user_id, $fundBalance);
                Task::createNewTask($paymentPlan->id, 'emailFillBalanceSuccessNotification');
                if (!$finalResult) {
                    $finalResult = $result;
                }
            } elseif (($paymentPlan->type === 'fund-balance') || ($paymentPlan->getPlanForPaymentForRecord() === 0)) {
                $result = User::fillBalance($paymentPlan->user_id, (int) $paymentPlan->total);
                Task::createNewTask($paymentPlan->id, 'emailFillBalanceSuccessNotification');
                if (!$finalResult) {
                    $finalResult = $result;
                }
            }
            return $finalResult;
        }
        return false;
    }

    /**
     * Метод устанавливает план для указанного проекта
     *
     * @param $id
     * @param $planId
     * @return bool
     */
    public static function setPlanForPayment($id, $planId)
    {
        if ($plan = self::first($id)) {
            if (!$array = unserialize($plan->specifics)) {
                $array = [];
            }
            $array['plan_id'] = $planId;
            $plan->specifics = serialize($array);
            return $plan->save();
        }
        return false;
    }

    /**
     * Метод устанавливат значение пополнения счёта для плана/платежа
     *
     * @param $id int
     * @param $balance int
     * @return bool
     */
    public static function setFundBalanceForPayment($id, $balance)
    {
        if ($plan = self::first($id)) {
            if (!$array = unserialize($plan->specifics)) {
                $array = [];
            }
            $array['fund_balance'] = $balance;
            $plan->specifics = serialize($array);
            return $plan->save();
        }
        return false;
    }

    /**
     * Метод возвращяет сумму пополнения
     *
     * @param $planRecord Record
     * @return int|null
     */
    public function getFundBalanceForPaymentForRecord(Record $planRecord)
    {
        if ($plan = self::first($planRecord->id)) {
            if (!$array = unserialize($plan->specifics)) {
                return null;
            }
            if (!isset($array['fund_balance'])) {
                return null;
            }
            return (int) $array['fund_balance'];
        }
        return null;
    }

    /**
     * Метод возвращяет сумму пополнения
     *
     * @param $id int
     * @return int|null
     */
    public static function getFundBalanceForPayment($id)
    {
        if ($plan = self::first($id)) {
            return $plan->getFundBalanceForPaymentForRecord();
        }
        return null;
    }

    /**
     * Метод возвращяет айди плана, если он установлен в плане
     *
     * @param $planRecord
     * @return int|null
     */
    public function getPlanForPaymentForRecord(Record $planRecord)
    {
        if (!$array = unserialize($planRecord->specifics)) {
            return null;
        }
        if (!isset($array['plan_id'])) {
            return null;
        }
        return (int) $array['plan_id'];
    }

    /**
     * Метод возвращяет айди плана, если он установлен в плане
     *
     * @param $id
     * @return int|null
     */
    public static function getPlanForPayment($id)
    {
        if ($plan = self::first($id)) {
            return $plan->getPlanForPaymentForRecord();
        }
        return null;
    }

    /**
     * Метод вовзращяает сохраненную сумму пополнения счета
     *
     * @param $id
     * @return int
     */
    public static function extractFundBalanceAmount($id)
    {
        if ($plan = self::first($id)) {
            if (!$array = unserialize($plan->specifics)) {
                return 0;
            }
            if (!isset($array['fund_balance'])) {
                return 0;
            }
            return $array['fund_balance'];
        }
        return 0;
    }
}
