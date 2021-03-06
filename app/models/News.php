<?php

namespace app\models;

use app\extensions\storage\Rcache;
use lithium\data\collection\RecordSet;
use lithium\data\entity\Record;

/**
 * Class News
 * @package app\models
 * @method Record|null first(array $conditions) static
 * @method int count(array $conditions) static
 * @method RecordSet|null all(array $conditions) static
 */
class News extends AppModel
{

    private static $news;
    public $hasMany = ['Like'];
    protected static $processImage = [
        'middleFeed' => [
            'image_resize' => true,
            'image_x' => 600,
            'image_y' => 500,
            'image_ratio' => true,
            'file_overwrite' => true
        ],
    ];

    public static function getPost($newsDate = 0)
    {
        $post = 0;
        if ($newsDate < 1) {
            $post = Rcache::read('middle-post');
            self::$news = self::all(['conditions' => ['created' => ['>' => $newsDate], 'isBanner' => 0, 'toggle' => 0], 'order' => ['created' => 'desc']]);
            if (self::$news && !$post) {
                $all_views = 0;
                foreach (self::$news as $n) {
                    if ($n->middle) {
                        $post = $n;
                        $post->short = html_entity_decode($post->short, ENT_QUOTES, 'UTF-8');
                        $post->short = mb_strimwidth($post->short, 0, 250, '...');
                        break;
                    }
                    $all_views += $n->views;
                }
                if (!$post) {
                    $av_views = round($all_views / count(self::$news));
                    $max = 0;
                    foreach (self::$news as $n) {
                        if (strpos($n->link, 'tutdesign')) {
                            continue;
                        }
                        if (($n->views > $av_views * 2 && $max < $n->views) || $max < $n->views) {
                            $max = $n->views;
                            $host = parse_url($n->link);
                            $n->host = $host['host'];
                            $n->short = html_entity_decode($n->short, ENT_QUOTES, 'UTF-8');
                            $n->short = mb_strimwidth($n->short, 0, 250, '...');
                            $str = strpos($n->tags, '|');
                            if ($str) {
                                $n->tags = substr($n->tags, 0, $str);
                            }
                            $post = $n;
                        }
                    }
                }
                Rcache::write('middle-post', $post, '+1 hour');
            }
        }
        return $post;
    }

    public static function getNews($newsDate = 0, $page = 1)
    {
        return self::all(['conditions' => ['created' => ['>' => $newsDate], 'toggle' => 0, 'isBanner' => 0, 'hidden' => 0, 'link' => ['NOT LIKE' => ['%http://tutdesign.ru/%', '%https://godesigner.ru/%']]], 'limit' => 25, 'page' => $page, 'order' => ['created' => 'desc']]);
    }

    public static function getBanner()
    {
        return self::first(['conditions' => ['isBanner' => 1, 'hidden' => 0], 'order' => ['created' => 'desc']]);
    }

    public static function resize($file)
    {
        $options = self::$processImage;
        foreach ($options as $option => $imageParams) {
            $newfiledata = pathinfo($file['name']);
            $newfiledata['filename'] = md5(uniqid('', true));
            $newfiledata['dirname'] = 'events';
            $newfilename = $newfiledata['dirname'] . '/' . $newfiledata['filename'] . '_' . $option . '.' . $newfiledata['extension'];
            $imageProcessor = new \upload($file['tmp_name']);
            $imageProcessor->upload($file['tmp_name']);
            $imageProcessor->init();
            $imageProcessor->uploaded = true;
            $imageProcessor->no_upload_check = true;
            $imageProcessor->file_src_pathname = $file['tmp_name'];
            $imageProcessor->file_src_name_ext = $newfiledata['extension'];
            $imageProcessor->file_new_name_body = $newfiledata['filename'] . '_' . $option;
            foreach ($imageParams as $param => $value) {
                $imageProcessor->{$param} = $value;
            }
            $imageProcessor->process($newfiledata['dirname']);

            return '/' . $newfilename;
        }

        return true;
    }

    public static function decreaseLike($newsId, $userId = 0)
    {
        $result = false;
        $news = self::first($newsId);
        $userId = (int) $userId;
        if (($like = Like::first(['conditions' => ['news_id' => $newsId, 'user_id' => $userId]])) && ($newsId)) {
            $news->liked -= 1;
            $news->save();
            if ($result = $like->delete()) {
                if ($event = Event::first(['conditions' => ['user_id' => $userId, 'news_id' => $newsId, 'Event.type' => 'LikeAdded']])) {
                    $event->delete();
                }
            }
        }
        return ['result' => $result, 'likes' => $news->likes];
    }

