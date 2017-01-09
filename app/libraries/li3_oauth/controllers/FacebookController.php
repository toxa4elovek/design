<?php

namespace li3_oauth\controllers;

use \li3_oauth\models\FacebookConsumer;
use \lithium\storage\Session;
use \lithium\core\Environment;

class FacebookController extends \li3_oauth\controllers\ClientController
{

    protected function _init()
    {
        parent::_init();
        //if(Environment::is('development')) {
            FacebookConsumer::config([
                'client_id' => '202765613136579', //199231630114741
                'client_secret' => '404ec2eea7487d85eb69ecceea341821',  //0bc25557657d3b6bff46ae4b0ec90402
                'redirect_uri' => 'http://godesigner.ru/facebook', //'http://94.127.68.233/facebook'
                'display' => 'page',
                'scheme' => 'https',
                'auth_host' => 'www.facebook.com/dialog',
                'host' => 'graph.facebook.com',
                'port' => 80,
                'scope' => 'email,user_about_me',
                'authorize' => '/oauth',
                'access' => '/oauth/access_token',
                
            ]);
        /*}else {
            FacebookConsumer::config(array(
                'client_id' => '199231630114741',//
                'client_secret' => '0bc25557657d3b6bff46ae4b0ec90402',  //
                'redirect_uri' => 'http://94.127.68.233/facebook',//''
                'display' => 'page',
                'scheme' => 'https',
                'auth_host' => 'www.facebook.com/dialog',
                'host' => 'graph.facebook.com',
                'port' => 80,
                'scope' => 'user_about_me',
                'authorize' => '/oauth',
                'access' => '/oauth/access_token',
                
            ));
        }*/
    }

    public function index()
    {
        //Session::delete('oauth2');die();

        $token = Session::read('oauth2.access.access_token');
        if ((empty($token) && (!empty($this->request->query['code'])))) {
            Session::write('oauth2.code', $this->request->query['code']);
            return $this->redirect('Facebook::access');
        }
        if (empty($token)) {
            return $this->redirect('Facebook::authorize');
        }
        $graph_url = "https://graph.facebook.com/me?access_token=" . Session::read('oauth2.access.access_token');
        $user = json_decode(file_get_contents($graph_url), true);
        Session::write('user.social.service', 'Facebook');
        Session::write('user.social.screen_name', $user->name);
        Session::write('user.social.uid', $user->id);
        Session::write('user.social.data', $user);
        return $this->redirect('Users::registration');
    }
    
    public function authorize()
    {
        return $this->redirect(FacebookConsumer::authorize());
    }


    public function access()
    {
        $code = Session::read('oauth2.code');
        $encodedResponse = FacebookConsumer::access($code);
        $response = json_decode($encodedResponse, true);
        $explodedResponse = explode('&', $encodedResponse);
        foreach ($explodedResponse as $item) {
            $explodedItem = explode('=', $item);
            $response[$explodedItem[0]] = $explodedItem[1];
        }
        if (isset($response['error'])) {
            return $this->redirect('Facebook::authorize');
        } else {
            $response = $response['access_token'];
        }
        Session::write('oauth2.access.access_token', $response);
        return $this->redirect('Facebook::index');
    }
}
