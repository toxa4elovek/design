<?php
namespace app\models;

/**
 * Class SubscriptionPlan
 *
 * Модель для работы с тарифными планами
 *
 * @package app\models
 */
class SubscriptionPlan extends Pitch {

    /**
     * Для хранения используем таблицу pitches
     *
     * @var array
     */
    protected $_meta = array(
        'source' => 'pitches',
    );

    /**
     * Данные тарифных планов
     *
     * @var array
     */
    protected static $_plans = array(
        1 => array(
            'price' => 49000,
            'title' => 'Предпринимательский'
        ),
        2 => array(
            'price' => 69000,
            'title' => 'Фирменный'
        ),
        3 => array(
            'price' => 89000,
            'title' => 'Корпоративный'
        )
    );

    /**
     * Метод возвращяет запрошенный тарифный план
     *
     * @param $id
     * @return null|array
     */
    static public function getPlan($id) {
        $id = (int) $id;
        if(isset(self::$_plans[$id])) {
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
    static public function getNextSubscriptionPlanId($userId) {
        if(!$payment = self::first(array(
            'conditions' => array(
                'user_id' => $userId,
                'billed' => 0,
                'type' => 'plan-payment'
            )
        ))) {
            $data = array(
                'user_id' => $userId,
                'type' => 'plan-payment',
                'category' => 100,
                'title' => 'Оплата абонентского обслуживания'
            );
            $payment = self::create($data);
            $payment->save();
        }
        return $payment->id;
    }

    /**
     * Метод возвращяет следующий зарезервированный айди для платежа за тарифный план
     *
     * @param $userId int пользователя
     * @return int
     */
    static public function getNextFundBalanceId($userId) {
        if(!$payment = self::first(array(
            'conditions' => array(
                'user_id' => $userId,
                'billed' => 0,
                'type' => 'fund-balance'
            )
        ))) {
            $data = array(
                'user_id' => $userId,
                'type' => 'fund-balance',
                'category' => 99,
                'title' => 'Пополнение счёта'
            );
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
    public function setTotal($payment, $value) {
        $payment->total = (int) $value;
        $payment->price = (int) $value;
        return $payment->save();
    }

    static public function setTotalOfPayment($id, $value) {
        $payment = self::first($id);
        return $payment->setTotal((int) $value);
    }
}