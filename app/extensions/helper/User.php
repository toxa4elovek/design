<?php
namespace app\extensions\helper;

class User extends \app\extensions\helper\Session {

    public $admins = array(32, 4, 5, 108, 81);

    /**
     * @param $expertsIds - Список айдишников эксперта
     * @return bool - Является ли пользователь экспертом
     */
    public function isExpert($expertsIds) {
        if(!$this->read('user')) {
            return false;
        }
        return in_array($this->read('user.id'), $expertsIds);
    }

    /**
     * @return bool - Является ли пользователь админом
     */
    public function isAdmin() {
        if(!$this->read('user')) {
            return false;
        }
        if(($this->read('user.isAdmin')) || (in_array($this->read('user.id'), $this->admins))) {
            return true;
        }
        return false;
    }

}