<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Brief;

class BriefTest extends \lithium\test\Unit {

	public function teststripemail(){
		$this->assertNull(null);
		$this->assertFalse('12345sdfqwe12');
        $this->assertFalse('test@@test.ru');
		$this->assertFalse('test.ru/login/user');
		$this->assertFalse('test@test.rutest@test.ru');
        $this->assertTrue('test@test.ru');
	}
	public function testremoveEmailClean() {
		$this->assertFalse(null);
		$this->assertFalse('asdfg12345');
		$this->assertFalse('test@@test.ru');
		$this->assertFalse('test@test.rutest@test.ru');
		$this->assertFalse('test.ru/login/user');
		$this->assertTrue('test@test.ru');
	}
	public function teststripurl() {
		$this->assertFalse(null);
		$this->assertFalse('test/login/user');
		$this->assertFalse('qwerty65432');
		$this->assertFalse('test@@test.ru');
		$this->assertFalse('http://test/');
		$this->assertTrue('http://test.ru/main');
	}
	public function testlinkemail() {
		$this->assertFalse(null);
		$this->assertFalse('test/login/user');
		$this->assertFalse('lkjhg64431');
		$this->assertFalse('test@@test.ru');
		$this->assertFalse('test@test.rutest@test.ru');
		$this->assertTrue('test@test.ru');
	}
}