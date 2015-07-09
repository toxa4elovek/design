<?php

namespace app\models;

class Post extends \app\models\AppModel {

    public $belongsTo = array('User');

    /**
     * Write Common Post Tags here
     *
     * @var array
     */
    private static $commonTags = array(
        'заказчикам',
        'дизайнерам',
        'фриланс',
        'интервью',
        'команда go',
        'герой месяца',
        'cовет в обед',
        'топ 10',
        'фриланс под пальмами',
    );

    public static function increaseCounter($id) {
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
    public static function getCommonTags() {
        $res = array();
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
    public static function parseExistingTags($tags = null) {
        if (empty($tags)) {
            return false;
        }
        $res = array();
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
    public static function lock($postId, $userId) {
        $md5 = md5($postId . $userId);
        if($post = self::first($postId)) {
            if(empty($post->lock) || ($post->lock == $md5)) {
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
    public static function unlock($postId) {
        if($post = self::first($postId)) {
            if(empty($post->lock)) {
                return false;
            }else {
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
    public static function updateLastEditTime($postId) {
        if($post = self::first($postId)) {
            $post->lastEditTime = date('Y-m-d H:i:s');
            return $post->save();
        }
        return false;
    }
}
