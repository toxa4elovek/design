<?php

namespace li3_oauth\controllers;

use \li3_oauth\models\VkontakteConsumer;
use \lithium\storage\Session;
use \lithium\core\Environment;

class VkontakteController extends \li3_oauth\controllers\ClientController {

    protected function _init() {
        parent::_init();
        VkontakteConsumer::config(array(
            'client_id' => 2950889, //2305673
            'redirect_uri' => 'http://www.godesigner.ru/vkontakte', // http://94.127.68.233/vkontakte'
            'display' => 'page',
            'scheme' => 'https',
            'host' => 'api.vkontakte.ru',
            'port' => 80,
            'authorize' => '/oauth/authorize',
            'access' => '/oauth/access_token',
            'client_secret' => 'j1bFzKfXP4lIa0wA7vaV' //dljTfoQUw91IiJn7k0md
        ));
    }

    public function index() {
        $token = Session::read('oauth.access.access_token');
        if ((empty($token) && (!empty($this->request->query['code'])))) {
            Session::write('oauth2.code', $this->request->query['code']);
            return $this->redirect('Vkontakte::access');
        }
        if (empty($token)) {
            return $this->redirect('Vkontakte::authorize');
        }
        $user = json_decode(VkontakteConsumer::getUser(Session::read('oauth.access.user_id'), Session::read('oauth.access.access_token'),'sex'), true);
        Session::write('vk_data.service', 'Vkontakte');
        Session::write('vk_data.screen_name', $user['response'][0]['first_name'] . ' ' . $user['response'][0]['last_name']);
        Session::write('vk_data.gender', $user['response'][0]['sex']);
        Session::write('vk_data.uid', $user['response'][0]['uid']);
        Session::write('vk_data.email', Session::read('oauth.access.email'));
        return $this->redirect('Users::registration');
    }

    public function authorize() {
        return $this->redirect(VkontakteConsumer::authorize());
    }

    public function access() {
        $code = Session::read('oauth2.code');
        $response = json_decode(VkontakteConsumer::access($code), true);
        if ((!isset($response['access_token'])) && (isset($response['error']))) {
            return $this->redirect('Vkontakte::authorize');
        }
        Session::write('oauth.access', $response);
        return $this->redirect('Vkontakte::index');
    }

    public function login() {
        $token = Session::read('oauth.request');
        if (empty($token)) {
            $this->redirect('Vkontakte::authorize');
        }
        return $this->redirect(VkontakteConsumer::authenticate($token));
    }

}

?>