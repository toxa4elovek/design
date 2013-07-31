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
                    endswitch;
                    if(!is_null($code->user_id)) {
                        $user = User::first($code->user_id);
                        $code->humanUser = '<a target="blank" href="http://www.godesigner.ru/users/view/' . $user->id . '">' . $user->first_name . ' ' . $user->last_name . '</a>';
                    }else {
                        $code->humanUser = 'не привязан';
                    }
                }
            endif;
            return $results;
        });
    }

    public function generateToken($length = 4) {
        $exists = true;
        while($exists == true) {
            $token = substr(md5(rand().rand()), 0, $length);
            if(!self::first(array('conditions' => array('code' => $token)))) {
                $exists = false;
            }
        }
        return $token;
    }

    public function createPromocode($userId) {
        $data = array('code' => self::generateToken(), 'type' => 'pinned', 'expires' => date('Y-m-d H:i:s', time() + (2 * MONTH)), 'user_id' => $userId);
        $newCode = self::create();
        $newCode->set($data);
        $newCode->save();
        return $newCode->code;
    }

    public static function checkPromocode($codeString) {
        $result = 'false';
        if($code = Promocode::first(array('conditions' => array(
            'code' => $codeString

        )))) {
            if(($code->pitch_id != null) && ($code->type != 'discount')) {
                return $result;
            }
            if($code->type != 'discount') {
                $code->user_id = Session::read('user.id');
                $code->save();
            }else {
                if(time() > strtotime($code->expires)) {
                    return $result;
                }
            }
            $result = $code->data();
        }
        return $result;
    }

}