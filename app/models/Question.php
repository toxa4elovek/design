<?php

namespace app\models;

use \app\models\User;
use \app\models\Test;

class Question extends \app\models\AppModel {

    public $hasMany = array('Variant');

    public static function getStats() {
        $usersTotal = User::count();
        $usersTested = Test::count();
        $usersNeud = Test::count(array(
            'conditions' => array(
                'percent' => array(
                    '<' => 70,
                ),
            ),
        ));
        $usersUd = Test::count(array(
            'conditions' => array(
                'percent' => array(
                    '>=' => 70,
                    '<' => 80,
                ),
            ),
        ));
        $usersGood = Test::count(array(
            'conditions' => array(
                'percent' => array(
                    '>=' => 80,
                    '<' => 90,
                ),
            ),
        ));
        $usersExc = Test::count(array(
            'conditions' => array(
                'percent' => array(
                    '>' => 90,
                ),
            ),
        ));
        $stats = array(
            '0' => array(
                'text' => 'Количество  тестируемых',
                'percent' => round($usersTested / $usersTotal * 100),
                'value' => $usersTested,
            ),
            '1' => array(
                'text' => 'Неудовлетворительно',
                'percent' => round($usersNeud / $usersTested * 100),
                'value' => $usersNeud,
            ),
            '2' => array(
                'text' => 'Удовлетворительно',
                'percent' => round($usersUd / $usersTested * 100),
                'value' => $usersUd,
            ),
            '3' => array(
                'text' => 'Хорошо',
                'percent' => round($usersGood / $usersTested * 100),
                'value' => $usersGood,
            ),
            '4' => array(
                'text' => 'Отлично',
                'percent' => round($usersExc / $usersTested * 100),
                'value' => $usersExc,
            ),
        );

        return $stats;
    }

}
