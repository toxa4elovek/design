<?php

namespace app\models;

use \app\models\Pitch;
use \app\models\User;

class Addon extends \app\models\AppModel {

    public $belongsTo = array('Pitch');

    public static function __init() {
        parent::__init();
        self::applyFilter('activate', function($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if ($result) {
                User::sendAdminNewAddon($params['addon']);
                if ($params['addon']->brief == 1) {
                    Pitch::addBrief($params['addon']);
                    User::sendAdminNewAddonBrief($params['addon']);
                }
                if ($params['addon']->experts == 1) {
                    Pitch::addExpert($params['addon']);
                    User::sendExpertMail($params['addon']);
                }
                if ($params['addon']->prolong == 1 && $params['addon']->{'prolong-days'} > 0) {
                    Pitch::addProlong($params['addon']);
                }
            }
            return $result;
        });
    }

    public static function activate($addon) {
        $params = compact('addon');
        return static::_filter(__FUNCTION__, $params, function($self, $params) {
            extract($params);
            $addon->billed = 1;
            return $addon->save();
        });
    }
}
