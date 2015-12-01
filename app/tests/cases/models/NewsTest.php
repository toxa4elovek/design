<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\News;
use app\models\Like;
use app\models\Event;

class NewsTest extends AppUnit {

    public function setUp() {
        $this->rollUp(array('News', 'Like', 'Event'));
    }

    public function tearDown() {
        $this->rollDown(array('News', 'Like', 'Event'));
    }

    /*public function testGetPost() {
        Rcache::delete('middle-post');
        $post = News::getPost();
        $this->assertEqual('Масло масляное: Ева Хан о своей картин', $post->title);

        // Отключаем пост
        $post->toggle = 1;
        $post->save();
        Rcache::delete('middle-post');
        $post2 = News::getPost();
        // Получаем наибольшее по просмотрам, т.к постов с av_views > views * 2 нету
        $this->assertEqual('Богатый внутренний мир', $post2->title);

        // Одна новость отключена, мы поставили в центр другую новость, с малым кол-вом просмотром
        $post5 = News::first(1);
        $post5->middle = 1;
        $post5->save();
        Rcache::delete('middle-post');
        $post5 = News::getPost();
        $this->assertEqual('Матрешкин труд', $post5->title);
        // возвращяем обратно, чтобы тесты проходились остальные
        $post5->middle = 0;
        $post5->save();
        Rcache::delete('middle-post');

        // Включаем пост и выносим принудительно на центр 
        $post2->toggle = 0;
        $post2->middle = 1;
        $post2->save();
        Rcache::delete('middle-post');
        $post3 = News::getPost();
        $this->assertEqual('Богатый внутренний мир', $post3->title);

        // Если toggle = 1, то пост считается выключенным и не будет показан, даже если middle = 1
        Rcache::delete('middle-post');
        $post3->toggle = 1;
        $post3->middle = 1;
        $post3->save();
        $post4 = News::getPost();
        $this->assertEqual('Топографический креатизм', $post4->title);

        $post->toggle = 0;
        $post->save();
        Rcache::delete('middle-post');
        $post5 = News::getPost();
        $this->assertEqual('Масло масляное: Ева Хан о своей картин', $post5->title);
        Rcache::delete('middle-post');
    }*/

    public function testdoesNewsExists() {
        $result = News::doesNewsExists('Fake');
        $this->assertFalse($result);

        $result = News::doesNewsExists('Матрешкин труд');
        $this->assertTrue($result);

        $result = News::doesNewsExists('Матрешкин труд', 'http://tutdesign.ru/cats/illustration/17254');
        $this->assertTrue($result);

        $result = News::doesNewsExists('Матрешкин труд', 'http://tutdesign.ru/cats/illustration/17253');
        $this->assertFalse($result);

        $result = News::doesNewsExists('Матрешкин труд 2', 'http://tutdesign.ru/cats/illustration/17254');
        $this->assertTrue($result);

        $result = News::doesNewsExists('Матрешкин труд 2', 'http://tutdesign.ru/cats/illustration/172542');
        $this->assertFalse($result);

        $result = News::doesNewsExists('Топографический креатизм', 'http://tutdesign.ru/cats/brand/17232-topograficheskij-kreatizm.html');
        $this->assertFalse($result);
    }

