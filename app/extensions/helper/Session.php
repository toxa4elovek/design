<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmitriynyu
 * Date: 12/15/11
 * Time: 2:23 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\extensions\helper;

class Session extends \lithium\storage\Session
{

    /**
     * Метод определяет, залогинен ли текущий пользователь
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return (bool) self::read('user');
    }
}
