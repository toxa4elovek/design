<?php

namespace app\models\behaviors;

use \app\models\File;
use \app\models\behaviors\handlers\ValidateHandler;
use \app\models\behaviors\handlers\MoveFileHandler;
use \app\models\behaviors\handlers\SetPermissionHandler;

/**
 * Поведение UploadableFile
 *
 * @description	Поведение позволяющие загружать файлы аттачи к любой модели.
 *
 *
 */
class UploadableWincomment extends \app\models\behaviors\ModelBehavior
{


    /**
     * Массив-хранилище загруженных файлов.
     *
     * @description
     * @access protected;
     */
    protected static $_storage = array();

    public static $fileModel = 'app\models\File';
    public static $name = null;

    public static $defaults = array(
        /*'validate' => array('uploadedOnly' => true),*/
        'moveFile' => array('preserveFileName' => false, 'path' => '/resources/tmp/'),
        /*'setPermission' => array('mode' => 0644)*/
    );


    protected static $_handlers = array();
    protected static $_handlersRegistry = array();

    /**
     * Флаг осуществления проверки загрузки файла методом UploadableFile::isUploadedFile()
     * @var boolean
     */
    public static $validateUploadedFile = true;

    protected function _init()
    {
        parent::_init();
        static::$name = __CLASS__;
    }


    /**
     * Коллбэк вызываемый до вызова метода Model::save().
     *
     * @description  Проверяем есть ли в пришедших данных ключи совпадающие с ключами в Model::$_attaches,
     * 				выносим их из пришедших данных во временный контейнер, передаем данные дальше.
     *
     */
    public function beforeSave($params)
    {
        $model = $this->_model;
        if (isset($model::$attaches)) {
            self::__fillStorage();
            $recordObject = $params['entity'];
            $data = $params['data'];
            if (is_null($data)) {
                $data = $recordObject->data();
            }
            foreach ($model::$attaches as $key => $attach) {
                if (is_string($attach)) {
                    $key = $attach;
                    $attach = static::$defaults ;
                } else {
                    $attach = $attach + static::$defaults;
                }
                if (isset($data[$key])) {
                    $recordObject->set(array($key => null));
                    static::$_storage[$model]['attaches'][$key]['data'] = $data[$key];
                    static::$_storage[$model]['record'] = $recordObject;
                }
            }
        }
        return $params;
    }

    public function afterFind($data)
    {
        if (is_null($data)) {
            return $data;
        }
        $getWebUrl = function ($path) {
            if (preg_match('#webroot(.*)#', $path, $matches)) {
                return $matches[1];
            } else {
                return false;
            }
        };
        $getBasename = function ($path) {
            $basename = function ($param, $suffix=null) {
                if ($suffix) {
                    $tmpstr = ltrim(substr($param, strrpos($param, DIRECTORY_SEPARATOR)), DIRECTORY_SEPARATOR);
                    if ((strpos($param, $suffix)+strlen($suffix))  ==  strlen($param)) {
                        return str_ireplace($suffix, '', $tmpstr);
                    } else {
                        return ltrim(substr($param, strrpos($param, DIRECTORY_SEPARATOR)), DIRECTORY_SEPARATOR);
                    }
                } else {
                    return ltrim(substr($param, strrpos($param, DIRECTORY_SEPARATOR)), DIRECTORY_SEPARATOR);
                }
            };
            return $basename($path);
        };
        $attachRecord = function ($fileModel, $record) use ($getWebUrl, $getBasename) {
            $images = $fileModel::all(array('conditions' => array('model_id' => $record->id, 'model' => '\\' . $record->model())));
            $record->images = array();
            $first = true;
            foreach ($images as $value) {
                $value->weburl = $getWebUrl($value->filename);
                if (empty($value->originalbasename)) { // Older files fallback
                    $value->basename = $getBasename($value->filename);
                } else {
                    $value->basename = $getBasename($value->originalbasename);
                }
                if (!isset($record->images[$value->filekey])) {
                    $record->images[$value->filekey] = $value->data();
                } else {
                    if ($first) {
                        $reserve = $record->images[$value->filekey];
                        $record->images[$value->filekey] = array();
                        $record->images[$value->filekey][] = $reserve;
                        $first = false;
                    }
                    $record->images[$value->filekey][] = $value->data();
                }
            }
        };
        $fileModel = static::$fileModel;
        if ((is_object($data)) && (get_class($data) === 'lithium\data\entity\Record')) {
            $attachRecord($fileModel, $data);
        } else {
            if ((is_object($data)) && (get_class($data) === 'lithium\data\collection\RecordSet')) {
                foreach ($data as $record) {
                    $attachRecord($fileModel, $record);
                }
            }
        }
        return $data;
    }