    public function testSaveNewsByAdmin()  {
        $result = News::doesNewsExists('Проверка', 'https://www.google.ru/');
        $this->assertFalse($result);

        $data = array(
            'title' => 'Проверка',
            'short' => '',
            'link' => 'https://www.google.ru/'
        );

        $result = News::saveNewsByAdmin($data, false);
        $this->assertTrue($result);
        $news = News::first(5);
        $this->assertEqual('Проверка', $news->title);
        $this->assertEqual(1, $news->admin);
        $this->assertEqual('https://www.google.ru/', $news->link);

        $result = News::doesNewsExists('Проверка', 'https://www.google.ru/');
        $this->assertTrue($result);

        $data = array(
            'title' => 'Проверка',
            'short' => '',
            'link' => 'https://www.google.ru/'
        );

        $result = News::saveNewsByAdmin($data, false);
        $this->assertFalse($result);

        $data = array(
            'title' => 'Проверка',
            'short' => '',
            'link' => 'https://www.google.com/'
        );

        $result = News::saveNewsByAdmin($data, false);
        $this->assertTrue($result);

        $data = array(
            'title' => '',
            'short' => 'Текст для проверки',
            'link' => ''
        );

        $result = News::saveNewsByAdmin($data, false);
        $this->assertTrue($result);

        $result = News::doesNewsExists('Проверка2');
        $this->assertFalse($result);

        $data = array(
            'short' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/3bhLkorXLI8" frameborder="0" allowfullscreen></iframe>',
        );

        $result = News::saveNewsByAdmin($data, false);
        $this->assertTrue($result);
        $news = News::first(8);
        $this->assertEqual('', $news->imageurl);
        $this->assertEqual('https://i.ytimg.com/vi/3bhLkorXLI8/maxresdefault.jpg', $news->og_image);
        $this->assertEqual('', $news->title);
        $this->assertEqual('GoDesigner.ru — Как это работает?', $news->og_title);
        $this->assertEqual('', $news->description);
        $this->assertEqual('Как это работает?', $news->og_description);

        $data = array(
            'short' => '<iframe src="https://player.vimeo.com/video/24302498" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> <p><a href="https://vimeo.com/24302498">29 WAYS TO STAY CREATIVE</a> from <a href="https://vimeo.com/tofudesign">TO-FU</a> on <a href="https://vimeo.com">Vimeo</a>.</p>',
        );

        $result = News::saveNewsByAdmin($data, false);
        $this->assertTrue($result);
        $news = News::first(9);
        $this->assertEqual('', $news->imageurl);
        $this->assertEqual('https://i.vimeocdn.com/video/164362346_1280x720.jpg', $news->og_image);
        $this->assertEqual('', $news->title);
        $this->assertEqual('29 WAYS TO STAY CREATIVE', $news->og_title);
        $this->assertEqual('', $news->description);
        $this->assertEqual('Motion Graphics: TO-FU Contact us at http://to-fu.tv Like us on Facebook http://www.facebook.com/TOFU.design twitter http://twitter.com/tofu_design  Reference: http://paulzii.tumblr.com/post/3360025995  Music:&hellip;', $news->og_description);

        $data = array('short' => '<div id="fb-root"></div><script>(function(d, s, id)');
        $result = News::saveNewsByAdmin($data, false);
        $this->assertTrue($result);
    }

    public function testIsCoubNews(){
        $news = News::first(1);
        $this->assertFalse($news->isCoub());
        $news->short = '<iframe src="//coub.com/embed/g9jnfu?muted=false&autostart=false&originalSize=false&hideTopBar=false&startWithHD=false" allowfullscreen="true" frameborder="0" width="640" height="360"></iframe>';
        $this->assertTrue($news->isCoub());
    }

    public function testIncreaseLike() {
        $result = News::increaseLike(9999);
        $expected = array('result' => false);
        $this->assertEqual($expected, $result);

        $result = News::increaseLike(4);
        $expected = array('result' => false, 'likes' => 10);
        $this->assertEqual($expected, $result);

        $result = News::increaseLike(4, 2);
        $expected = array('result' => true, 'likes' => 11);
        $this->assertEqual($expected, $result);

        $like = Like::first(array('conditions' => array('news_id' => 4, 'user_id' => 2)));
        $this->assertEqual('object', gettype($like));
        $this->assertEqual('lithium\data\entity\Record', get_class($like));

        $event = Event::first(array('conditions' => array('type' => 'LikeAdded', 'news_id' => 4, 'user_id' => 2)));
        $this->assertEqual('object', gettype($event));
        $this->assertEqual('lithium\data\entity\Record', get_class($event));

        $result = News::increaseLike(4, 2);
        $expected = array('result' => false, 'likes' => 11);
        $this->assertEqual($expected, $result);
    }

    public function testDecreaseLike() {
        $result = News::decreaseLike(9999);
        $expected = array('result' => false);
        $this->assertEqual($expected, $result);

        $result = News::decreaseLike(4);
        $expected = array('result' => false, 'likes' => 10);
        $this->assertEqual($expected, $result);

        News::increaseLike(4, 2);

        $result = News::decreaseLike(4, 2);
        $expected = array('result' => true, 'likes' => 10);
        $this->assertEqual($expected, $result);

        $like = Like::first(array('conditions' => array('news_id' => 4, 'user_id' => 2)));
        $event = Event::first(array('conditions' => array('type' => 'LikeAdded', 'news_id' => 4, 'user_id' => 2)));

        $this->assertNull($like);
        $this->assertNull($event);
    }

}