<?php

namespace app\models;

class Uploadnonce extends \app\models\AppModel
{

    protected static $_behaviors = [
        'UploadableSolutionNonce'
    ];

    public static $attaches = ['solution' => [
        'validate' => ['uploadedOnly' => true],
        'moveFile' => ['preserveFileName' => false, 'path' => '/webroot/solutions/'],
        'setPermission' => ['mode' => 0644],
        'processImage' => [
        ],
    ]];

    public static function getNonce()
    {
        $nonce = self::create();
        $nonce->nonce = uniqid();
        while (self::count(['conditions' => ['nonce' => $nonce->nonce]]) > 0) {
            $nonce->nonce = uniqid();
        }
        $nonce->save();
        return $nonce->nonce;
    }

    public static function uploadFile($formdata)
    {
        $data = [
            'solution' => $formdata['solution'],
            'position' => $formdata['fileposition'],
        ];
        if ($nonce = self::first(['conditions' => ['nonce' => $formdata['uploadnonce']]])) {
            return $nonce->save($data);
        }
        return false;
    }
}
