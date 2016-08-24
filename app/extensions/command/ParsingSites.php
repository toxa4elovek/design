<?php

namespace app\extensions\command;

use \app\models\Post;
use \app\models\Wp_post;
use \app\models\News;
use \app\models\Event;

class ParsingSites extends \app\extensions\command\CronJob
{

    public static $debug = false;

    public function run()
    {
        // На случай долгих таймаутов
        set_time_limit(120);
        $startTimeStamp = time();
        self::$debug = false;

        $this->header('Welcome to the ParsingSites command!');

        $this->out("Starting parsing wtpack.ru");
        self::ParsingWTPack('http://wtpack.ru/feed', '/< *img[^>]*src *= *["\']?([^"\']*)/i', true, 'ru');
        $this->out('Finished parsing wtpack.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing lookatme.ru");
        self::ParsingLookatme();
        $this->out('Finished parsing lookatme.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing krutayatema.ru");
        self::ParsingWordpress('http://krutayatema.ru/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i', true, 'ru');
        $this->out('Finished parsing krutayatema.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing http://designlenta.com/industrial");
        self::ParsingRss('http://feeds.feedburner.com/designlenta?format=rss');
        $this->out('Finished parsing http://designlenta.com/industrial [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing artchronika.ru");
        self::ParsingWordpress('http://artchronika.ru/feed', '/< *img[^>]*src *= *["\']?([^"\']*)/i', true, 'ru');
        $this->out('Finished parsing artchronika.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing http://www.kokokokids.ru/");
        self::ParsingKoko('http://www.kokokokids.ru/feeds/posts/default?alt=rss');
        $this->out('Finished parsing http://www.kokokokids.ru/ [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing http://www.theartnewspaper.ru/");
        self::ParsingSimpleRss('http://www.theartnewspaper.ru/rss/');
        $this->out('Finished parsing http://www.theartnewspaper.ru/ [' . (time() - $startTimeStamp) . ' sec]');

        //$this->out("Starting parsing interviewrussia.ru");
        //self::ParsingInterview('http://www.interviewrussia.ru/export/rss.xml', '/< *img[^>]*src *= *["\']?([^"\']*)/i', true, 'ru');
        //$this->out('Finished parsing interviewrussia.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing desnews.ru");
        self::ParsingDesnewsru('http://desnews.ru/?feed=rss2', '/< *img[^>]*src *= *["\']?([^"\']*)/i', true, 'ru');
        $this->out('Finished parsing desnews.ru [' . (time() - $startTimeStamp) . ' sec]');

        //$this->out("Starting parsing buro247.ru");
        //self::ParsingWordpress('http://www.buro247.ru/xml/rss.xml', '/< *img[^>]*src *= *["\']?([^"\']*)/i', true, 'ru');
        //$this->out('Finished parsing buro247.ru [' . (time() - $startTimeStamp) . ' sec]');


        #$this->out("Starting parsing russiangap.com");
        #self::ParsingWordpress('http://www.russiangap.com/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i', true, 'ru');
        #$this->out('Finished parsing russiangap.com [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing pixelgene.ru");
        self::ParsingWordpress('http://pixelgene.ru/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i', true, 'ru');
        $this->out('Finished parsing pixelgene.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing godesigner.ru");
        self::ParsingGodesigner();
        $this->out('Finished parsing godesigner.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing tutdesign.ru");
        self::ParsingTutdesign();
        $this->out('Finished parsing tutdesign.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing vice.com/ru");
        self::ParsingVice();
        $this->out('Finished parsing vice.com/ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing royalcheese.ru");
        self::ParsingRoyalcheese();
        $this->out('Finished parsing royalcheese.ru [' . (time() - $startTimeStamp) . ' sec]');

        /*
        //$this->out("Starting parsing vozduh.afisha.ru");
        //self::ParsingVozduhAfisha();
        //$this->out('Finished parsing vozduh.afisha.ru [' . (time() - $startTimeStamp) . ' sec]');
        //$this->out("Starting parsing colta.ru");
        //self::ParsingColta();
        //$this->out('Finished parsing colta.ru [' . (time() - $startTimeStamp) . ' sec]');
        //$this->out("Starting parsing newgrids.fr");
        //self::ParsingWordpress('http://newgrids.fr/feed', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        //$this->out('Finished parsing newgrids.fr [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing lovelypackage.com");
        self::ParsingWordpress('http://lovelypackage.com/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing lovelypackage.com [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing bpando.org");
        self::ParsingWordpress('http://bpando.org/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing bpando.org [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing love-aesthetics.nl");
        self::ParsingWordpress('http://love-aesthetics.nl/category/diy/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing love-aesthetics.nl [' . (time() - $startTimeStamp) . ' sec]');

        //$this->out("Starting parsing the-village.ru");
        //self::ParsingVillage();
        //$this->out('Finished parsing the-village.ru [' . (time() - $startTimeStamp) . ' sec]');

        //$this->out("Starting parsing packaginguqam.blogspot.ru");
        //self::ParsingPackaginguqam();
        //$this->out('Finished parsing packaginguqam.blogspot.ru [' . (time() - $startTimeStamp) . ' sec]');

        //$this->out("Starting parsing fuckingyoung.es");
        //self::ParsingWordpress('http://fuckingyoung.es/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        //$this->out('Finished parsing fuckingyoung.es [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing raneytown.com");
        self::ParsingWordpress('http://raneytown.com/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing raneytown.com [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing designmadeingermany.de");
        self::ParsingWordpress('http://www.designmadeingermany.de/2013/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing designmadeingermany.de [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing typetoken.net");
        self::ParsingWordpress('http://www.typetoken.net/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing typetoken.net [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing typeforyou.org");
        self::ParsingWordpress('http://www.typeforyou.org/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing typeforyou.org [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing abduzeedo.com");
        self::ParsingAbduzeedo();
        $this->out('Finished parsing abduzeedo.com [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing underconsideration.com/fpo/");
        self::ParsingUnderconsideration();
        $this->out('Finished parsing underconsideration.com/fpo/ [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing hel-looks.com");
        self::hel_looks();
        $this->out('Finished parsing hel-looks.com [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing monsterchildren");
        self::ParsingWordpress('http://www.monsterchildren.com/feed/');

        $this->out('Finished parsing monsterchildren [' . (time() - $startTimeStamp) . ' sec]');

        self::ParsingWordpress('http://illusion.scene360.com/feed/');

        $this->out("Starting fixing tags");
        self::fixTags();
        $this->out('Finished fixing tags [' . (time() - $startTimeStamp) . ' sec]');
        */
    }

    private function ParsingGodesigner()
    {
        $posts = Post::all(array('conditions' => array('published' => 1, 'created' => array('<=' => date('Y-m-d H:i:s'))), 'limit' => 300, 'order' => array('created' => 'desc')));
        foreach ($posts as $post) {
            $trigger = News::doesNewsExists((string) $post->title, 'http://www.godesigner.ru/posts/view/' . $post->id);

            if (!$trigger) {
                $this->out('Saving - ' . $post->title . ' (' . $post->id . ')');
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $post->full, $matches);
                $image = '';
                if (isset($matches[1])) {
                    $image = $matches[1];
                }
                $news = News::create(array(
                            'title' => $post->title,
                            'short' => strip_tags($post->short),
                            'tags' => substr($post->tags, 0, strpos($post->tags, '|')),
                            'created' => date('Y-m-d H:i:s', (time() - (HOUR))),
                            'link' => 'http://www.godesigner.ru/posts/view/' . $post->id,
                            'imageurl' => $image,
                            'lang' => 'ru'
                ));
                $news->save();
                Event::createEventNewsAdded($news->id, 0, date('Y-m-d H:i:s', (time())));
            }
        }
    }

    private function ParsingTutdesign()
    {
        $posts = Wp_post::all(array(
                    'conditions' => array(
                        'post_status' => 'publish',
                        'post_type' => 'post',
                        'post_date' => array(
                            '<=' => date('Y-m-d H:i:s'),
                        ),
                        'Wp_postmeta.meta_key' => '_thumbnail_id',
                    ),
                    'with' => array('Wp_term_relationship', 'Wp_postmeta'),
                    'order' => array('post_date' => 'desc'),
                    'limit' => 100
        ));

        foreach ($posts as $item) {
            if ($item->category == 'images') {
                $trigger = true;
            } else {
                $trigger = News::doesNewsExists((string) $item->post_title, (string) $item->guid);
            }
            if (!$trigger) {
                $this->out('Saving - ' . $item->post_title);
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->post_content, $matches);
                $image = '';
                if (isset($matches[1])) {
                    $image = $matches[1];
                }
                $news = News::create(array(
                            'title' => $item->post_title,
                            'short' => strip_tags($item->post_excerpt),
                            'tags' => $item->category_name,
                            'created' => date('Y-m-d H:i:s', (time() - (HOUR))),
                            'link' => $item->guid,
                            'imageurl' => $image,
                            'lang' => 'ru'
                ));
                $news->save();
                Event::createEventNewsAdded($news->id, 0, date('Y-m-d H:i:s', (time())));
            }
        }
    }

    private function ParsingVozduhAfisha()
    {
        $xml = file_get_contents('http://vozduh.afisha.ru/export/rss/');
        $xml = simplexml_load_string($xml);

        if (($xml->channel->item) && (count($xml->channel->item > 0))) {
            $newsList = News::all();
            foreach ($xml->channel->item as $item) {
                if (strpos($item->link, '/art/') !== false || strpos($item->link, '/cinema/') !== false) {
                    $trigger = false;
                    foreach ($newsList as $n) {
                        if ((string) $item->title === (string) $n->title) {
                            $trigger = true;
                        }
                    }
                    if (!$trigger) {
                        $this->out('Saving - ' . $item->title);
                        $content = file_get_contents($item->link);
                        preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content, $matches);
                        preg_match("/<a.*class=\"tag\".*>(.*)<\/a>/", $content, $match);
                        $date = new \DateTime($item->pubDate);
                        News::create(array(
                            'title' => $item->title,
                            'tags' => $match[1],
                            'short' => strip_tags($item->description),
                            'created' => $date->format('Y-m-d H:i:s'),
                            'link' => $item->link,
                            'imageurl' => $matches[1]
                        ))->save();
                    }
                }
            }
        }
    }

    private function ParsingColta()
    {
        $xml = simplexml_load_file('http://www.colta.ru/feed');
        $newsList = News::all();
        foreach ($xml->channel->item as $item) {
            if ($item->category != 'Новости' && $item->category != 'Swiss Made') {
                $trigger = false;
                foreach ($newsList as $n) {
                    if ((string) $item->title === (string) $n->title) {
                        $trigger = true;
                    }
                }
                if (!$trigger) {
                    $content = file_get_contents($item->link);
                    $doc = new \DOMDocument();
                    libxml_use_internal_errors(true);
                    $doc->loadHTML($content);
                    $xml = simplexml_import_dom($doc);
                    libxml_clear_errors();
                    $images = $xml->xpath('//img');
                    $imgurl = '';
                    foreach ($images as $img) {
                        if (strpos($img['src'], '/storage') !== false && !strpos($img['src'], 'preview') && !strpos($img['src'], 'cover')) {
                            if (!preg_match('#colta.ru#', $img['src'])) {
                                $img['src'] = 'http://www.colta.ru' . $img['src'];
                            }
                            $imgurl = $img['src'];
                            break;
                        }
                    }
                    $this->out('Saving - ' . $item->title);
                    $date = new \DateTime($item->pubDate);
                    if (!empty($imgurl)) {
                        News::create(array(
                            'title' => $item->title,
                            'short' => strip_tags($item->description),
                            'tags' => $item->category,
                            'created' => $date->format('Y-m-d H:i:s'),
                            'link' => $item->link,
                            'imageurl' => $imgurl
                        ))->save();
                    }
                }
            }
        }
    }

    private function ParsingWordpress($url, $regexp = '/< *img[^>]*src *= *["\']?([^"\']*)/i', $event = true, $lang = 'en')
    {
        $temp = file_get_contents($url);
        $xml = simplexml_load_string($temp);
        if(($xml) && (isset($xml->channel)) && (isset($xml->channel->item))) {
            foreach ($xml->channel->item as $item) {
                $trigger = News::doesNewsExists((string) $item->title, $item->link);
                if (!$trigger) {
                    $this->out('Saving - ' . $item->title);
                    preg_match($regexp, $item->asXML(), $matches);
                    $image = '';
                    if (isset($matches[1])) {
                        $image = $matches[1];
                    }
                    if((!isset($item->category)) || ($item->category == '')) {
                        $item->category = 'Дизайн';
                    }
                    if ($lang == 'ru') {
                        $news = News::create(array(
                            'title' => $item->title,
                            'short' => strip_tags($item->description),
                            'tags' => $item->category,
                            'created' => date('Y-m-d H:i:s', (time() - (HOUR))),
                            'link' => $item->link,
                            'imageurl' => $image,
                            'lang' => $lang
                        ));
                    } else {
                        $news = News::create(array(
                            'title' => $this->translate($item->title),
                            'original_title' => $item->title,
                            'short' => $this->translate(strip_tags($item->description)),
                            'original_short' => strip_tags($item->description),
                            'tags' => $item->category,
                            'created' => date('Y-m-d H:i:s', (time() - (HOUR))),
                            'link' => $item->link,
                            'imageurl' => $image,
                            'lang' => $lang
                        ));
                    }
                    if (self::$debug == false) {
                        $news->save();
                        if ($event) {
                            Event::createEventNewsAdded($news->id, 0, date('Y-m-d H:i:s', (time())));
                        }
                    } else {
                        var_dump($news->data());
                    }
                }
            }
        }
    }

    private function ParsingWTPack($url, $regexp = '/< *img[^>]*src *= *["\']?([^"\']*)/i', $event = true, $lang = 'en')
    {
        $temp = file_get_contents($url);
        $xml = simplexml_load_string($temp);
        if(($xml) && (isset($xml->channel)) && (isset($xml->channel->item))) {
            foreach ($xml->channel->item as $item) {
                $trigger = News::doesNewsExists((string) $item->title, $item->link);
                if (!$trigger) {
                    $this->out('Saving - ' . $item->title);
                    $content = file_get_contents($item->link);
                    preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content, $matches);
                    $image = '';
                    if (isset($matches[1][1])) {
                        $image = $matches[1][1];
                    }
                    if((!isset($item->category)) || ($item->category == '')) {
                        $item->category = 'Дизайн';
                    }
                    if ($lang == 'ru') {
                        $news = News::create(array(
                            'title' => $item->title,
                            'short' => strip_tags($item->description),
                            'tags' => $item->category,
                            'created' => date('Y-m-d H:i:s', (time() - (HOUR))),
                            'link' => $item->link,
                            'imageurl' => $image,
                            'lang' => $lang
                        ));
                    } else {
                        $news = News::create(array(
                            'title' => $this->translate($item->title),
                            'original_title' => $item->title,
                            'short' => $this->translate(strip_tags($item->description)),
                            'original_short' => strip_tags($item->description),
                            'tags' => $item->category,
                            'created' => date('Y-m-d H:i:s', (time() - (HOUR))),
                            'link' => $item->link,
                            'imageurl' => $image,
                            'lang' => $lang
                        ));
                    }
                    if (self::$debug == false) {
                        $news->save();
                        if ($event) {
                            Event::createEventNewsAdded($news->id, 0, date('Y-m-d H:i:s', (time())));
                        }
                    } else {
                        var_dump($news->data());
                    }
                }
            }
        }
    }

    private function ParsingVice()
    {
        $xml = simplexml_load_file('http://www.vice.com/ru/rss');
        if (($xml) && (isset($xml->channel)) && (isset($xml->channel->item))) {
            foreach ($xml->channel->item as $item) {
                $trigger = News::doesNewsExists((string) $item->title, $item->link);
                if (!$trigger) {
                    $this->out('Saving - ' . $item->title);
                    $date = new \DateTime($item->pubDate);
                    preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->description, $matches);
                    if (isset($matches[1])) {
                        $news = News::create(array(
                                    'title' => $item->title,
                                    'short' => preg_replace('@\[.*\]@', '', strip_tags($item->description)),
                                    'tags' => substr($item->category, 0, strpos($item->category, ',')),
                                    'created' => $date->format('Y-m-d H:i:s'),
                                    'link' => $item->link,
                                    'imageurl' => $matches[1]
                        ));
                        $news->save();
                        Event::createEventNewsAdded($news->id, 0, $date->format('Y-m-d H:i:s'));
                    }
                }
            }
        }
    }

    private function ParsingRoyalcheese()
    {
        $url = 'http://www.royalcheese.ru/';
        $xml = simplexml_load_file($url . 'rss/');
        foreach ($xml->channel->item as $item) {
            $trigger = News::doesNewsExists((string) $item->title, $item->link);
            $photo = false;
            $city = false;
            if (!$trigger && ($photo = strpos($item->link, 'photo/') || $city = strpos($item->link, 'city/'))) {
                $this->out('Saving - ' . $item->title);
                $content = file_get_contents($item->link);
                preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content, $matches);
                $count = count($matches[1]);
                $image = '';
                for ($i = 0; $i < $count; $i++) {
                    if (strpos($matches[1][$i], 'gallery/')) {
                        $image = $matches[1][$i];
                        break;
                    }
                }
                $tag = '';
                if ($photo) {
                    $tag = 'Фотографии';
                } elseif ($city) {
                    $tag = 'Город';
                }
                $news = News::create(array(
                            'title' => $item->title,
                            'tags' => $tag,
                            'short' => '',
                            'created' => date('Y-m-d H:i:s', (time() - (HOUR))),
                            'link' => $item->link,
                            'imageurl' => $url . $image,
                            'lang' => 'ru'
                ));
                $news->save();
                Event::createEventNewsAdded($news->id, 0, date('Y-m-d H:i:s', (time())));
            }
        }
    }

    private function ParsingLookatme()
    {
        $xml = simplexml_load_file('http://www.lookatme.ru/feeds/posts.atom?topic=people');
        foreach ($xml->entry as $item) {
            $trigger = News::doesNewsExists((string) $item->title, (string) $item->id);

            if (!$trigger) {
                $date = new \DateTime($item->published);
                $content = file_get_contents($item->id);
                libxml_use_internal_errors(true);
                $doc = new \DomDocument();
                $doc->loadHTML($content);
                $xpath = new \DOMXPath($doc);
                $query = '//*/meta[starts-with(@property, \'article:tag\')]';
                $metas = $xpath->query($query);
                foreach ($metas as $meta) {
                    $tag = $meta->getAttribute('content');
                }
                $cat = '';
                if (strpos($tag, 'Дизайн')) {
                    $cat = 'Дизайн';
                } elseif (strpos($tag, 'Типографика')) {
                    $cat = 'Типографика';
                } elseif (strpos($tag, 'Шрифты')) {
                    $cat = 'Шрифты';
                } elseif (strpos($tag, 'Полиграфия')) {
                    $cat = 'Полиграфия';
                } elseif (strpos($tag, 'Интерфейс')) {
                    $cat = 'Интерфейс';
                }
                $cat = 'Герои';
                preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content, $matches);
                $image = '';
                $count = count($matches[1]);
                for ($i = 0; $i < $count; $i++) {
                    if (strpos($matches[1][$i], 'lamcdn.net')) {
                        $image = $matches[1][$i];
                        break;
                    }
                }
                if (strlen($image) > 0 && strlen($cat) > 0) {
                    $data = array(
                        'title' => (string) $item->title,
                        'tags' => $cat,
                        'short' => (string) str_replace('Читать дальше...', '', strip_tags($item->content)),
                        'created' => date('Y-m-d H:i:s', (time() - (HOUR))),
                        'link' => (string) $item->id,
                        'imageurl' => $image,
                        'lang' => 'ru'
                    );
                    if (self::$debug == false) {
                        $this->out('Saving - ' . $item->title);
                        $news = News::create($data);
                        $news->save();
                        Event::createEventNewsAdded($news->id, 0, date('Y-m-d H:i:s', (time())));
                    } else {
                        var_dump($item);
                        var_dump($data);
                    }
                }
            }
        }
    }

    private function ParsingVillage()
    {
        $xml = simplexml_load_file('http://www.the-village.ru/feeds/posts.atom');
        $newsList = News::all();
        foreach ($xml->entry as $item) {
            $trigger = false;
            foreach ($newsList as $n) {
                if (((string) $item->title === (string) $n->title) || ($n->link == $item->id)) {
                    $trigger = true;
                }
            }
            if (!$trigger) {
                $date = new \DateTime($item->published);
                $content = file_get_contents($item->id);
                $cat = '';
                if (strpos($item->id, 'home/')) {
                    $cat = 'Дом';
                } elseif (strpos($item->id, 'service-shopping/')) {
                    $cat = 'Стиль';
                }
                preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content, $matches);
                $image = '';
                $img = 0;
                $count = count($matches[1]);
                for ($i = 0; $i < $count; $i++) {
                    if (strpos($matches[1][$i], 'lamcdn.net')) {
                        $image = $matches[1][$i];
                        ++$img;
                        if ($img >= 2) {
                            break;
                        }
                    }
                }
                if (strlen($image) > 0 && strlen($cat) > 0) {
                    $this->out('Saving - ' . $item->title);
                    $news = News::create(array(
                                'title' => $item->title,
                                'tags' => $cat,
                                'short' => '',
                                'created' => $date->format('Y-m-d H:i:s'),
                                'link' => $item->id,
                                'imageurl' => $image,
                        'lang' => 'ru'
                    ));
                    $news->save();
                    Event::createEventNewsAdded($news->id, 0, $date->format('Y-m-d H:i:s'));
                }
            }
        }
    }


    private function ParsingKoko($url)
    {
        $temp = file_get_contents($url);
        $xml = simplexml_load_string($temp);
        if(($xml) && (isset($xml->channel)) && (isset($xml->channel->item))) {
            foreach ($xml->channel->item as $item) {
                $trigger = News::doesNewsExists((string) $item->title, $item->link);
                if (!$trigger) {
                    $date = new \DateTime($item->published);
                    $image = '';
                    preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->description, $matches);
                    if (isset($matches[1]) && !strpos($matches[1], 'feedburner.com/')) {
                        $image = $matches[1];
                    }
                    $data = array(
                        'title' => (string) $item->title,
                        'short' => strip_tags($item->description),
                        'tags' => 'Дизайн',
                        'created' => $date->format('Y-m-d H:i:s'),
                        'link' => (string) $item->link,
                        'imageurl' => $image,
                        'lang' => 'ru'
                    );
                    if (self::$debug == false) {
                        $news = News::create($data);
                        $news->save();
                        Event::createEventNewsAdded($news->id, 0, $date->format('Y-m-d H:i:s'));
                    } else {
                        var_dump($data);
                    }
                }
            }
        }
    }

    private function ParsingRss($url)
    {
        $xml = simplexml_load_file($url);
        foreach ($xml->channel->item as $item) {
            $trigger = News::doesNewsExists((string) $item->title, (string) $item->link);
            if (!$trigger) {
                $date = new \DateTime($item->published);
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->description, $matches);
                $image = '';
                if (isset($matches[1])) {
                    $image = $matches[1];
                }
                $data = array(
                    'title' => (string) $item->title,
                    'short' => trim(strip_tags($item->description)),
                    'tags' => 'Дизайн',
                    'created' => $date->format('Y-m-d H:i:s'),
                    'link' => (string) $item->link,
                    'imageurl' => $image,
                    'lang' => 'ru'
                );
                if (self::$debug == false) {
                    $news = News::create($data);
                    $news->save();
                    Event::createEventNewsAdded($news->id, 0, $date->format('Y-m-d H:i:s'));
                } else {
                    var_dump($data);
                }
            }
        }
    }

