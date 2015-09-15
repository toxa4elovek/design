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
}