<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Og;

class OgTest extends \lithium\test\Unit
{

    /**
     * Это переменная будет хранить объект хелпера, чтобы можно было в тесте
     * писать $this->$og и вызывать методы
     *
     * @var object
     */
    public $og = null;

    /**
     * В тестах перед каждым запуском теста вызывает этот метод
     * Это может быть полезно, например если надо подготовить данные в базе
     * В данном слычае мы просто создаем наш хелпер для последующего образения внутри теста
     */
    public function setUp()
    {
        $this->og = new Og();
    }

    /**
    * Ну а этот метод вызывает в конце каждого теста, тут можно почистить базу, сбросить состояния и тп.
    */
    public function tearDown()
    {
        // ничего пока не делаем тут
    }

    public function testGetOgImage()
    {
        $expected = '<meta property="og:image" content="https://godesigner.ru/img/fb_icon.jpg"/>';
        $result = $this->og->getOgImage('');
        $this->assertEqual($expected, $result);

        $expected = '<meta property="og:image" content="http://godesigner.ru/events/image.jpg"/>';
        $result = $this->og->getOgImage('http://godesigner.ru/events/image.jpg');
        $this->assertEqual($expected, $result);

        $expected = '<meta property="og:image" content="https://godesigner.ru/events/image.jpg"/>';
        $result = $this->og->getOgImage('/events/image.jpg');
        $this->assertEqual($expected, $result);

        $expected = '<meta property="og:image" content="https://godesigner.ru/blog/emojii/shutterstock_364647341_Converte-Copy1.png"/>';
        $result = $this->og->getOgImage('https://godesigner.ru/blog/emojii/shutterstock_364647341_Converte-Copy1.png');
        $this->assertEqual($expected, $result);
    }

    public function testGetOgTitle()
    {
        $expected = '<meta property="og:title" content="Логотип, сайт и дизайн: выбирай из идей, а не портфолио"/>';
        $result = $this->og->getOgTitle('');
        $this->assertEqual($expected, $result);

        $expected = '<meta property="og:title" content="' . htmlspecialchars('Тест "Какой ты дизайнер на самом деле" показал, что я дворник, совсем не дизайнер!') . '"/>';
        $result = $this->og->getOgTitle('Тест "Какой ты дизайнер на самом деле" показал, что я дворник, совсем не дизайнер!');
        $this->assertEqual($expected, $result);
    }

    public function testGetOgDescription()
    {
        $expected = '<meta property="og:description" content="Логотип, сайт и дизайн от всего креативного интернет сообщества"/>';
        $result = $this->og->getOgDescription('');
        $this->assertEqual($expected, $result);

        $string = 'Тест "Какой ты дизайнер на самом деле" показал, что я дворник, совсем не дизайнер!';
        $cleanString = str_replace('"', '\'', str_replace("\n\r", '', str_replace('&nbsp;', ' ', strip_tags(mb_substr($string, 0, 100, 'UTF-8') . '...'))));
        $expected = '<meta property="og:description" content="' . $cleanString . '"/>';
        $result = $this->og->getOgDescription($string);
        $this->assertEqual($expected, $result);
    }

    public function testGetOgUrl()
    {
        $expected = '<meta property="og:url" content="http://godesigner.ru' . $_SERVER['REQUEST_URI'] . '"/>';
        $result = $this->og->getOgUrl();
        $this->assertEqual($expected, $result);
    }
}
