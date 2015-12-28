<?php
namespace app\models;

use \app\models\User;
use \app\models\Pitch;

use \lithium\storage\Session;

class Promocode extends \app\models\AppModel {

    public $belongsTo = array('User', 'Pitch');

    public static function __init() {
        parent::__init();
        self::applyFilter('find', function($self, $params, $chain){
            $results = $chain->next($self, $params, $chain);
            if($params['type'] == 'all'):
                foreach($results as $code){
                    $code->humanPitch = $code->pitch_id;
                    if(is_null($code->humanPitch)) {
                        $code->humanPitch = 'не использован';
                    }else {
                        $pitch = Pitch::first($code->pitch_id);
                        $code->humanPitch = '<a target="blank" href="/pitches/edit/' . $pitch->id . '">' . $pitch->title . '</a>';
                    }
                    switch($code->type):
                        case 'pinned': $code->humanType = 'прокачать бриф'; break;
                        case 'custom_discount': $code->humanType = 'произвольная скидка'; break;
                    endswitch;
                    if(!is_null($code->user_id)) {
                        $user = User::first($code->user_id);
                        $code->humanUser = '<a target="blank" href="https://www.godesigner.ru/users/view/' . $user->id . '">' . $user->first_name . ' ' . $user->last_name . '</a>';
                    }else {
                        $code->humanUser = 'не привязан';
                    }
                }
            endif;
            return $results;
        });
    }

    /**
     * Метод генерирует случайно/ строчку, которая еще не используется в качестве промокода
     *
     * @param int $length
     * @return string
     */
    public static function generateToken($length = 4) {
        $exists = true;
        while($exists == true) {
            $randomString = uniqid();
            $token = substr($randomString, strlen($randomString) - $length, $length);
            if(!self::first(array(
                'fields' => array('id'),
                'conditions' => array('code' => $token)))) {
                $exists = false;
            }
        }
        return $token;
    }

    public static function createPromocode($userId) {
        $data = array(
            'code' => self::generateToken(),
            'type' => 'pinned',
            'starts' => date('Y-m-d H:i:s'),
            'expires' => date('Y-m-d H:i:s', time() + (2 * MONTH)),
            'user_id' => $userId
        );
        $newCode = self::create($data);
        $newCode->save();
        return $newCode->code;
    }

    public static function checkPromocode($codeString) {
        $result = 'false';
        if($code = Promocode::first(array('conditions' => array(
            'code' => $codeString

        )))) {
            if(($code->pitch_id != null) && (!$code->isMultiUse())) {
                return $result;
            }
            if((time() < strtotime($code->starts)) || (time() > strtotime($code->expires))) {
                return $result;
            }
            if(!$code->isMultiUse()) {
                $code->user_id = Session::read('user.id');
                $code->save();
            }
            $result = $code->data();
        }
        return $result;
    }

    /**
     * Метод возвращяет номера устаревших промокодов для их удаления
     *
     * @return mixed
     */
    public static function getOldPromocodes() {
        return self::all(array(
            'fields' => array('id'),
            'conditions' => array(
                'type' => 'pinned',
                'pitch_id' => null,
                'expires' => array(
                    '<' => date('Y-m-d H:i:s'),
                )
            ),
        ));
    }

    /**
     * Метод определяет, являетсял и запись промокода мультиисппользуемой
     *
     * @param $record
     * @return bool
     */
    public function isMultiUse($record) {
        $multiUse = array(
            'discount',
            'misha',
            'in_twain'
        );
        if((in_array($record->type, $multiUse)) || ($record->multi)){
            return true;
        }
        return false;
    }

}