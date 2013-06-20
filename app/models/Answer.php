<?php
namespace app\models;

class Answer extends \app\models\AppModel {

    public static $questioncategory_id = array(
        '1' => 'Общие вопросы',
        '2' => 'Помощь заказчикам',
        '3' => 'Помощь дизайнерам',
        '4' => 'Оплата и денежные вопросы'
    );

    public static function increaseCounter($id) {
        $answer = self::first($id);
        $answer->hits += 1;
        $answer->save();
        return $answer->hits;
    }




}