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
class Addon extends AppModel
{

    public $belongsTo = ['Pitch'];

    public static function __init()
    {
        parent::__init();
        self::applyFilter('activate', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if ($result) {
                if ($params['sendEmail']) {
                    User::sendAdminNewAddon($params['addon']);
                }
                if ($params['addon']->brief == 1) {
                    Pitch::addBrief($params['addon']);
                    if ($params['sendEmail']) {
                        User::sendAdminNewAddonBrief($params['addon']);
                    }
                }
                if ($params['addon']->experts == 1) {
                    Pitch::addExpert($params['addon']);
                    if ($params['sendEmail']) {
                        User::sendExpertMail($params['addon']);
                    }
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

    public static function activate($addon, $sendEmail = true)
    {
        error_reporting(E_ALL);
        ini_set('log_errors', '0');
        ini_set('display_errors', '1');
        $params = compact('addon', 'sendEmail');
        return static::_filter(__FUNCTION__, $params, function ($self, $params) {
            extract($params);
            $addon->billed = 1;
            return $addon->save();
        });
    }

    public static function graphData($date)
    {
        $range = "'$date'";
        $addons = self::all([
            'conditions' => [
                'YEAR(created) = YEAR(' . $range . ') AND MONTH(created) = MONTH( ' . $range . ')',
                'Addon.billed' => 1,
            ], 'with' => ['Pitch']
        ]);
        $countArray = [];
        $addonsCount = 0;
        $addonsTotal = 0;
        $addonsProlong = 0;
        $addonsCountSub = 0;
        $addonsTotalSub = 0;
        $addonsCohortTotal = 0;
        $isCohort = false;
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
            if ($addon->pitch->category_id == 20) {
                $addonsCountSub++;
                $addonsTotalSub += $addon->total;
            }
            $project = Pitch::first($addon->pitch_id);
            $userId = $project->user_id;
            $isCohort = Pitch::isCohortClientForMonth($userId, $date);
            if($isCohort) {
                $addonsCohortTotal += $addon->total;
            }
        }
        $values = [];
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

        return [
            'values' => $values,
            'highestValue' => $highestValue,
            'addonsCount' => $addonsCount,
            'addonsTotal' => $addonsTotal,
            'addonsCohortTotal' => $addonsCohortTotal,
            'addonsCountSub' => $addonsCountSub,
            'addonsTotalSub' => $addonsTotalSub,
            'addonsProlong' => $addonsProlong,
        ];
    }
}
