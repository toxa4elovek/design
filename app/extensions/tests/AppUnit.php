<?php

namespace app\extensions\tests;

use lithium\util\Inflector;
use li3_fixtures\test\Fixture;
use lithium\data\Connections;

class AppUnit extends \lithium\test\Unit
{
    
    protected static $testModel = null;
    protected static $testDbName = '_test';

    protected function rollUp($tables = [])
    {
        if (!is_array($tables)) {
            $tables = [$tables];
        }
        foreach ($tables as $table) {
            $model = '\app\models\\' . $table;
            $model::config(['connection' => 'test']);
            $connectionConfig = Connections::get('test', ['config' => true]);
            mysql_query('TRUNCATE TABLE `' . $connectionConfig['database'] . '`.`' . Inflector::underscore(Inflector::pluralize($table)) . '`') or die(mysql_error());
            $fixtures = Fixture::load($table);
            if (count($fixtures) > 0) {
                $data = $fixtures->to('array');
                foreach ($data as $fixtureItem) {
                    $item = $model::create();
                    $item->set($fixtureItem);
                    $item->save(null, ['validate' => null]);
                }
                /*
                    $formatFunc = function($key) { return '`' . $key . '`';};
                    $keys = array_map($formatFunc, array_keys($model::schema()));

                    $insertQuery = 'INSERT INTO `' . $connectionConfig['database'] . '`.`'. Inflector::underscore(Inflector::pluralize($table)) . '` (' . implode(', ', $keys) . ') VALUES ';
                    $values = array();
                    foreach($fixtures as $item){
                        $item = array_map(function($item) {
                            return "'" . mysql_real_escape_string($item) . "'";
                        }, $item);
                        $values[] = '(' . implode(', ', $item) . ')';
                    }
                    $insertQuery = $insertQuery . implode(', ', $values) . ';';
                    mysql_query($insertQuery) or die(mysql_error());
                */
            }
        }
    }

    protected function rollDown($tables = [])
    {
        if (!is_array($tables)) {
            $tables = [$tables];
        }
        foreach ($tables as $table) {
            $model = '\app\models\\' . $table;
            $model::config(['connection' => 'test']);
            $connectionConfig = Connections::get('test', ['config' => true]);
            mysql_query('TRUNCATE TABLE `' . $connectionConfig['database'] . '`.`' . Inflector::underscore(Inflector::pluralize($table)) . '`') or die(mysql_error());
        }
    }
}
