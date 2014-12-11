<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\News;
use app\extensions\storage\Rcache;

class NewsTest extends AppUnit {

    public function setUp() {
        $this->rollUp('News');
    }

    public function tearDown() {
        $this->rollDown('News');
    }

    public function testGetPost() {
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
    }

}
