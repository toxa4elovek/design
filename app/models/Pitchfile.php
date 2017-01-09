<?php

namespace app\models;

class Pitchfile extends \app\models\AppModel
{

    public $belongsTo = ['Pitch' => ['key' => 'model_id']];

    public static $attaches = ['file' => [
        /*'validate' => array('uploadedOnly' => true),*/
        'moveFile' => ['preserveFileName' => false, 'path' => '/webroot/pitchfiles/'],
        'setPermission' => ['mode' => 0766],
    ]];

    public static $fileModel = 'app\models\Pitchfile';

    protected static $_behaviors = [
        'UploadablePitchfile'
    ];

    public static function __init()
    {
        parent::__init();
        self::applyFilter('find', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
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
            $attachRecord = function ($record) use ($getWebUrl, $getBasename) {
                $record->weburl = $getWebUrl($record->filename);
                if (empty($record->originalbasename)) { // Older files fallback
                    $record->basename = $getBasename($record->filename);
                } else {
                    $record->basename = $getBasename($record->originalbasename);
                }
                return $record;
            };
            if ((is_object($result)) and (get_class($result) == 'lithium\data\entity\Record')) {
                $result = $attachRecord($result);
            } elseif (($result) and (count($result) > 0)) {
                foreach ($result as $foundItem) {
                    $foundItem = $attachRecord($foundItem);
                }
            }
            return $result;
        });
    }
}