    public static function increaseLike($newsId, $userId = 0)
    {
        $result = false;
        $news = self::first($newsId);
        if ($userId == 0) {
            return $news->liked;
        }
        $userId = (int) $userId;
        $allowUser = false;
        if ($userId && (!$like = Like::find('first', ['conditions' => ['news_id' => $newsId, 'user_id' => $userId]]))) {
            $allowUser = true;
        }
        if ($allowUser) {
            $news->liked += 1;
            $news->save();
            $result = Like::create([
                        'news_id' => $newsId,
                        'user_id' => $userId,
                        'created' => date('Y-m-d H:i:s')
                    ])->save();
            if ($result) {
                Event::createEvent(0, 'LikeAdded', $userId, 0, 0, $newsId);
            }
        }
        return ['result' => $result, 'likes' => $news->liked];
    }

    public static function hideNews($newsId)
    {
        $result = false;
        if ($news = self::first($newsId)) {
            $news->hidden = 1;
            $news->save();
            if ($event = Event::first(['conditions' => ['news_id' => $news->id]])) {
                $event->delete();
            }
            $result = true;
        } elseif ($event = Event::first(['conditions' => ['news_id' => $newsId]])) {
            $event->delete();
            $result = true;
        }
        return $result;
    }

    /**
     * Метод проверяет, существует ли новость сназванием $title или
     * полем link == $url иди imageUrl
     *
     * @param string $title
     * @param string $url
     * @param string $imageUrl
     * @return bool
     */
    public static function doesNewsExists($title, $url = null, $imageUrl = null)
    {
        $conditions = [];
        if (!is_null($url)) {
            $conditions['link'] = (string) $url;
        } elseif (!is_null($imageUrl)) {
            /**
             * @todo - возможно, следует убрать
             */
            $conditions = ['OR' => [
                ['imageurl' => (string) $imageUrl],
                ['title' => (string) $title]]
            ];
        } else {
            $conditions['title'] = (string) $title;
        }
        return (bool) self::count(['conditions' => $conditions]);
    }


    /**
     * Метод сохраняет новость из данных, предоставленных админом ленты
     *
     * @param $data
     * @param bool $createEvent
     * @return bool
     */
    public static function saveNewsByAdmin($data, $createEvent = true)
    {
        if ((isset($data['link'])) && (!empty($data['link'])) && (isset($data['title'])) && (!empty($data['title']))) {
            if (self::doesNewsExists($data['title'], $data['link'])) {
                return false;
            }
        }
        $news = News::create($data);
        $news->created = date('Y-m-d H:i:s');
        if (isset($data['file'])) {
            $news->imageurl = News::resize($data['file']);
        }

        if (preg_match('@youtube.com\/embed\/(.*?)"@', $news->short, $matches)) {
            // расшаривание с ютюба
            $youtubeUrl = 'https://www.youtube.com/watch?v=' . $matches[1];
            try {
                $context  = stream_context_create();
                if ($html = file_get_contents($youtubeUrl, false, $context)) {
                    if (preg_match('@property="og:image" content="(.*?)">@', $html, $matches)) {
                        $news->og_image = $matches[1];
                    }
                    if (preg_match('@property="og:title" content="(.*?)">@', $html, $matches)) {
                        $news->og_title = $matches[1];
                    }
                    if (preg_match('@property="og:description" content="(.*?)">@', $html, $matches)) {
                        $news->og_description = $matches[1];
                    }
                }
            } catch (\Exception $e) {
            }
        }

        if (preg_match('@player.vimeo.com\/video\/(.*?)"@', $news->short, $matches)) {
            // расшаривание с ютюба
            $vimeoUrl = 'https://vimeo.com/' . $matches[1];
            try {
                $context  = stream_context_create();
                if ($html = file_get_contents($vimeoUrl, false, $context)) {
                    if (preg_match('@property="og:image" content="(.*?)">@', $html, $matches)) {
                        $news->og_image = $matches[1];
                    }
                    if (preg_match('@property="og:title" content="(.*?)">@', $html, $matches)) {
                        $news->og_title = $matches[1];
                    }
                    if (preg_match('@property="og:description" content="(.*?)">@', $html, $matches)) {
                        $news->og_description = $matches[1];
                    }
                }
            } catch (\Exception $e) {
            }
        }

        $news->admin = 1;
        if ($result = $news->save()) {
            if ((!$news->isBanner) and ($createEvent)) {
                Event::createEventNewsAdded($news->id, 0, $news->created);
                $result = Event::first(['conditions' => ['news_id' => $news->id]]);
                if ($news->tags == 'Goворит Designer') {
                    Task::createNewTask($result->id, 'postNewsToSocial', 10 * MINUTE);
                    Task::createNewTask($result->id, 'postNewsToSocialDelayed', HOUR);
                }
            }
        }
        return $result;
    }

    /**
     * Метод определяет, является ли новость коубом
     *
     * @param $record
     * @return bool
     */
    public function isCoub($record)
    {
        return (bool) preg_match('#<iframe src="//coub.com#', $record->short);
    }
}
