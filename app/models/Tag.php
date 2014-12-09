<?php

namespace app\models;

use app\models\Solutiontag;

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
        'health' => 'Медицина / Здоровье');

    public function add($formdata, $solution_id) {
        $tags_list = Tag::all();
        if ($tags_list) {
            $tags_list = $tags_list->data();
        }
        foreach ($formdata['tags'] as $v) {
            if ($tag_id = in_array_r($v, $tags_list)) {
                Solutiontag::create(array(
                    'tag_id' => $tag_id,
                    'solution_id' => $solution_id
                ))->save();
            } else {
                $tags = Tag::create(array(
                            'name' => trim($v)
                ));
                $tags->save();
                Solutiontag::create(array(
                    'tag_id' => $tags->id,
                    'solution_id' => $solution_id
                ))->save();
            }
        }
        if (is_array($formdata['job-type'])) {
            $filteredTags = array_intersect_key(self::$job_types, array_flip($formdata['job-type']));
            if (is_array($filteredTags)) {
                foreach ($filteredTags as $v) {
                    $multi_tag = explode('/', $v);
                    if (is_array($multi_tag)) {
                        foreach ($multi_tag as $value) {
                            if ($temp = in_array_r(trim($value), $tags_list)) {
                                $tag_id = $temp;
                            } else {
                                $tags = Tag::create(array(
                                            'name' => trim($value)
                                ));
                                $tags->save();
                                $tag_id = $tags->id;
                            }
                            Solutiontag::create(array(
                                'tag_id' => $tag_id,
                                'solution_id' => $solution_id
                            ))->save();
                        }
                    } else {
                        if ($temp = in_array_r(trim($value), $tags_list)) {
                            $tag_id = $temp;
                        } else {
                            $tags = Tag::create(array(
                                        'name' => trim($v)
                            ));
                            $tags->save();
                            $tag_id = $tags->id;
                        }
                        Solutiontag::create(array(
                            'tag_id' => $tag_id,
                            'solution_id' => $solution_id
                        ))->save();
                    }
                }
            }
        }
    }

}
