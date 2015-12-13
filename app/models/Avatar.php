<?php

namespace app\models;

use app\extensions\storage\Rcache;

/**
 * Class Avatar
 *
 * Метод для управления аватарами пользователя
 * @package app\models
 */
class Avatar extends AppModel
{

    public static function __init()
    {
        parent::__init();
        self::applyFilter('find', function ($self, $params, $chain) {
            if ($params['type'] === 'all') {
                $conditions = $params['options']['conditions'];
                if (isset($conditions['model_id'])) {
                    $cacheKey = 'avatars_' . $conditions['model_id'];
                    $result = Rcache::read($cacheKey, function () use ($self, $params, $chain) {
                        $result = $chain->next($self, $params, $chain);
                        $result->data();
                        return $result;
                    }, '+7 day');
                }
            }
            if ($params['type'] === 'count') {
                $conditions = $params['options']['conditions'];
                if (isset($conditions['model_id'])) {
                    $cacheKey = 'avatars_' . $conditions['model_id'];
                    if (!$result = Rcache::read($cacheKey)) {
                        $result = $chain->next($self, $params, $chain);
                    } else {
                        $result = count($result);
                    }
                }
            }
            if (!isset($result)) {
                $result = $chain->next($self, $params, $chain);
            }
            return $result;
        });

        self::applyFilter('delete', function ($self, $params, $chain) {
            if (isset($params['entity'])) {
                $record = $params['entity'];
                $cacheKey = 'avatars_' . $record->model_id;
                if (Rcache::exists($cacheKey)) {
                    Rcache::delete($cacheKey);
                }
            }
            return $chain->next($self, $params, $chain);
        });
    }

    /**
     * Метод возвращяет аватарку пользователя из фейсбука
     *
     * @param $userRecord
     * @return array
     */
    public static function getFbAvatar($userRecord)
    {
        $facebookUid = $userRecord->facebook_uid;
        $userPicFile = file_get_contents('http://graph.facebook.com/' . $facebookUid . '/picture?type=large');
        return self::__setImageAsAvatarForUser($userRecord, $userPicFile);
    }

    /**
     * Метод возвращяет аватарку пользователя из вк
     *
     * @param $userRecord
     * @return array
     */
    public static function getVkAvatar($userRecord)
    {
        $imageUrl = $userRecord->vk_image_link;
        $data = json_decode(file_get_contents($imageUrl), true);
        $userPicFile = file_get_contents($data['response'][0]['photo_max_orig']);
        return self::__setImageAsAvatarForUser($userRecord, $userPicFile);
    }

    /**
     * Метод-помощник, которые привязывает файл в качестве аватарки к пользователю
     *
     * @param $userRecord
     * @param $userPicFile
     * @return null
     */
    private static function __setImageAsAvatarForUser($userRecord, $userPicFile)
    {
        $tmp = stream_get_meta_data(tmpfile())['uri'];
        file_put_contents($tmp, $userPicFile);
        $imageData = getimagesize($tmp);
        switch ($imageData['mime']) {
            case 'image/gif':
                $filename = uniqid() . '.gif';
                break;
            case 'image/jpeg':
                $filename = uniqid() . '.jpg';
                break;
            case 'image/png':
                $filename = uniqid() . '.png';
                break;
            default:
                return null;
                break;
        }
        self::removeAllAvatarsOfUser($userRecord->id);
        $data = ['avatar' => ['name' => $filename, 'tmp_name' => $tmp, 'error' => 0]];
        $userRecord->save($data, ['validate' => false]);
        return self::all(['conditions' => ['model_id' => $userRecord->id]]);
    }

    /**
     * Метод удаляет все записи и файлы аватаров для пользователя
     *
     * @param $userId
     */
    public static function removeAllAvatarsOfUser($userId)
    {
        $avatars = self::all(['conditions' => ['model_id' => $userId]]);
        $cacheKey = 'avatars_' . $userId;
        if (Rcache::exists($cacheKey)) {
            Rcache::delete($cacheKey);
        }
        foreach ($avatars as $avatar) {
            if (file_exists($avatar->filename)) {
                unlink($avatar->filename);
            }
            $avatar->delete();
        }
    }
}
