<?php

namespace li3_oauth\controllers;

use \li3_oauth\models\MailruConsumer;
use \lithium\storage\Session;
use \lithium\core\Environment;

class MailruController extends \li3_oauth\controllers\ClientController
{

    protected function _init()
    {
        parent::_init();
        if (Environment::is('development')) {
            MailruConsumer::config([
                'host' => 'www.appsmail.ru/platform/api',
                'scheme' => 'http',
                'secret_key' => 'cb0e5f79050e676f948ebc7dbfbcc33a',
                'private' => '243de57bbcb1972e24b5f2676481e239',
                'app_id' => '615163',
            ]);
        } else {
            MailruConsumer::config([
                'host' => 'www.appsmail.ru/platform/api',
                'scheme' => 'http',
                'secret_key' => 'c6789be55aae019203cbbfe67b178de9',
                'private' => 'f0f2efbc66d90477cd1185cdf068d711',
                'app_id' => '617235',
            ]);
        }
    }

    public function index()
    {
        $getMailRuCookieArray = function () {
            parse_str($_COOKIE['mrc']);
            return get_defined_vars();
        };
        
        if ($getMailRuCookieArray() == $this->request->data) {
            if ($user = MailruConsumer::getInfo($this->request->data)) {
                Session::write('user.social.service', 'Mail.ru');
                Session::write('user.social.screen_name', $user['nick']);
                Session::write('user.social.uid', $user['uid']);
                return $this->render(['json' => ['error' => false]]);
            } else {
                return $this->render(['json' => ['error' => true]]);
            }
        } else {
            return $this->render(['json' => ['error' => true]]);
        }
    }
}