    private function ParsingSimpleRss($url)
    {
        $xml = simplexml_load_file($url);
        foreach ($xml->channel->item as $item) {
            $trigger = News::doesNewsExists((string) $item->title, (string) $item->link);
            if (!$trigger) {
                $date = new \DateTime($item->published);
                $image = '';

                foreach ($item->enclosure->attributes() as $key => $value) {
                    if ($key == 'url') {
                        $image = (string) $value;
                    }
                }
                $data = array(
                    'title' => (string) $item->title,
                    'short' => strip_tags($item->description),
                    'tags' => 'Дизайн',
                    'created' => $date->format('Y-m-d H:i:s'),
                    'link' => (string) $item->link,
                    'imageurl' => $image,
                    'lang' => 'ru'
                );
                if (self::$debug == false) {
                    $news = News::create($data);
                    $news->save();
                    Event::createEventNewsAdded($news->id, 0, $date->format('Y-m-d H:i:s'));
                } else {
                    var_dump($data);
                }
            }
        }
    }

    private function ParsingInterview($url)
    {
        $xml = simplexml_load_file($url);
        foreach ($xml->channel->item as $item) {
            $trigger = News::doesNewsExists((string) $item->title, (string) $item->link, (string) $item->guid);
            if (!$trigger) {
                $date = new \DateTime($item->published);
                $data = array(
                    'title' => (string) $item->title,
                    'short' => strip_tags($item->description),
                    'tags' => 'Дизайн',
                    'created' => $date->format('Y-m-d H:i:s'),
                    'link' => (string) $item->link,
                    'imageurl' => (string) $item->guid,
                    'lang' => 'ru'
                );
                if (self::$debug == false) {
                    $news = News::create($data);
                    $news->save();
                    Event::createEventNewsAdded($news->id, 0, $date->format('Y-m-d H:i:s'));
                } else {
                    var_dump($data);
                }
            }
        }
    }

