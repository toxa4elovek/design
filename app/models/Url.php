<?php

namespace app\models;

class Url extends \app\models\AppModel {

    public function check($full){
        return Url::first(array('conditions' => array('full' => $full)));
    }

    public function get($short) {
        return Url::first(array('conditions' => array('short' => $short)));
    }

    public function createNew($full) {
        if($existing = Url::check($full)) {
            return $existing;
        }else{
            $new = Url::create();
            $new->full = $full;
            $new->short = Url::generateUrl();
            $new->save();
            return $new;
        }
    }

    public function generateUrl(){
        return substr(md5(rand().rand()), 0, 6);
    }

    public function view($full) {
        return Url::createNew($full)->short;
    }

}