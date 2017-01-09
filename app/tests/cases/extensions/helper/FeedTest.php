<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Feed;

class FeedTest extends \lithium\test\Unit
{

    public $feed = null;

    public function setUp()
    {
        $this->feed = new Feed();
    }

    public function testIsEmbeddedLink()
    {
        $this->assertFalse($this->feed->isEmbeddedLink('http://www.google.com/test'));
        $this->assertTrue($this->feed->isEmbeddedLink('https://www.youtube.com/watch?v=NifyOHkaztY'));
        $this->assertTrue($this->feed->isEmbeddedLink('https://vimeo.com/129346968'));
    }

    /** методы стали приватными
    public function testIsYoutubeLink() {
        $this->assertFalse($this->feed->isYoutubeLink('http://www.google.com/test'));
        $this->assertTrue($this->feed->isYoutubeLink('https://www.youtube.com/watch?v=NifyOHkaztY'));
        $this->assertFalse($this->feed->isYoutubeLink('https://vimeo.com/129346968'));
    }

    public function testIsVimeoLink() {
        $this->assertFalse($this->feed->isVimeoLink('http://www.google.com/test'));
        $this->assertFalse($this->feed->isVimeoLink('https://www.youtube.com/watch?v=NifyOHkaztY'));
        $this->assertTrue($this->feed->isVimeoLink('https://vimeo.com/129346968'));
    }**/

    public function testGenerateEmbeddedIframe()
    {
        $expected = '<iframe width="600" height="337" src="https://www.youtube.com/embed/NifyOHkaztY" frameborder="0" allowfullscreen></iframe>';
        $this->assertIdentical($expected, $this->feed->generateEmbeddedIframe('https://www.youtube.com/watch?v=NifyOHkaztY'));
        $expected = '<iframe src="https://player.vimeo.com/video/129346968" width="600" height="337" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        $this->assertIdentical($expected, $this->feed->generateEmbeddedIframe('https://vimeo.com/129346968'));
        $expected = '<iframe width="600" height="337" src="https://www.youtube.com/embed/NifyOHkaztb" frameborder="0" allowfullscreen></iframe>';
        $this->assertIdentical($expected, $this->feed->generateEmbeddedIframe('https://www.youtube.com/watch?v=NifyOHkaztb'));
        $expected = '<iframe src="https://player.vimeo.com/video/129346967" width="600" height="337" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        $this->assertIdentical($expected, $this->feed->generateEmbeddedIframe('https://vimeo.com/129346967'));
        $expected = '<iframe src="//coub.com/embed/g9jnfu?muted=false&autostart=false&originalSize=false&hideTopBar=false&startWithHD=false" allowfullscreen="true" frameborder="0" width="600" height="337"></iframe>';
        $this->assertIdentical($expected, $this->feed->generateEmbeddedIframe('<iframe src="//coub.com/embed/g9jnfu?muted=false&autostart=false&originalSize=false&hideTopBar=false&startWithHD=false" allowfullscreen="true" frameborder="0" width="640" height="360"></iframe>'));
    }
}
