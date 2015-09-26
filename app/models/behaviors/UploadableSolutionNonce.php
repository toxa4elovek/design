<?php

namespace app\models\behaviors;

use \app\models\File;
use \app\models\Solutionfile;

/**
 * ��������� UploadableImage
 *
 * @description	��������� ����������� ��������� ����� ������ � ����� ������.
 *
 *
 */
class UploadableSolutionNonce extends \app\models\behaviors\UploadableImage{

    public static $defaults = array(
        'validate' => array('uploadedOnly' => true),
        'moveFile' => array('preserveFileName' => false, 'path' => '/resources/tmp/'),
        'setPermission' => array('mode' => 0644),
        'processImage' => array(),
    );

    public static $fileModel = 'app\models\Solutionfile';

    protected function _init(){
        parent::_init();
        static::$name = __CLASS__;
        static::$defaults['processImage'] = Solutionfile::getParams();
    }
}