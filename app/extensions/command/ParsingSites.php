<?php

namespace app\extensions\command;

use \app\models\Post;
use \app\models\News;
use \app\models\Solution;
use \app\models\Event;

class ParsingSites extends \app\extensions\command\CronJob {

    public function run() {
        $startTimeStamp = time();
        $this->header('Welcome to the ParsingSites command!');
        $this->out("Starting parsing godesigner.ru");
        self::ParsingGodesigner();
        $this->out('Finished parsing godesigner.ru [' . (time() - $startTimeStamp) . ' sec]');
        $this->out("Starting parsing tutdesign.ru");
        self::ParsingTutdesign();
        $this->out('Finished parsing tutdesign.ru [' . (time() - $startTimeStamp) . ' sec]');
        $this->out("Starting parsing vozduh.afisha.ru");
        self::ParsingVozduhAfisha();
        $this->out('Finished parsing vozduh.afisha.ru [' . (time() - $startTimeStamp) . ' sec]');
        $this->out("Starting parsing colta.ru");
        self::ParsingColta();
        $this->out('Finished parsing colta.ru [' . (time() - $startTimeStamp) . ' sec]');
        $this->out("Starting parsing newgrids.fr");
        self::ParsingNewgrids();
        $this->out('Finished parsing newgrids.fr [' . (time() - $startTimeStamp) . ' sec]');
        $this->out("Starting parsing lovelypackage.com");
        self::ParsingLovelypackage();
        $this->out('Finished parsing lovelypackage.com [' . (time() - $startTimeStamp) . ' sec]');
        $this->out("Starting parsing bpando.org");
        self::ParsingBpando();
        $this->out('Finished parsing bpando.org [' . (time() - $startTimeStamp) . ' sec]');
    }

    private function ParsingGodesigner() {
        $posts = Post::all(array('conditions' => array('published' => 1, 'created' => array('<=' => date('Y-m-d H:i:s'))), 'limit' => 300, 'order' => array('created' => 'desc')));
        $newsList = News::all();
        foreach ($posts as $post) {
            $trigger = false;
            if(($newsList) && (count($newsList) > 0)){
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
                if(isset($matches[1])) {
                    $image = $matches[1];
                }
                $news = News::create(array(
                            'title' => $post->title,
                            'short' => strip_tags($post->short),
                            'tags' => $post->tags,
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
        $xml = simplexml_load_file('http://tutdesign.ru/feed');
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
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->asXML(), $matches);
                $news = News::create(array(
                    'title' => $item->title,
                    'short' => strip_tags($item->description),
                    'tags' => $item->category,
                    'created' => $date->format('Y-m-d H:i:s'),
                    'link' => $item->link,
                    'imageurl' => $matches[1]
                ));
                $news->save();
                Event::createEventNewsAdded($news->id, 0, $date->format('Y-m-d H:i:s'));
            }
        }
    }

    private function ParsingVozduhAfisha() {
        $xml = file_get_contents('http://vozduh.afisha.ru/export/rss/');
        $xml = simplexml_load_string($xml);

        if(($xml->channel->item) && (count($xml->channel->item > 0))) {
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
                            if(!preg_match('#colta.ru#', $img['src'])) {
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

    private function ParsingNewgrids() {
        $xml = simplexml_load_file('http://newgrids.fr/feed');
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
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->asXML(), $matches);
                $image = '';
                if(isset($matches[1])) {
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
            }
        }
    }

    private function ParsingLovelypackage() {
        $xml = simplexml_load_file('http://lovelypackage.com/feed/');
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
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->asXML(), $matches);
                $image = '';
                if(isset($matches[1])) {
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
            }
        }
    }

    private function ParsingBpando() {
        $xml = simplexml_load_file('http://bpando.org/feed/');
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
                preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $item->asXML(), $matches);
                $image = '';
                if(isset($matches[1])) {
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
            }
        }
    }

}
