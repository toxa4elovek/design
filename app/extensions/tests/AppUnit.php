<?php

namespace app\extensions\tests;

use lithium\util\Inflector;
use li3_fixtures\test\Fixture;
use lithium\data\Connections;

class AppUnit extends \lithium\test\Unit {
	
	protected static $testModel = null;
	protected static $testDbName = '_test';

	protected function rollUp($tables = array()) {
		if(!is_array($tables)) {
			$tables = array($tables);
		}
		foreach($tables as $table) {
			$model = '\app\models\\' . $table;
			$model::config(array('connection' => 'test'));
			$connectionConfig = Connections::get('test', array('config' => true));
			$fixtures = Fixture::load($table);
			mysql_query('TRUNCATE TABLE `' . $connectionConfig['database'] . '`.`' . Inflector::underscore(Inflector::pluralize($table)) . '`') or die(mysql_error());
			if(count($fixtures) > 0) {
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
			}
		}
	}
	
	/*protected static function getMethod($class, $method) {
		$class = new \ReflectionClass($class);
	    $method = $class->getMethod($method);
	    $class1 = new \ReflectionClass($method);
	    $method->setAccessible(true);
	    return $method;
	}*/
	/*
	public function testFoo() {
	  $foo = self::getMethod('foo');
	  $obj = new MyClass();
	  $foo->invokeArgs($obj, array(...));
	  ...
	}
	*/
}