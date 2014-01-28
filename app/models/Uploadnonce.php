<?php

namespace app\models;

class Uploadnonce extends \app\models\AppModel {

    protected static $_behaviors = array(
        'UploadableSolutionNonce'
    );

    public static $attaches = array('solution' => array(
        'validate' => array('uploadedOnly' => true),
        'moveFile' => array('preserveFileName' => false, 'path' => '/webroot/solutions/'),
        'setPermission' => array('mode' => 0644),
        'processImage' => array(
        ),
    ));

    public static function getNonce() {
        $nonce = self::create();
        $nonce->nonce = uniqid();
        while (self::count(array('conditions' => array('nonce' => $nonce->nonce))) > 0) {
            $nonce->nonce = uniqid();
        }
        $nonce->save();
        return $nonce->nonce;
    }

    public static function uploadFile($formdata) {
        $data = array(
            'solution' => $formdata['solution'],
            'position' => $formdata['fileposition'],
        );
        if ($nonce = self::first(array('conditions' => array('nonce' => $formdata['uploadnonce'])))) {
            return $nonce->save($data);
        }
        return false;
    }
}
