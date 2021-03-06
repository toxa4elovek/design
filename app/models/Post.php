<?php

namespace app\models;

use lithium\storage\Session;

/**
 * Class Post
 *
 * Класс для работы с СМС сообщениями
 * @package app\models
 */
class Post extends AppModel
{

    public $belongsTo = ['User'];

    private static function __replaceNbspWithNonBreakingSpace($result)
    {
        if (get_class($result) === 'lithium\data\entity\Record') {
            $result->title = str_replace('&nbsp;', ' ', $result->title);
            return $result;
        }
        foreach ($result as $item) {
            $item->title = str_replace('&nbsp;', ' ', $item->title);
        }
        return $result;
    }

    /**
     * Write Common Post Tags here
     *
     * @var array
     */
    private static $commonTags = [
        'заказчикам',
        'дизайнерам',
        'фриланс',
        'интервью',
        'команда go',
        'герой месяца',
        'cовет в обед',
        'топ 10',
        'фриланс под пальмами',
    ];

    public static function __init()
    {
        parent::__init();
        self::applyFilter('find', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if (($params['type'] === 'all') || ($params['type'] === 'first')) {
                $result = $self::__replaceNbspWithNonBreakingSpace($result);
            }
            return $result;
        });
    }

    /**
     * Метод увеличивает счетчик просмотров для статьи под номером $id
     *
     * @param $id
     * @return int
     */
    public static function increaseCounter($id)
    {
        $answer = self::first($id);
        $answer->views += 1;
        $answer->save();
        return $answer->views;
    }

    /**
     * Gets the common tags array
     *
     * @return array
     */
    public static function getCommonTags()
    {
        $res = [];
        if (count(self::$commonTags) > 0) {
            foreach (self::$commonTags as $tag) {
                $res[] = '"' . $tag . '"';
            }
        }

        return $res;
    }

    /**
     * Parsing specified post tags field
     *
     * @param string $tags
     * @return boolean|multitype:string
     */
    public static function parseExistingTags($tags = null)
    {
        if (empty($tags)) {
            return false;
        }
        $res = [];
        foreach (explode('|', $tags) as $tag) {
            $res[] = '"' . $tag . '"';
        }

        return $res;
    }

    /**
     * Помечает пост как тот, что редактируется пользователем
     *
     * @param $postId
     * @param $userId
     * @return bool
     */
    public static function lock($postId, $userId)
    {
        $md5 = md5($postId . $userId);
        if ($post = self::first($postId)) {
            if (empty($post->lock) || ($post->lock == $md5)) {
                $post->lock = $md5;
                $post->save();
                return true;
            }
        }
        return false;
    }

    /**
     * Помечает пост как доступный для редактирования
     *
     * @param $postId
     * @return bool
     */
    public static function unlock($postId)
    {
        if ($post = self::first($postId)) {
            if (empty($post->lock)) {
                return false;
            } else {
                $post->lock = '';
                $post->save();
                return true;
            }
        }
        return false;
    }

    /**
     * Обновляет время последнего редактирования поста
     *
     * @param $postId
     * @return bool
     */
    public static function updateLastEditTime($postId)
    {
        if ($post = self::first($postId)) {
            $post->lastEditTime = date('Y-m-d H:i:s');
            return $post->save();
        }
        return false;
    }

    /**
     * Метод проверяет, залочен ли пост пользователем $userId
     *
     * @param $postId
     * @param $userId
     * @return bool
     */
    public static function isLockedByMe($postId, $userId)
    {
        $md5 = md5($postId . $userId);
        if ($post = self::first($postId)) {
            if (!empty($post->lock) && ($post->lock == $md5)) {
                return true;
            }
        }
        return false;
    }

    public static function mb_ucfirst($string, $enc = 'UTF-8')
    {
        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) .
        mb_substr($string, 1, mb_strlen($string, $enc), $enc);
    }
}
