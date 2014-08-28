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

    public function testRemoveEmailClean() {
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
    }


	public function testStripEmail(){
		$this->assertEqual('', $this->brief->stripemail(null));
		$this->assertEqual('', $this->brief->stripemail(false));
		$this->assertEqual('asdfg12345', $this->brief->stripemail('asdfg12345'));
		$this->assertEqual('<a target="_blank" href="http://www.godesigner.ru/answers/view/47">[Адрес скрыт]</a>', $this->brief->stripemail('test@test.ru'));
		$this->assertEqual('Проверка <a target="_blank" href="http://www.godesigner.ru/answers/view/47">[Адрес скрыт]</a> вторая часть <a target="_blank" href="http://www.godesigner.ru/answers/view/47">[Адрес скрыт]</a>', $this->brief->stripemail('Проверка test@mail.com вторая часть test@mail.com'));
		$this->assertEqual('test.ru/login/user', $this->brief->stripemail('test.ru/login/user'));
	}

	public function testStripUrl() {
		$this->assertEqual('', $this->brief->stripurl(null));
		$this->assertEqual('', $this->brief->stripurl(false));
		$this->assertEqual('test.ru/login/user', $this->brief->stripurl('test.ru/login/user'));
		$this->assertEqual('qwerty65432', $this->brief->stripurl('qwerty65432'));
		$this->assertEqual('test@test.ru', $this->brief->stripurl('test@test.ru'));
		$this->assertEqual('', $this->brief->stripurl('http://test/'));
		$this->assertEqual('Начало строки  конец строки ', $this->brief->stripurl('Начало строки http://test.ru конец строки http://test.ru/main'));
	}

	public function testLinkEmail() {
		$this->assertEqual('', $this->brief->linkemail(null));
		$this->assertEqual('', $this->brief->linkemail(false));
		$this->assertEqual('test@@test.ru', $this->brief->linkemail('test@@test.ru'));
		$this->assertEqual('test@test', $this->brief->linkemail('test@test'));
		$this->assertEqual('<a href="mailto://test@test.ru">test@test.ru</a>', $this->brief->linkemail('test@test.ru'));
		$this->assertEqual('Начало строки <a href="mailto://test@test.ru">test@test.ru</a> конец строки <a href="mailto://tester@tester.ru">tester@tester.ru</a>', $this->brief->linkemail('Начало строки test@test.ru конец строки tester@tester.ru'));
		$this->assertEqual('http://test.ru/main', $this->brief->linkemail('http://test.ru/main'));
	}
 
}