    /**
     * Коллбэк вызываемый после вызова метода Model::save().
     *
     *
     *
     * @param boolean $result record saved
     */
    public function afterSave($result)
    {
        $model = $this->_model;
        if (!isset(static::$_storage[$model]['record'])) {
            return $result;
        }
        $record = static::$_storage[$model]['record'];
        foreach (static::$_storage[$model]['attaches'] as $key => $uploadedFile) {
            $attachRules = $uploadedFile['attachInfo'];

            if ((!isset($model::$attaches[$key])) || (!is_array($model::$attaches[$key]))) {
                $userHandlerOptions = array();
            } else {
                $userHandlerOptions = $model::$attaches[$key];
            }
            $handlersSet =  $userHandlerOptions + static::$defaults;
            static::$_methodFilters[__CLASS__] = array();

            foreach ($handlersSet as $handlerName => $options) {
                if ((is_int($handlerName)) && (is_string($options))) {
                    $handlerName = $options;
                }

                $handlerClassName =  ucfirst($handlerName).'Handler';
                $handlerClassPath = 'app\models\behaviors\handlers\\';
                $handlerObject = $handlerClassPath . $handlerClassName;
                $handlerObject::useHandler(static::$name);

                /*if(isset(static::$_handlers[$handlerName])){
                        $lambda = static::$_handlers[$handlerName];
                        $lambda(static::$name);
                    }*/
            }

            $params = compact('model', 'key', 'attachRules', 'record', 'uploadedFile');
            static::_filter('afterSave', $params, function ($self, $params) {
                if (isset($params['uploadedFile']['data'][0])) {
                    foreach ($params['uploadedFile']['data'] as $file) {
                        if ((isset($file)) && (isset($file['newname']))) {
                            $conditions = array('model' => $params['model'], 'model_id' => $params['record']->id, 'filekey' => $params['key'], 'filename' => $file['newname']);
                            $fileModel = $self::$fileModel;
                            $data = array(
                                'filename' => $file['newname'],
                                'originalbasename' => $file['name'],
                            ) + $conditions;
                            if ($existingRow = $fileModel::first(array('conditions' => $conditions))) {
                                $existingRow->set($data);
                                $existingRow->save();
                            } else {
                                $fileModel::create($data)->save();
                            }
                        }
                    }
                } else {
                    if ((isset($params['uploadedFile']['data'])) && (isset($params['uploadedFile']['data']['newname']))) {
                        $conditions = array('model' => $params['model'], 'model_id' => $params['record']->id, 'filekey' => $params['key'], 'filename' => $params['uploadedFile']['data']['newname']);
                        $fileModel = $self::$fileModel;
                        $data = array(
                            'filename' => $params['uploadedFile']['data']['newname'],
                            'originalbasename' => $params['uploadedFile']['data']['name'],
                        ) + $conditions;
                        if ($existingRow = $fileModel::first(array('conditions' => $conditions))) {
                            $existingRow->set($data);
                            $existingRow->save();
                        } else {
                            $fileModel::create($data)->save();
                        }
                    }
                }
                return true;
            });
        }
        return $result;
    }

    /**
     * Определяет был ли файл загружен через HTTP_POST.
     * Обертка над is_uploaded_file()
     *
     * @param array|string $filedata
     * @return boolean
     */
    public static function isUploadedFile($filedata)
    {
        $result = false;
        if ((is_array($filedata)) && (array_key_exists('tmp_name', $filedata))) {
            $result = is_uploaded_file($filedata['tmp_name']);
        }
        if (is_string($filedata)) {
            $result = is_uploaded_file($filedata);
        }
        return $result;
    }

    public function beforeDelete($params)
    {
        $model = $this->_model;
        foreach ($model::$attaches as $key => &$attachInfo) {
            $attachInfo['deleteId'] = $params['entity']->id;
        }
        return $params;
    }

    public function afterDelete($result)
    {
        if ($result) {
            $model = $this->_model;
            $configuration = static::$_storage[$model];
            foreach ($model::$attaches as $key => &$attachInfo) {
                if (isset($attachInfo['deleteId'])) {
                    $fileModel = static::$fileModel;
                    $filerecord = $fileModel::find('first', array('conditions' => array(
                        'model' => $model,
                        'model_id' => $attachInfo['deleteId'],
                        'filekey' => $key,
                    )));
                    if (!is_null($filerecord)) {
                        if (file_exists($filerecord->filename)) {
                            unlink($filerecord->filename);
                        }
                        $filerecord->delete();
                    }
                }
            }
        }
        return $result;
    }

    protected function __fillStorage()
    {
        $model = $this->_model;
        static::$_storage[$model] = array();
        foreach ($model::$attaches as $key => $attach) {
            if ((is_numeric($key)) && (is_string($attach))) {
                $key = $attach;
                $attach = array();
            }
            static::$_storage[$model]['attaches'][$key] = array(
                'attachInfo' => $attach + static::$defaults,
                'data' => null,
                'deleteId' => null,
            );
        }
    }
}
