<?php
namespace app\extensions\helper;

/**
 * Class User - Хелпер для различных проверок текущего пользователя
 * Взаимодействует с моделью User и Expert
 * @package app\extensions\helper
 */
class User extends \app\extensions\helper\Session {

    /**
     * @var array Массив хранит айди админом
     */
    public $adminsIds = array();

    /**
     * @var array Массив хранит айди экспертов
     */
    public $expertsIds = array();

    /**
     * @var array Массив хранит айди редакторов блога
     */
    public $editorsIds = array();

    protected $_options = array();

    /**
     * Конструктор устанавливает свойства
     */
    public function __construct($config) {
        $defaults = array(
            'userModel' => 'app\models\User',
            'expertModel' => 'app\models\Expert',
            'inflector' => 'app\extensions\helper\NameInflector'
        );
        $this->_options = $options =  $config + $defaults;
        $this->adminsIds = $options['userModel']::$admins;
        $this->expertsIds = $options['expertModel']::getExpertUserIds();
        $this->editorsIds = $options['userModel']::$editors;
    }

    /**
     * Метод определяет, является ли текущий пользователь экспертом
     *
     * @param $expertsIds - Список айдишников эксперта
     * @return bool - Является ли пользователь экспертом
     */
    public function isExpert() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return in_array($this->read('user.id'), $this->expertsIds);
    }

    /**
     * Метод определяет, является ли текущий пользователь админом
     *
     * @return bool - Является ли пользователь админом
     */
    public function isAdmin() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        if(($this->read('user.isAdmin')) || (in_array($this->read('user.id'), $this->adminsIds))) {
            return true;
        }
        return false;
    }

    /**
     * Метод определяет, является ли текущий пользователь редактором блога
     *
     * @return bool
     */
    public function isEditor() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return in_array($this->read('user.id'), $this->editorsIds);
    }

    /**
     * Метод определяет, залогинен ли текущий пользователь
     *
     * @return bool
     */
    public function isLoggedIn() {
        return (bool) $this->read('user');
    }

    /**
     * Метод определяет, является ли текущий пользователь владельцем питча с айди $pitchUserId
     *
     * @param $pitchUserId - айди владельца питча
     * @return bool
     */
    public function isPitchOwner($pitchUserId) {
        return $this->__detectOwnership($pitchUserId);
    }

    /**
     * Метод определяет, является ли текущий пользователь автором решения с айди $solutionUserId
     *
     * @param $solutionUserId
     * @return bool
     */
    public function isSolutionAuthor($solutionUserId) {
        return $this->__detectOwnership($solutionUserId);
    }

    /**
     * Метод определяет, является ли текущий пользователь автором комментария с айди $commentAuthorId
     *
     * @param $commentAuthorId
     * @return bool
     */
    public function isCommentAuthor($commentAuthorId) {
        return $this->__detectOwnership($commentAuthorId);
    }

    /**
     * Метод возвращает айди пользователя или false, если он не залогинен
     *
     * @return bool|mixed
     */
    public function getId() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return $this->read('user.id');
    }

    /**
     * Метод возвращает имя пользователя или false, если он не залогинен
     *
     * @return bool|mixed
     */
    public function getFirstname() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return $this->read('user.first_name');
    }

    /**
     * Метод возвращает фамилию пользователя или false, если он не залогинен
     *
     * @return bool|mixed
     */
    public function getLastname() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return $this->read('user.last_name');
    }

    /**
     * Метод возвращает email или false, если он не залогинен
     *
     * @return bool|mixed
     */
    public function getEmail() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return $this->read('user.email');
    }

    public function getFormattedName() {
        if(!$this->isLoggedIn()) {
            return false;
        }
        $inflectorClassName = $this->_options['inflector'];
        return $inflectorClassName::renderName($this->getFirstname(), $this->getLastname());
    }

    /**
     * Приватный метод помощник, сравнивает аргумент @model_id с текущим user.id, если установлен
     *
     * @param $model_id
     * @return bool
     */
    private function __detectOwnership($model_id) {
        if(!$this->isLoggedIn()) {
            return false;
        }
        return $model_id == $this->read('user.id');
    }

}