    private function ParsingDesnewsru()
    {
        $xml = simplexml_load_file('http://desnews.ru/?feed=rss2');
        if (($xml) && (isset($xml->channel)) && (isset($xml->channel->item))) {
            foreach ($xml->channel->item as $item) {
                $trigger = News::doesNewsExists($item->title,  $item->guid);
                if (!$trigger) {
                    $date = new \DateTime($item->published);
                    $content = file_get_contents($item->guid);
                    preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content, $matches);
                    $count = count($matches[1]);
                    $image = '';
                    for ($i = 0; $i < $count; $i++) {
                        if (strpos($matches[1][$i], 'uploads/')) {
                            $image = $matches[1][$i];
                            if ($i == 1) {
                                break;
                            }
                        }
                    }
                    $data = array(
                        'title' => (string) $item->title,
                        'short' => strip_tags($item->description),
                        'tags' => 'Дизайн',
                        'created' => $date->format('Y-m-d H:i:s'),
                        'link' => (string) $item->guid,
                        'imageurl' => $image,
                        'lang' => 'ru'
                    );

                    $news = News::create($data);
                    $news->save();
                    Event::createEventNewsAdded($news->id, 0, $date->format('Y-m-d H:i:s'));
                }
            }
        }
    }

    private function ParsingPackaginguqam()
    {
        $xml = simplexml_load_file('http://feeds.feedburner.com/blogspot/mzWJ?format=xml');
        foreach ($xml->entry as $item) {
            $trigger = News::doesNewsExists($item->title,  substr($item->link['href'], 0, strpos($item->link['href'], '#')));
            if (!$trigger) {
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->content, $matches);
                if (isset($matches[1]) && !strpos($matches[1], 'feedburner.com/')) {
                    $news = News::create(array(
                                'title' => (string) $item->title,
                                'tags' => 'Дизайн',
                                'short' => '',
                                'created' => date('Y-m-d H:i:s', (time() - (HOUR))),
                                'link' => substr($item->link['href'], 0, strpos($item->link['href'], '#')),
                                'imageurl' => $matches[1],
                        'lang' => 'ru'
                    ));
                    $news->save();
                    Event::createEventNewsAdded($news->id, 0, date('Y-m-d H:i:s', (time())));
                }
            }
        }
    }

    private function ParsingAbduzeedo()
    {
        $xml = simplexml_load_file('http://feeds.feedburner.com/abduzeedo');
        $newsList = News::all();
        foreach ($xml->channel->item as $item) {
            $trigger = false;
            foreach ($newsList as $n) {
                if (((string) $item->title === (string) $n->title) || ($n->link == $item->link)) {
                    $trigger = true;
                }
            }
            if (!$trigger) {
                $date = new \DateTime($item->pubDate);
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->description, $matches);
                if (isset($matches[1])) {
                    $news = News::create(array(
                                'title' => $this->translate((string) $item->title),
                                'original_title' => (string) $item->title,
                                'tags' => 'Дизайн',
                                'short' => $this->translate(strip_tags($item->description)),
                                'original_short' => strip_tags($item->description),
                                'created' => date('Y-m-d H:i:s', (time() - (HOUR))),
                                'link' => $item->link,
                                'imageurl' => $matches[1],
                                'lang' => 'en'
                    ));
                    $news->save();
                    Event::createEventNewsAdded($news->id, 0, date('Y-m-d H:i:s', (time())));
                }
            }
        }
    }

    private function ParsingUnderconsideration()
    {
        $xml = simplexml_load_file('http://feeds.feedburner.com/ucllc/fpo');
        $newsList = News::all();
        foreach ($xml->entry as $item) {
            $trigger = false;
            foreach ($newsList as $n) {
                if (((string) $item->title === (string) $n->title) || ($n->link == $item->link['href'])) {
                    $trigger = true;
                }
            }
            if (!$trigger) {
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->content, $matches);
                if (isset($matches[1])) {
                    $news = News::create(array(
                                'title' => $this->translate($item->title),
                                'original_title' => $item->title,
                                'tags' => trim($item->category['term']),
                                'short' => $this->translate(trim(strip_tags($item->content))),
                                'original_short' => trim(strip_tags($item->content)),
                                'created' => date('Y-m-d H:i:s', (time() - (HOUR))),
                                'link' => $item->link['href'],
                                'imageurl' => $matches[1],
                        'lang' => 'en'
                    ));
                    $news->save();
                    Event::createEventNewsAdded($news->id, 0, date('Y-m-d H:i:s', (time())));
                }
            }
        }
    }

    private function hel_looks()
    {
        $xml = simplexml_load_file('http://feeds.feedburner.com/hel-looks');
        $newsList = News::all();
        foreach ($xml->channel->item as $item) {
            $trigger = false;
            foreach ($newsList as $n) {
                if (((string) $item->title === (string) $n->title) || ($n->link == $item->link)) {
                    $trigger = true;
                }
            }
            if (!$trigger) {
                $date = new \DateTime($item->pubDate);
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->description, $matches);
                if (isset($matches[1])) {
                    $news = News::create(array(
                                'title' => $this->translate((string) $item->title),
                                'original_title' => (string) $item->title,
                                'tags' => 'Мода',
                                'short' => strip_tags($item->description),
                                'origin_short' => $this->translate(strip_tags($item->description)),
                                'created' => date('Y-m-d H:i:s', (time() - (HOUR))),
                                'link' => $item->link,
                                'imageurl' => $matches[1],
                                'lang' => 'en'
                    ));
                    $news->save();
                    Event::createEventNewsAdded($news->id, 0, date('Y-m-d H:i:s', (time())));
                }
            }
        }
    }

    private function fixTags()
    {
        $newsList = News::all();
        $trigger = false;
        foreach ($newsList as $news) {
            if ($str = strpos($news->tags, ',')) {
                $news->tags = substr($news->tags, 0, $str);
                $trigger = true;
            } elseif ($str = strpos($news->tags, '|')) {
                $news->tags = substr($news->tags, 0, $str);
                $trigger = true;
            }
        }
        if ($trigger) {
            $newsList->save();
        }
    }

    /*
     * Create and execute the HTTP CURL request.
     *
     * @param string $url        HTTP Url.
     * @param string $authHeader Authorization Header string.
     * @param string $postData   Data to post.
     *
     * @return string.
     *
     */
    public function curlRequest($url, $authHeader, $postData='')
    {
        //Initialize the Curl Session.
        $ch = curl_init();
        //Set the Curl url.
        curl_setopt($ch, CURLOPT_URL, $url);
        //Set the HTTP HEADER Fields.
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($authHeader, "Content-Type: text/xml"));
        //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($postData) {
            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, true);
            //Set data to POST in HTTP "POST" Operation.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        //Execute the  cURL session.
        $curlResponse = curl_exec($ch);
        //Get the Error Code returned by Curl.
        $curlErrno = curl_errno($ch);
        if ($curlErrno) {
            $curlError = curl_error($ch);
            throw new Exception($curlError);
        }
        //Close a cURL session.
        curl_close($ch);
        return $curlResponse;
    }

    /*
     * Create Request XML Format.
     *
     * @param string $languageCode  Language code
     *
     * @return string.
     */
    public function createReqXML($languageCode)
    {
        //Create the Request XML.
        $requestXml = '<ArrayOfstring xmlns="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">';
        if ($languageCode) {
            $requestXml .= "<string>$languageCode</string>";
        } else {
            throw new Exception('Language Code is empty.');
        }
        $requestXml .= '</ArrayOfstring>';
        return $requestXml;
    }

    public function translate($text)
    {
        $from = "en";
        $to = "ru";

        $accessToken  = Event::getBingAccessToken();
        $authHeader = "Authorization: Bearer ". $accessToken;
        $translateUrl = "http://api.microsofttranslator.com/v2/Http.svc/Translate?text=" . urlencode($text) . "&from=" . $from . "&to=" . $to;

        $strResponse = $this->curlRequest($translateUrl, $authHeader);

        $xml = simplexml_load_string($strResponse);
        $json = json_encode($xml);
        $array = json_decode($json, true);
        if (isset($array[0])) {
            return $array[0];
        } else {
            return '';
        }
    }
}
