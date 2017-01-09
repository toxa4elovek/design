<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Brief;
use app\extensions\tests\AppUnit;
use app\models\Pitch;

class BriefTest extends AppUnit
{

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
    public function setUp()
    {
        $this->brief = new Brief();
        $this->rollUp('Pitch');
    }

    /**
     * Ну а этот метод вызывает в конце каждого теста, тут можно почистить базу, сбросить состояния и тп.
     */
    public function tearDown()
    {
        $this->rollUp('Pitch');
    }

    public function testRemoveEmailClean()
    {
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


    public function testStripEmail()
    {
        $this->assertEqual('', $this->brief->stripEmail(null));
        $this->assertEqual('', $this->brief->stripEmail(false));
        $this->assertEqual('asdfg12345', $this->brief->stripEmail('asdfg12345'));
        $this->assertEqual('<a target="_blank" href="http://godesigner.ru/answers/view/47">[Адрес скрыт]</a>', $this->brief->stripEmail('test@test.ru'));
        $this->assertEqual('Проверка <a target="_blank" href="http://godesigner.ru/answers/view/47">[Адрес скрыт]</a> вторая часть <a target="_blank" href="http://godesigner.ru/answers/view/47">[Адрес скрыт]</a>', $this->brief->stripEmail('Проверка test@mail.com вторая часть test@mail.com'));
        $this->assertEqual('test.ru/login/user', $this->brief->stripEmail('test.ru/login/user'));
    }

    public function testStripUrl()
    {
        $this->assertEqual('', $this->brief->stripUrl(null));
        $this->assertEqual('', $this->brief->stripUrl(false));
        $this->assertEqual('test.ru/login/user', $this->brief->stripUrl('test.ru/login/user'));
        $this->assertEqual('qwerty65432', $this->brief->stripUrl('qwerty65432'));
        $this->assertEqual('test@test.ru', $this->brief->stripUrl('test@test.ru'));
        $this->assertEqual('', $this->brief->stripUrl('http://test/'));
        $this->assertEqual('Начало строки  конец строки ', $this->brief->stripUrl('Начало строки http://test.ru конец строки http://test.ru/main'));
    }

    public function testLinkEmail()
    {
        $this->assertEqual('', $this->brief->linkEmail(null));
        $this->assertEqual('', $this->brief->linkEmail(false));
        $this->assertEqual('test@@test.ru', $this->brief->linkEmail('test@@test.ru'));
        $this->assertEqual('test@test', $this->brief->linkEmail('test@test'));
        $this->assertEqual('<a href="mailto://test@test.ru">test@test.ru</a>', $this->brief->linkEmail('test@test.ru'));
        $this->assertEqual('Начало строки <a href="mailto://test@test.ru">test@test.ru</a> конец строки <a href="mailto://tester@tester.ru">tester@tester.ru</a>', $this->brief->linkEmail('Начало строки test@test.ru конец строки tester@tester.ru'));
        $this->assertEqual('http://test.ru/main', $this->brief->linkEmail('http://test.ru/main'));
    }

    public function testIsUsingPlainText()
    {
        $project = Pitch::first(1);
        $this->assertFalse($this->brief->isUsingPlainText($project));
        $project->started = '2012-01-01 00:00:00';
        $project->save();
        $this->assertTrue($this->brief->isUsingPlainText($project));
    }

    public function testTrimAll()
    {
        $text = "This is \r\n Test";
        $expected = "This is Test";
        $result = $this->brief->trimAllInvisibleCharacter($text);
        $this->assertEqual($expected, $result);
    }

    public function testBriefDetails()
    {
        $project = Pitch::first(1);
        $project->description = '<p>Описание проекта на сайте</p> htt://godesigner.ru/ и продолжение <a href="http://www.google.com">http://www.google.com</a> текста';

        $project->started = '2012-01-01 00:00:00';
        $project->save();
        $result = $this->brief->briefDetails($project);
        $expected = 'Описание проекта на сайте <a href="http://htt://godesigner.ru/" target="_blank">htt://godesigner.ru/</a> и продолжение <a href="http://www.google.com" target="_blank">http://www.google.com</a> текста';
        $this->assertEqual($expected, $result);

        $project->started = '2015-01-01 00:00:00';
        $project->save();
        $result = $this->brief->briefDetails($project);
        $expected = '<p>Описание проекта на сайте</p> <a href="http://htt://godesigner.ru/" target="_blank">htt://godesigner.ru/</a> и продолжение <a href="http://www.google.com">http://www.google.com</a> текста';
        $this->assertEqual($expected, $result);
    }

    public function testInsertHtmlLinkInText()
    {
        $text = '<p>Test<br/> text http://www.google.com</p>';
        $expected = '<p>Test<br/> text <a href="http://www.google.com" target="_blank">http://www.google.com</a></p>';
        $result = $this->brief->insertHtmlLinkInText($text);
        $this->assertEqual($expected, $result);
    }

    public function testDeleteHtmlTagsAndInsertHtmlLinkInText()
    {
        $text = '<p>Test<br/><br> text http://www.google.com</p>';
        $expected = 'Test<br/><br> text <a href="http://www.google.com" target="_blank">http://www.google.com</a>';
        $result = $this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($text);
        $this->assertEqual($expected, $result);
    }

    public function testDeleteHtmlTagsAndInsertHtmlLinkInTextAndMentions()
    {
        $text = "<p>@Дмитрий Н., Test<br/><br> text http://www.google.com</p>";
        $expected = '<a href="#" class="mention-link" data-comment-to="Дмитрий Н.">@Дмитрий Н.,</a> Test text <a href="http://www.google.com" target="_blank">http://www.google.com</a>';
        $result = $this->brief->deleteHtmlTagsAndInsertHtmlLinkInTextAndMentions($text);
        $this->assertEqual($expected, $result);
    }
}
