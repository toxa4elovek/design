<?php

namespace app\controllers;

use app\models\Promocode;

/**
 * Class PromocodesController
 *
 * Метод для запросов проверки промокодов
 *
 * @package app\controllers
 */
class PromocodesController extends AppController
{

    /**
     * @var array публичные методы
     */
    public $publicActions = ['check'];

    /**
     * Метод проверяет, введен ли действующий промокод
     *
     * @return string
     */
    public function check()
    {
        $projectId = null;
        if (isset($this->request->data['id'])) {
            $projectId = (int) $this->request->data['id'];
        }
        return Promocode::checkPromocode($this->request->data['code'], $projectId);
    }
}
