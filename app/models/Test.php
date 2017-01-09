<?php

namespace app\models;

class Test extends \app\models\AppModel
{

    public $belongsTo = ['User'];

    /**
     * Метод помечает запись теста, как актвированную
     *
     * @param $testId
     * @return bool
     */
    public static function activate($testId)
    {
        if ($test = self::first($testId)) {
            $test->active = 1;
            $test->save();
        }
        return (bool) $test;
    }
}
