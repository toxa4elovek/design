<?php

namespace app\models\behaviors;

use \app\models\File;

/**
 * ��������� UploadableImage
 *
 * @description	��������� ����������� ��������� ����� ������ � ����� ������.
 *
 *
 */
class UploadableAvatar extends \app\models\behaviors\UploadableFile{

    public static $defaults = array(
        'moveFile' => array('preserveFileName' => false, 'path' => '/resources/tmp/'),
        'setPermission' => array('mode' => 0644),
        'processImage' => array(),
    );

    public static $fileModel = 'app\models\Avatar';

    protected function _init(){
        parent::_init();
        static::$name = __CLASS__;
    }

    public function afterDelete($result){
        if($result){
            $model = $this->_model;
			foreach($model::$attaches as $key => &$attachInfo){
                if(isset($attachInfo['deleteId'])){
                    $fileModel = static::$fileModel;
                    $filerecord = $fileModel::find('first', array('conditions' => array(
                        'model' => $model,
                        'model_id' => $attachInfo['deleteId'],
                        'filekey' => $key,
                    )));
                    if(!is_null($filerecord)){
                        if(file_exists($filerecord->filename)){
                            unlink($filerecord->filename);
                        }
                        $filerecord->delete();
                    }
                    foreach($attachInfo['processImage'] as $resizeName => $resizeOptions){
                        $filerecord = $fileModel::find('first', array('conditions' => array(
                            'model' => $model,
                            'model_id' => $attachInfo['deleteId'],
                            'filekey' => $key . '_' . $resizeName,
                        )));
                        if(!is_null($filerecord)){
                            if(file_exists($filerecord->filename)){
                                unlink($filerecord->filename);
                            }
                            $filerecord->delete();
                        }
                    }

                }
            }
		}
        return $result;
    }

    public function afterSave($result) {
        $model = $this->_model;
        if(!isset(static::$_storage[$model]['record'])) {
            return $result;
        }
        $record = static::$_storage[$model]['record'];
        foreach(static::$_storage[$model]['attaches'] as $key => $uploadedFile) {
            $attachRules = $uploadedFile['attachInfo'];

            if((!isset($model::$attaches[$key])) || (!is_array($model::$attaches[$key]))){
                $userHandlerOptions = array();
            }else{
                $userHandlerOptions = $model::$attaches[$key];
            }
            $handlersSet =  $userHandlerOptions + static::$defaults;
            static::$_methodFilters[__CLASS__] = array();

            foreach($handlersSet as $handlerName => $options){
                if((is_int($handlerName)) && (is_string($options))){
                    $handlerName = $options;
                }

                $handlerClassName =  ucfirst($handlerName).'Handler';
                $handlerClassPath = 'app\models\behaviors\handlers\\';
                $handlerObject = $handlerClassPath . $handlerClassName;
                if(class_exists($handlerObject)) {
                    static::$name = __CLASS__;
                    $handlerObject::useHandler(static::$name);
                }
            }
            $params = compact('model', 'key', 'attachRules', 'record', 'uploadedFile');
            static::_filter('afterSave', $params, function($self, $params) {
                if((isset($params['uploadedFile']['data'])) && (isset($params['uploadedFile']['data']['newname']))){

                    $conditions = array('model' => $params['model'], 'model_id' => $params['record']->id, 'filekey' => $params['key'], 'filename' => $params['uploadedFile']['data']['newname']);
                    $fileModel = $self::$fileModel;
                    $data = array('filename' => $params['uploadedFile']['data']['newname']) + $conditions;
                    if($existingRow = $fileModel::first(array('conditions' => $conditions))) {
                        $existingRow->set($data);
                        $existingRow->save();
                    }else {
                        $fileModel::create($data)->save();
                    }
                }
                return true;
            });

        }
        return $result;
    }

}