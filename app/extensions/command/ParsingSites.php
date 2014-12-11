<?php

namespace app\extensions\command;

use \app\models\Post;
use \app\models\Wp_post;
use \app\models\News;
use \app\models\Event;

class ParsingSites extends \app\extensions\command\CronJob {

    public function run() {
        // На случай долгих таймаутов
        set_time_limit(120);
        $startTimeStamp = time();
        $this->header('Welcome to the ParsingSites command!');

        $this->out("Starting parsing godesigner.ru");
        self::ParsingGodesigner();
        $this->out('Finished parsing godesigner.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing tutdesign.ru");
        self::ParsingTutdesign();
        $this->out('Finished parsing tutdesign.ru [' . (time() - $startTimeStamp) . ' sec]');

        //$this->out("Starting parsing vozduh.afisha.ru");
        //self::ParsingVozduhAfisha();
        //$this->out('Finished parsing vozduh.afisha.ru [' . (time() - $startTimeStamp) . ' sec]');
        //$this->out("Starting parsing colta.ru");
        //self::ParsingColta();
        //$this->out('Finished parsing colta.ru [' . (time() - $startTimeStamp) . ' sec]');
        $this->out("Starting parsing newgrids.fr");
        self::ParsingWordpress('http://newgrids.fr/feed', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing newgrids.fr [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing lovelypackage.com");
        self::ParsingWordpress('http://lovelypackage.com/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing lovelypackage.com [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing bpando.org");
        self::ParsingWordpress('http://bpando.org/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing bpando.org [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing love-aesthetics.nl");
        self::ParsingWordpress('http://love-aesthetics.nl/category/diy/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing love-aesthetics.nl [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing vice.com/ru");
        self::ParsingVice();
        $this->out('Finished parsing vice.com/ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing wtpack.ru");
        self::ParsingWordpress('http://wtpack.ru/feed', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing wtpack.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing royalcheese.ru");
        self::ParsingRoyalcheese();
        $this->out('Finished parsing royalcheese.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing lookatme.ru");
        self::ParsingLookatme();
        $this->out('Finished parsing lookatme.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing the-village.ru");
        self::ParsingVillage();
        $this->out('Finished parsing the-village.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing packaginguqam.blogspot.ru");
        self::ParsingPackaginguqam();
        $this->out('Finished parsing packaginguqam.blogspot.ru [' . (time() - $startTimeStamp) . ' sec]');

        $this->out("Starting parsing fuckingyoung.es");
        self::ParsingWordpress('http://fuckingyoung.es/feed/', '/< *img[^>]*src *= *["\']?([^"\']*)/i');
        $this->out('Finished parsing fuckingyoung.es [' . (time() - $startTimeStamp) . ' sec]');

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

        $this->out("Starting fixing tags");
        self::fixTags();
        $this->out('Finished fixing tags [' . (time() - $startTimeStamp) . ' sec]');
    }

    private function ParsingGodesigner() {
        $posts = Post::all(array('conditions' => array('published' => 1, 'created' => array('<=' => date('Y-m-d H:i:s'))), 'limit' => 300, 'order' => array('created' => 'desc')));
        $newsList = News::all();
        foreach ($posts as $post) {
            $trigger = false;
            if (($newsList) && (count($newsList) > 0)) {
                foreach ($newsList as $n) {
                    if ((string) $post->title === (string) $n->title) {
                        $trigger = true;
                    }
                }
            }
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
                            'created' => $post->created,
                            'link' => 'http://www.godesigner.ru/posts/view/' . $post->id,
                            'imageurl' => $image
                ));
                $news->save();
                Event::createEventNewsAdded($news->id, 0, $post->created);
            }
        }
    }

    private function ParsingTutdesign() {
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
                    'limit' => 300
        ));
        $newsList = News::all();

        foreach ($posts as $item) {
            $trigger = false;
            foreach ($newsList as $n) {
                if (((string) $item->post_title === (string) $n->title) || $item->category == 'images') {
                    $trigger = true;
                }
            }
            if (!$trigger) {
                $this->out('Saving - ' . $item->post_title);
                $date = new \DateTime($item->post_date);
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->post_content, $matches);
                $image = '';
                if (isset($matches[1])) {
                    $image = $matches[1];
                }
                $news = News::create(array(
                            'title' => $item->post_title,
                            'short' => strip_tags($item->post_excerpt),
                            'tags' => $item->category_name,
                            'created' => $date->format('Y-m-d H:i:s'),
                            'link' => $item->guid,
                            'imageurl' => $image
                ));
                $news->save();
                Event::createEventNewsAdded($news->id, 0, $date->format('Y-m-d H:i:s'));
            }
        }
    }

    private function ParsingVozduhAfisha() {
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

    private function ParsingColta() {
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

    private function ParsingWordpress($url, $regexp = '/< *img[^>]*src *= *["\']?([^"\']*)/i', $event = false) {
        $xml = simplexml_load_file($url);
        $newsList = News::all();
        foreach ($xml->channel->item as $item) {
            $trigger = false;
            foreach ($newsList as $n) {
                if ((string) $item->title === (string) $n->title) {
                    $trigger = true;
                }
            }
            if (!$trigger) {
                $this->out('Saving - ' . $item->title);
                $date = new \DateTime($item->pubDate);
                preg_match($regexp, $item->asXML(), $matches);
                $image = '';
                if (isset($matches[1])) {
                    $image = $matches[1];
                }
                $news = News::create(array(
                            'title' => $item->title,
                            'short' => strip_tags($item->description),
                            'tags' => $item->category,
                            'created' => $date->format('Y-m-d H:i:s'),
                            'link' => $item->link,
                            'imageurl' => $image
                ));
                $news->save();
                if ($event) {
                    Event::createEventNewsAdded($news->id, 0, $date->format('Y-m-d H:i:s'));
                }
            }
        }
    }

    private function ParsingVice() {
        $xml = simplexml_load_file('http://www.vice.com/ru/rss');
        $newsList = News::all();
        foreach ($xml->channel->item as $item) {
            $trigger = false;
            foreach ($newsList as $n) {
                if ((string) $item->title === (string) $n->title) {
                    $trigger = true;
                }
            }
            if (!$trigger) {
                $this->out('Saving - ' . $item->title);
                $date = new \DateTime($item->pubDate);
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->description, $matches);
                if (isset($matches[1])) {
                    $news = News::create(array(
                                'title' => $item->title,
                                'short' => strip_tags($item->description),
                                'tags' => substr($item->category, 0, strpos($item->category, ',')),
                                'created' => $date->format('Y-m-d H:i:s'),
                                'link' => $item->link,
                                'imageurl' => $matches[1]
                    ));
                    $news->save();
                }
            }
        }
    }

    private function ParsingRoyalcheese() {
        $url = 'http://www.royalcheese.ru/';
        $xml = simplexml_load_file($url . 'rss/');
        $newsList = News::all();

        foreach ($xml->channel->item as $item) {
            $trigger = false;
            foreach ($newsList as $n) {
                if ((string) $item->title === (string) $n->title) {
                    $trigger = true;
                }
            }
            $photo = false;
            $city = false;
            if (!$trigger && ($photo = strpos($item->link, 'photo/') || $city = strpos($item->link, 'city/'))) {
                $this->out('Saving - ' . $item->title);
                $content = file_get_contents($item->link);
                $date = new \DateTime($item->pubDate);
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
                            'created' => $date->format('Y-m-d H:i:s'),
                            'link' => $item->link,
                            'imageurl' => $url . $image
                ));
                $news->save();
            }
        }
    }

    private function ParsingLookatme() {
        $xml = simplexml_load_file('http://www.lookatme.ru/feeds/posts.atom');
        $newsList = News::all();
        foreach ($xml->entry as $item) {
            $trigger = false;
            foreach ($newsList as $n) {
                if ((string) $item->title === (string) $n->title) {
                    $trigger = true;
                }
            }
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
                    $this->out('Saving - ' . $item->title);
                    $news = News::create(array(
                                'title' => $item->title,
                                'tags' => $cat,
                                'created' => $date->format('Y-m-d H:i:s'),
                                'link' => $item->id,
                                'imageurl' => $image
                    ));
                    $news->save();
                }
            }
        }
    }

    private function ParsingVillage() {
        $xml = simplexml_load_file('http://www.the-village.ru/feeds/posts.atom');
        $newsList = News::all();
        foreach ($xml->entry as $item) {
            $trigger = false;
            foreach ($newsList as $n) {
                if ((string) $item->title === (string) $n->title) {
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
                $count = count($matches[1]);
                for ($i = 0; $i < $count; $i++) {
                    if (strpos($matches[1][$i], 'lamcdn.net')) {
                        $image = $matches[1][$i];
                        break;
                    }
                }
                if (strlen($image) > 0 && strlen($cat) > 0) {
                    $this->out('Saving - ' . $item->title);
                    $news = News::create(array(
                                'title' => $item->title,
                                'tags' => $cat,
                                'created' => $date->format('Y-m-d H:i:s'),
                                'link' => $item->id,
                                'imageurl' => $image
                    ));
                    $news->save();
                }
            }
        }
    }

    private function ParsingPackaginguqam() {
        $xml = simplexml_load_file('http://feeds.feedburner.com/blogspot/mzWJ?format=xml');
        $newsList = News::all();
        foreach ($xml->entry as $item) {
            $trigger = false;
            foreach ($newsList as $n) {
                if ((string) $item->title === (string) $n->title) {
                    $trigger = true;
                }
            }
            if (!$trigger) {
                $date = new \DateTime($item->published);
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->content, $matches);
                if (isset($matches[1]) && !strpos($matches[1], 'feedburner.com/')) {
                    $news = News::create(array(
                                'title' => (string) $item->title,
                                'tags' => 'Дизайн',
                                'created' => $date->format('Y-m-d H:i:s'),
                                'link' => substr($item->link['href'], 0, strpos($item->link['href'], '#')),
                                'imageurl' => $matches[1]
                    ));
                    $news->save();
                }
            }
        }
    }

    private function ParsingAbduzeedo() {
        $xml = simplexml_load_file('http://feeds.feedburner.com/abduzeedo');
        $newsList = News::all();
        foreach ($xml->channel->item as $item) {
            var_dump($item);
            $trigger = false;
            foreach ($newsList as $n) {
                if ((string) $item->title === (string) $n->title) {
                    $trigger = true;
                }
            }
            if (!$trigger) {
                $date = new \DateTime($item->pubDate);
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->description, $matches);
                if (isset($matches[1])) {
                    $news = News::create(array(
                                'title' => (string) $item->title,
                                'tags' => 'Дизайн',
                                'short' => strip_tags($item->description),
                                'created' => $date->format('Y-m-d H:i:s'),
                                'link' => $item->link,
                                'imageurl' => $matches[1]
                    ));
                    $news->save();
                }
            }
        }
    }

    private function ParsingUnderconsideration() {
        $xml = simplexml_load_file('http://feeds.feedburner.com/ucllc/fpo');
        $newsList = News::all();
        foreach ($xml->entry as $item) {
            $trigger = false;
            foreach ($newsList as $n) {
                if ((string) $item->title === (string) $n->title) {
                    $trigger = true;
                }
            }
            if (!$trigger) {
                $date = new \DateTime($item->published);
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->content, $matches);
                if (isset($matches[1])) {
                    $news = News::create(array(
                                'title' => $item->title,
                                'tags' => trim($item->category['term']),
                                'short' => trim(strip_tags($item->content)),
                                'created' => $date->format('Y-m-d H:i:s'),
                                'link' => $item->link['href'],
                                'imageurl' => $matches[1]
                    ));
                    $news->save();
                }
            }
        }
    }

    private function fixTags() {
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

}
