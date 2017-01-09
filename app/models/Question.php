<?php

namespace app\models;

use \app\models\User;
use \app\models\Test;

class Question extends \app\models\AppModel
{

    public $hasMany = ['Variant'];

    public static $questionsLimit = 15;

    public static function getStats()
    {
        $usersTotal = User::count();
        $usersTested = Test::count([
            'conditions' => [
                'first_time' => 1,
            ],
        ]);
        $usersNeud = Test::count([
            'conditions' => [
                'first_time' => 1,
                'percent' => [
                    '<' => 70,
                ],
            ],
        ]);
        $usersUd = Test::count([
            'conditions' => [
                'first_time' => 1,
                'percent' => [
                    '>=' => 70,
                    '<' => 80,
                ],
            ],
        ]);
        $usersGood = Test::count([
            'conditions' => [
                'first_time' => 1,
                'percent' => [
                    '>=' => 80,
                    '<' => 90,
                ],
            ],
        ]);
        $usersExc = Test::count([
            'conditions' => [
                'first_time' => 1,
                'percent' => [
                    '>' => 90,
                ],
            ],
        ]);
        $totalPercent = ceil($usersTested / $usersTotal * 100);
        if ($totalPercent < 3) {
            $totalPercent = 3;
        }
        $stats = [
            '0' => [
                'text' => 'Количество  тестируемых',
                'percent' => $totalPercent,
                'value' => $usersTested,
            ],
            '1' => [
                'text' => 'Неудовлетворительно',
                'percent' => round($usersNeud / $usersTested * 100),
                'value' => $usersNeud,
            ],
            '2' => [
                'text' => 'Удовлетворительно',
                'percent' => round($usersUd / $usersTested * 100),
                'value' => $usersUd,
            ],
            '3' => [
                'text' => 'Хорошо',
                'percent' => round($usersGood / $usersTested * 100),
                'value' => $usersGood,
            ],
            '4' => [
                'text' => 'Отлично',
                'percent' => round($usersExc / $usersTested * 100),
                'value' => $usersExc,
            ],
        ];

        return $stats;
    }
}
