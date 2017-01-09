<?php

namespace li3_oauth\controllers;

use \li3_oauth\models\Consumer;
use \lithium\storage\Session;
use \lithium\core\Environment;

class TweetController extends \li3_oauth\controllers\ClientController
{

    protected function _init()
    {
        parent::_init();
        if (Environment::is('development')) {
            Consumer::config([
                'host' => 'twitter.com',
                'oauth_consumer_key' => 'zNBZnNqXGryqE3RYfG2oA', //eRrgRdTM8GOqSxn2ppayhg
                'oauth_consumer_secret' => 'ECeiYjZxonMph4RmYWyBKbaWqcLGU5MgG6J9JLkM', //kqrS9gxU44UeF8wmSXUuygSVI8rNhVLAvJ35Bs37qkU
            ]);
        } else {
            Consumer::config([
                'host' => 'twitter.com',
                'oauth_consumer_key' => 'eRrgRdTM8GOqSxn2ppayhg', //
                'oauth_consumer_secret' => 'kqrS9gxU44UeF8wmSXUuygSVI8rNhVLAvJ35Bs37qkU', //
            ]);
        }
    }

    public function index()
    {
        $message = null;
        $token = Session::read('oauth.access');

        if (empty($token) && !empty($this->request->query['oauth_token'])) {
            return $this->redirect('Tweet::access');
        }
        if (empty($token)) {
            return $this->redirect('Tweet::authorize');
        }
        Session::write('user.social.service', 'Twitter');
        Session::write('user.social.screen_name', Session::read('oauth.access.screen_name'));
        Session::write('user.social.uid', Session::read('oauth.access.user_id'));
        return $this->redirect('Users::socialconnect');
    }

    public function authorize()
    {
        $token = Consumer::token('request');
        if (is_string($token)) {
            return $token;
        }
        Session::write('oauth.request', $token);
        return $this->redirect(Consumer::authorize($token));
    }

    public function access()
    {
        $token = Session::read('oauth.request');
        $access = Consumer::token('access', compact('token'));
        Session::write('oauth.access', $access);
        return $this->redirect('Tweet::index');
    }

    public function login()
    {
        $token = Session::read('oauth.request');
        if (empty($token)) {
            $this->redirect('Tweet::authorize');
        }
        return $this->redirect(Consumer::authenticate($token));
    }
}
