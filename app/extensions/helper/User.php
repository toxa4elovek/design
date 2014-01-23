<?php
namespace app\extensions\helper;

use app\models\User as UserModel;
use app\models\Expert;

/**
 * Class User - Хелпер для различных проверок текущего пользователя
 * Взаимодействует с моделью User и Expert
 * @package app\extensions\helper
 */
class User extends \app\extensions\helper\Session {

    /**
     * @var array Массив хранит айди админом
     */
    public $adminIds = array();

    /**
     * @var array Массив хранит айди экспертов
     */
    public $expertIds = array();

    /**
     * Конструктор устанавливает свойства
     */
    public function __construct($config) {
        $defaults = array(
            'userModel' => 'app\models\User',
            'expertModel' => 'app\models\Expert'
        );
        $options = $config + $defaults;
        $this->adminIds = $options['userModel']::$admins;
        $this->expertIds = $options['expertModel']::getExpertUserIds();
    }

    /**
     * Метод определяет, является ли текущий пользователь экспертом
     *
     * @param $expertsIds - Список айдишников эксперта
     * @return bool - Является ли пользователь экспертом
     */
    public function isExpert() {
        if(!$this->read('user')) {
            return false;
        }
        return in_array($this->read('user.id'), $this->expertIds);
    }

    /**
     * Метод определяет, является ли текущий пользователь админом
     *
     * @return bool - Является ли пользователь админом
     */
    public function isAdmin() {
        if(!$this->read('user')) {
            return false;
        }
        if(($this->read('user.isAdmin')) || (in_array($this->read('user.id'), $this->adminIds))) {
            return true;
        }
        return false;
    }

}