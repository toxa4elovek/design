<?php

namespace app\models\behaviors;

use app\extensions\storage\Rcache;
use app\models\Solution;
use app\models\Solutionfile;

/**
 * Class UploadableSolution
 * @package app\models\behaviors
 */
class UploadableSolution extends UploadableImage
{
    /**
     * @var array $defaults Настройки для сохранения картинок для решений
     */
    public static $defaults = [
        'validate' => ['uploadedOnly' => true],
        'moveFile' => ['preserveFileName' => false, 'path' => '/resources/tmp/'],
        'setPermission' => ['mode' => 0644],
        'processImage' => [],
    ];

    /**
     * @var string $fileModel Имя модели
     */
    public static $fileModel = 'app\models\Solutionfile';

    /**
     * Инициализация
     * @return void
     */
    protected function _init()
    {
        parent::_init();
        static::$name = __CLASS__;
    }

    /**
     * Перегруженный метод, который запускается после удаления записи Solutionfile
     * Удалются все записи картинок и файлы
     *
     * @param mixed $result
     * @return mixed
     */
    public function afterDelete($result)
    {
        if (isset(Solution::$attaches['solution']['deleteId'])) {
            $deleteFileForRecord = function ($fileRecord) {
                if (!is_null($fileRecord)) {
                    if (file_exists($fileRecord->filename)) {
                        unlink($fileRecord->filename);
                    }
                    $fileRecord->delete();
                }
            };
            $deletedRecordId = Solution::$attaches['solution']['deleteId'];
            $cacheKey = "solutionfiles_$deletedRecordId";
            Rcache::delete($cacheKey);
            $fullModelName = $this->_model;
            foreach (Solution::$attaches as $key => $attachInfo) {
                $fileModel = static::$fileModel;
                $fileRecord = $fileModel::first(['conditions' => [
                    'model' => $fullModelName,
                    'model_id' => $deletedRecordId,
                    'filekey' => $key,
                ]]);
                $deleteFileForRecord($fileRecord);
            }
            foreach (Solutionfile::$processImage as $resizeName => $resizeOptions) {
                $fileRecord = $fileModel::first(['conditions' => [
                    'model' => $fullModelName,
                    'model_id' => $deletedRecordId,
                    'filekey' => $key . '_' . $resizeName,
                ]]);
                $deleteFileForRecord($fileRecord);
            }
        }
        return $result;
    }
}
