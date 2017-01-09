<?php

namespace app\models;

/**
 * Class Request
 *
 * Класс для управления запросами входа в скрытые проекты
 *
 * @package app\models
 */
class Request extends AppModel
{

    /**
     * @var array Связи
     */

    public $belongsTo = ['User'];
}
