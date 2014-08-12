<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Brief;

class BriefTest extends \lithium\test\Unit {

    /**
     * Это переменная будет хранить объект хелпера, чтобы можно было в тесте
     * писать $this->brief и вызывать методы
     *
     * @var object
     */
    public $brief = null;

    /**
     * В тестах перед каждым запуском теста вызывает этот метод
     * Это может быть полезно, например если надо подготовить данные в базе
     * В данном слычае мы просто создаем наш хелпер для последующего образения внутри теста
     */
    public function setUp() {
        $this->brief = new Brief();
    }

    /**
     * Ну а этот метод вызывает в конце каждого теста, тут можно почистить базу, сбросить состояния и тп.
     */
    public function tearDown() {
        // ничего пока не делаем тут
    }

    public function testremoveEmailClean() {
        // Метод должен конвертировать null в пустую строку
        $this->assertEqual('', $this->brief->removeEmailClean(null));
        // Метод должен конвертировать false в пустую строку
        $this->assertEqual('', $this->brief->removeEmailClean(false));
        // Метод ничего не делает, если в строке нет email
        $this->assertEqual('asdfg12345', $this->brief->removeEmailClean('asdfg12345'));
        // Метод должен вырезать целиков всё содержимое строки
        $this->assertEqual('', $this->brief->removeEmailClean('test@test.ru'));
        // Метод должен вырезать оба имейла из следующего текста
        $this->assertEqual('Проверка  вторая часть ', $this->brief->removeEmailClean('Проверка test@mail.com вторая часть test@mail.com'));
        // Метод не должен реагировать на простые строчки
        $this->assertEqual('test.ru/login/user', $this->brief->removeEmailClean('test.ru/login/user'));

        // А этот тест провалется, просто чтобы показать, как это происходит
        $this->assertEqual('', $this->brief->removeEmailClean('test@test.rutest@test.ru'), 'В качестве последнего параметра ассретам можно даавать строчку с пояснинем, что сломалось.');
    }

/**
	public function teststripemail(){
		$this->assertNull(null);
		$this->assertFalse('12345sdfqwe12');
        $this->assertFalse('test@@test.ru');
		$this->assertFalse('test.ru/login/user');
		$this->assertFalse('test@test.rutest@test.ru');
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
 */
}