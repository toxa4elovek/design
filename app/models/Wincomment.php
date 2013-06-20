<?php

namespace app\models;

class Wincomment extends \app\models\AppModel {

    public $belongsTo = array('Solution', 'User');

    //public static $fileModel = 'app\models\Pitchfile';

    protected static $_behaviors = array(
        'UploadableWincomment'
    );

    public static $attaches = array('file' => array(
        /*'validate' => array('uploadedOnly' => true),*/
        'moveFile' => array('preserveFileName' => true, 'path' => '/webroot/files/'),
        /*'setPermission' => array('mode' => 0766),*/
    ));

    public static function createComment($data) {
        return static::_filter(__FUNCTION__, $data, function($self, $params) {
            $comment = $self::create();
            $comment->set($params);
            $comment->created = date('Y-m-d H:i:s');
            $comment->save();
            $params['id'] = $comment->id;
            return $params;
        });
    }

}