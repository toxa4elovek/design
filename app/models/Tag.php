<?php

namespace app\models;

use app\models\Solutiontag;
use app\extensions\storage\Rcache;

class Tag extends \app\models\AppModel {

    public $hasMany = array('Solutiontag');
    private static $job_types = array(
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
        'childs' => 'Дети',
        'security' => 'Охрана / Безопасность',
        'health' => 'Медицина / Здоровье',
        'it' => 'Компьютеры / IT');

    public static function add($formdata, $solution_id) {
        if(isset($formdata['tags']) && (is_array($formdata['tags']))) {
            foreach ($formdata['tags'] as $v) {
                Tag::saveSolutionTag($v, $solution_id);
            }
        }
        if((isset($formdata['job-type'])) && (is_array($formdata['job-type']))) {
            $filteredTags = array_intersect_key(self::$job_types, array_flip($formdata['job-type']));
            if (is_array($filteredTags)) {
                foreach ($filteredTags as $v) {
                    $multi_tag = explode('/', $v);
                    if (is_array($multi_tag)) {
                        foreach ($multi_tag as $value) {
                            Tag::saveSolutionTag($value, $solution_id);
                        }
                    } else {
                        Tag::saveSolutionTag($value, $solution_id);
                    }
                }
            }
        }
    }

    /**
     * Метод сохраняет указанный тег $string для решения с айди $solutionId
     *
     * @param $string
     * @param $solutionId
     * @return object
     */
    public static function saveSolutionTag($string, $solutionId) {
        if (!Tag::isTagExists($string)) {
            Tag::saveTag($string);
        }
        $solutionTag = Solutiontag::create(array(
            'tag_id' => Tag::getTagId($string),
            'solution_id' => $solutionId
        ));
        $solutionTag->save();
        return $solutionTag;
    }

    /**
     * Метод проверяет, существует ли в базе тегов аргумент
     *
     * @param $string
     * @return bool
     */
    public static function isTagExists($string) {
        return (bool) self::count(array('conditions' => array('name' => trim($string))));
    }

    /**
     * Метод сохраняет и возвращяет объект
     * (или просто возвращяет объект, если тег уже есть в базе)
     *
     * @param $string
     * @return object
     */
    public static function saveTag($string) {
        if(!$tag = self::first(array('conditions' => array('name' => trim($string))))) {
            $tag = self::create(array('name' => trim($string)));
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
    public static function getTagId($string) {
        $id = false;
        if($tag = self::first(array('fields' => array('id'), 'conditions' => array('name' => trim($string))))) {
            $id = $tag->id;
        }
        return $id;
    }

    /**
     * Метод возвращяет все теги с подстрокой $string
     *
     * @param $string
     * @return mixed
     */
    public static function getSuggest($string, $cleanCache = false) {
        $cacheKey = 'suggest_tags_' . $string;
        if($cleanCache) {
            Rcache::delete($cacheKey);
        }
        if (!$tags = Rcache::read($cacheKey)) {
            $tags = Tag::all(array('conditions' => array('name' => array('LIKE' => '%' . $string . '%'))));
            Rcache::write($cacheKey, $tags->data(), '+2 hours');
            return $tags->data();
        }
        return $tags;
    }


    /**
     * Метод возвращяет самые популярные теги
     *
     * @param $count
     * @return array|bool|mixed
     */
    public static function getPopularTags($count) {
        $sort_tags = Rcache::read('sort_tags');
        if (empty($sort_tags)) {
            $tags = Tag::all(array('with' => 'Solutiontag'));
            foreach ($tags as $v) {
                $sort_tags[$v->name] = count($v->solutiontags);
            }
            asort($sort_tags);
            $sort_tags = array_slice($sort_tags, 0, $count);
            Rcache::write('sort_tags', $sort_tags, '+1 hour');
        }
        return $sort_tags;
    }

}
