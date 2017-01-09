<?php

namespace li3_oauth\controllers;

use \li3_oauth\models\YandexConsumer;
use \lithium\storage\Session;
use \lithium\core\Environment;

require_once(LITHIUM_APP_PATH . '/libraries/openid/Auth/OpenID/Consumer.php');
require_once(LITHIUM_APP_PATH . '/libraries/openid/Auth/OpenID/FileStore.php');

class YandexController extends \li3_oauth\controllers\ClientController
{
    
    protected $_serviceConfig = [];
    
    protected function _init()
    {
        parent::_init();
        if (Environment::is('development')) {
            $this->_serviceConfig = [
                'base' => 'http://meditoria.ru',
                'redirect_to' => 'http://meditoria.ru/yandex'
            ];
        } else {
            $this->_serviceConfig = [
                'base' => 'http://94.127.68.233/',
                'redirect_to' => 'http://94.127.68.233/yandex'
            ];
        }
    }
    
    public function login()
    {
        $store = new \Auth_OpenID_FileStore(LITHIUM_APP_PATH . '/resources/tmp/oid_store');
        $consumer = new \Auth_OpenID_Consumer($store);
        $auth = $consumer->begin(YandexConsumer::getYaAddress($this->request->query['username']));
        if (!$auth) {
            return $this->redirect('Home::home');
        }
        $url = $auth->redirectURL($this->_serviceConfig['base'], $this->_serviceConfig['redirect_to']);
        return $this->redirect($url);
    }

    public function index()
    {
        $store = new \Auth_OpenID_FileStore(LITHIUM_APP_PATH . '/resources/tmp/oid_store');
        $consumer = new \Auth_OpenID_Consumer($store);
        
        //$query = \Auth_OpenID::getQuery();
        //$query['openid.return_to'] = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $response = $consumer->complete($this->_serviceConfig['redirect_to']);
        
        if ($response->status == Auth_OpenID_SUCCESS) {
            Session::write('user.social.service', 'Yandex');
            Session::write('user.social.screen_name', YandexConsumer::getUsername($response->identity_url));
            Session::write('user.social.uid', $response->identity_url);
            return $this->redirect('Users::socialconnect');
        } else {
            return $this->redirect('Yandex::login');
        }
    }
}
