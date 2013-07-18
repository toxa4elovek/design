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

    /**
     * Moving Answer one position up
     *
     * @param number $id Answer Id
     * @return array Result
     */
    public static function moveUp($id) {
        $res = array();
        $res['error'] = false;
        if ($answer = self::first($id)) {
            $currentOrder = $answer->display_order;
            if ($currentOrder == 1) {
                $res['position'] = 1;
                $res['error'] = 'alreadyTop';
                return $res;
            }
            if ($prev = self::first(array('conditions' => array('display_order' => $currentOrder - 1, 'questioncategory_id' => $answer->questioncategory_id)))) {
                $answer->display_order = $currentOrder - 1;
                $prev->display_order = $currentOrder;
                $answer->save();
                $prev->save();
                $res['position'] = $currentOrder - 1;
                return $res;
            }
        }
        $res['error'] = 'wrongId';
        return $res;
    }

    /**
     * Moving Answer one position down
     *
     * @param number $id Answer Id
     * @return array Result
     */
    public static function moveDown($id) {
        $res = array();
        $res['error'] = false;
        if ($answer = self::first($id)) {
            $countInCategory = self::find('count', array('conditions' => array('questioncategory_id' => $answer->questioncategory_id)));
            $currentOrder = $answer->display_order;
            if ($currentOrder == $countInCategory) {
                $res['position'] = $currentOrder;
                $res['error'] = 'alreadyBottom';
                return $res;
            }
            if ($next = self::first(array('conditions' => array('display_order' => $currentOrder + 1, 'questioncategory_id' => $answer->questioncategory_id)))) {
                $answer->display_order = $currentOrder + 1;
                $next->display_order = $currentOrder;
                $answer->save();
                $next->save();
                $res['position'] = $currentOrder + 1;
                return $res;
            }
        }
        $res['error'] = 'wrongId';
        return $res;
    }

    /**
     * Moving Answer to thw top position
     *
     * @param number $id Answer Id
     * @return array Result
     */
    public static function moveTop($id) {
        $res = array();
        $res['error'] = false;
        if ($answer = self::first($id)) {
            $currentOrder = $answer->display_order;
            if ($currentOrder == 1) {
                $res['position'] = 1;
                $res['error'] = 'alreadyTop';
                return $res;
            }
            $answersBefore = self::find('all', array(
                'conditions' => array(
                    'questioncategory_id' => $answer->questioncategory_id,
                    'display_order' => array('<' => $currentOrder),
                ),
            ));
            foreach ($answersBefore as $updatable) {
                $updatable->display_order++;
                $updatable->save();
            }
            $answer->display_order = 1;
            $answer->save();
            $res['position'] = 1;
            return $res;
        }
        $res['error'] = 'wrongId';
        return $res;
    }

    /**
     * Moving Answer to the bottom position
     *
     * @param number $id Answer Id
     * @return array Result
     */
    public static function moveBottom($id) {
        $res = array();
        $res['error'] = false;
        if ($answer = self::first($id)) {
            $countInCategory = self::find('count', array('conditions' => array('questioncategory_id' => $answer->questioncategory_id)));
            $currentOrder = $answer->display_order;
            if ($currentOrder == $countInCategory) {
                $res['position'] = $currentOrder;
                $res['error'] = 'alreadyBottom';
                return $res;
            }
            $answersAfter = self::find('all', array(
                'conditions' => array(
                    'questioncategory_id' => $answer->questioncategory_id,
                    'display_order' => array('>' => $currentOrder),
                ),
            ));
            foreach ($answersAfter as $updatable) {
                $updatable->display_order--;
                $updatable->save();
            }
            $answer->display_order = $countInCategory;
            $answer->save();
            $res['position'] = $countInCategory;
            return $res;
        }
        $res['error'] = 'wrongId';
        return $res;
    }
}