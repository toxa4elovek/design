<?php

namespace app\models;

use app\extensions\storage\Rcache;

/**
 * Class Tag
 * Класс для взаимодействия с таблице тегов
 * и промежуточной таблицой теги-решения
 *
 * @package app\models
 */
class Tag extends AppModel
{

    /**
     * @var array связь
     */
    public $hasMany = ['Solutiontag'];

    /**
     * Словарь соответствия латинских ключей и русских видов деятельности
     *
     * @var array
     */
    private static $industryDictionary = [
        'realty' => 'Недвижимость / Строительство',
        'auto' => 'Автомобили / Транспорт',
        'finances' => 'Финансы / Бизнес',
        'food' => 'Еда / Напитки',
        'adv' => 'Реклама / Коммуникации',
        'tourism' => 'Туризм / Путешествие',
        'sport' => 'Спорт',
        'sci' => 'Образование / Наука',
        'fashion' => 'Красота / Мода',
        'music' => 'Развлечение / Музыка',
        'culture' => 'Искусство / Культура',
        'animals' => 'Животные',
        'children' => 'Дети',
        'security' => 'Охрана / Безопасность',
        'health' => 'Медицина / Здоровье',
        'it' => 'Компьютеры / IT'];

    /**
     * Метод добавления тегов к решению через внешние запросы
     *
     * @param $formdata
     * @param $solutionId
     */
    public static function add($formdata, $solutionId)
    {
        if (isset($formdata['tags']) && (is_array($formdata['tags']))) {
            self::__bulkArraySave($formdata['tags'], $solutionId);
        }
        if ((isset($formdata['job-type'])) && (is_array($formdata['job-type']))) {
            self::__jobTypesSave($formdata['job-type'], $solutionId);
        }
    }

    /**
     * Приватный метод для сохранения видов деятельности как тегов
     *
     * @param $tagsList
     * @param $solutionId
     */
    private static function __jobTypesSave($tagsList, $solutionId)
    {
        $filteredTags = array_intersect_key(self::$industryDictionary, array_flip($tagsList));
        $saveTag = function ($tag, $solutionId) {
            Tag::saveSolutionTag($tag, $solutionId);
        };
        foreach ($filteredTags as $tagString) {
            $multiTag = explode('/', $tagString);
            if ((is_array($multiTag)) && (count($multiTag) > 1)) {
                foreach ($multiTag as $value) {
                    $saveTag($value, $solutionId);
                }
            } else {
                $saveTag($multiTag[0], $solutionId);
            }
        }
    }

    /**
     * Приватный метод сохранения тегов из списка
     *
     * @param $tagsList
     * @param $solutionId
     */
    private static function __bulkArraySave($tagsList, $solutionId)
    {
        foreach ($tagsList as $tag) {
            Tag::saveSolutionTag($tag, $solutionId);
        }
    }

    /**
     * Метод сохраняет указанный тег $string для решения с айди $solutionId
     *
     * @param $string
     * @param $solutionId
     * @return object
     */
    public static function saveSolutionTag($string, $solutionId)
    {
        if (!Tag::isTagExists($string)) {
            Tag::saveTag($string);
        }
        $solutionTag = Solutiontag::create([
            'tag_id' => Tag::getTagId($string),
            'solution_id' => $solutionId
        ]);
        $solutionTag->save();
        $cacheKey = 'tags_for_solutions_' . $solutionId;
        Rcache::delete($cacheKey);
        return $solutionTag;
    }

    /**
     * Метод удаляет указанный тег $string для решени с айди $solutionId
     *
     * @param $string
     * @param $solutionId
     * @return bool
     */
    public static function removeTag($string, $solutionId)
    {
        if (!Tag::isTagExists($string)) {
            Tag::saveTag($string);
        }
        if ($result = Solutiontag::remove(['tag_id' => [
            'tag_id' => Tag::getTagId($string),
            'solution_id' => $solutionId
        ]])) {
            $cacheKey = 'tags_for_solutions_' . $solutionId;
            Rcache::delete($cacheKey);
        }
        return true;
    }

    /**
     * Метод проверяет, существует ли в базе тегов аргумент
     *
     * @param $string
     * @return bool
     */
    public static function isTagExists($string)
    {
        return (bool) self::count(['conditions' => ['name' => trim($string)]]);
    }

    /**
     * Метод сохраняет и возвращяет объект
     * (или просто возвращяет объект, если тег уже есть в базе)
     *
     * @param $string
     * @return object
     */
    public static function saveTag($string)
    {
        if (!$tag = self::first(['conditions' => ['name' => trim($string)]])) {
            $tag = self::create(['name' => trim($string)]);
            $tag->save();
        }
        return $tag;
    }

    /**
     * Метод возващяет айди тега или false, если тега в базе нет
     *
     * @param $string
     * @return bool
     */
    public static function getTagId($string)
    {
        $id = false;
        if ($tag = self::first(['fields' => ['id'], 'conditions' => ['name' => trim($string)]])) {
            $id = $tag->id;
        }
        return $id;
    }

    /**
     * Метод возвращяет все теги с подстрокой $string
     *
     * @param $string
     * @param bool $cleanCache - нужно ли очистить теги
     * @return mixed
     */
    public static function getSuggest($string, $cleanCache = false)
    {
        $cacheKey = 'suggest_tags_' . $string;
        if ($cleanCache) {
            Rcache::delete($cacheKey);
        }
        if (!$resultData = Rcache::read($cacheKey)) {
            $tags = Tag::all(['conditions' => ['name' => ['LIKE' => $string . '%']]]);
            $resultData = $tags->data();
            Rcache::write($cacheKey, $resultData, '+2 hours');
        }
        return $resultData;
    }


    /**
     * Метод возвращяет самые популярные теги
     *
     * @param $count
     * @return array|bool|mixed
     */
    public static function getPopularTags($count)
    {
        $sort_tags = Rcache::read('sort_tags');
        if (empty($sort_tags)) {
            $solutionTags = Solutiontag::all([
                'fields' => ['tag_id', 'count(id) AS total_count'],
                'group' => ['tag_id'],
                'order' => ['total_count' => 'desc'],
                'limit' => $count
            ]);
            $sort_tags = [];
            foreach ($solutionTags as $solutionTag) {
                $tag = self::first($solutionTag->tag_id);
                $sort_tags[$tag->name] = (int) $solutionTag->total_count;
            }
            Rcache::write('sort_tags', $sort_tags, '+1 hour');
        }
        return $sort_tags;
    }
}
