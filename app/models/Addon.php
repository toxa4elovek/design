<?php

namespace app\models;

use app\models\Pitch;
use app\models\User;
use lithium\data\collection\RecordSet;
use lithium\data\entity\Record;

/**
 * Class User
 * @package app\models
 * @method Record|null first(array $conditions) static
 * @method int count(array $conditions) static
 * @method RecordSet|null all(array $conditions = []) static
 */
class Addon extends AppModel {

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
                if ($params['addon']->guaranteed == 1) {
                    Pitch::addGuaranteed($params['addon']);
                }
                if ($params['addon']->pinned == 1) {
                    Pitch::addPinned($params['addon']);
                }
                if ($params['addon']->private == 1) {
                    Pitch::addPrivate($params['addon']);
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

    public static function graphData($date) {
        $range = "'$date'";
        $addons = self::all(array(
            'conditions' => array(
                'YEAR(created) = YEAR(' . $range . ') AND MONTH(created) = MONTH( ' . $range . ')',
                'billed' => 1,
            ),
        ));
        $countArray = array();
        $addonsCount = 0;
        $addonsTotal = 0;
        $addonsProlong = 0;
        foreach ($addons as $addon) {
            $day = date('j', strtotime($addon->created));
            if (isset($countArray[$day])) {
                $countArray[$day] += 1;
            } else {
                $countArray[$day] = 1;
            }
            $addonsCount++;
            $addonsTotal += $addon->total;
            if ($addon->prolong == 1) {
                $addonsProlong += 1000 * $addon->{'prolong-days'};
            }
        }
        $values = array();
        $highestValue = 0;

        for ($i = 1; $i <= date('t', strtotime($date)); $i++) {
            if ((date('Y') == date('Y', strtotime($date))) && (date('n') == date('n', strtotime($date)))) {
                if ($i > date('j')) {
                    break;
                }
            }
            if (isset($countArray[$i])) {
                if ($highestValue < $countArray[$i]) {
                    $highestValue = $countArray[$i];
                }
                $values[] = $countArray[$i];
            } else {
                $values[] = 0;
            }
        }

        return array(
            'values' => $values,
            'highestValue' => $highestValue,
            'addonsCount' => $addonsCount,
            'addonsTotal' => $addonsTotal,
            'addonsProlong' => $addonsProlong,
        );
    }
}
