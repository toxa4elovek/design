<?php

namespace app\models;

use lithium\data\collection\RecordSet;
use lithium\data\entity\Record;

/**
 * Class Solutionfile
 * @package app\models
 * @method Record|null first(array $conditions = []) static
 * @method int count(array $conditions = []) static
 * @method RecordSet|null all(array $conditions = []) static
 */
class Solutionfile extends AppModel
{

    public static $processImage = [
        'solutionView' => [
            'image_resize' => true,
            'image_x' => 600,
            'image_ratio_y' => true,
            'convert_animation' => true
        ],
        'galleryLargeSize' => [
            'image_resize' => true,
            'image_ratio_fill' => true,
            'image_x' => 180,
            'image_background_color' => '#ffffff',
            'image_y' => 135,
            'file_overwrite' => true,
        ],
        'gallerySiteSize' => [
            'image_resize' => true,
            'image_x' => 800,
            'image_ratio_y' => true,
        ],
        'leftFeed' => [
            'image_resize' => true,
            'image_x' => 310,
            'image_y' => 240,
            'image_ratio_crop' => 'T',
            'file_overwrite' => true
        ],
        'pdfSummary' => [
            'image_resize' => true,
            'image_x' => 333,
            'image_y' => 224,
            'image_ratio_crop' => 'T',
            'file_overwrite' => true
        ],
        'middleFeed' => [
            'image_resize' => true,
            'image_x' => 600,
            'image_y' => 500,
            'image_ratio_crop' => 'T',
            'file_overwrite' => true
        ],
        'tutdesign' => [
            'image_resize' => true,
            'image_ratio_fill' => true,
            'image_x' => 267,
            'image_background_color' => '#dddddd',
            'image_y' => 200,
            'file_overwrite' => true
        ],
        'mobile' => [
            'image_resize' => true,
            'image_ratio_fill' => true,
            'image_x' => 590,
            'image_background_color' => '#ffffff',
            'image_y' => 448,
            'file_overwrite' => true
        ],
    ];
    protected static $processImageWatermark = [
        'solutionView' => [
            'image_resize' => true,
            'image_x' => 600,
            'image_ratio_y' => true,
            'image_watermark' => 'img/closed_pitch_watermark.png',
            'image_watermark_position' => 'TR',
        ],
        'galleryLargeSize' => [
            'image_resize' => true,
            'image_ratio_fill' => true,
            'image_x' => 180,
            'image_background_color' => '#ffffff',
            'image_y' => 135,
            'file_overwrite' => true,
        ],
        'gallerySiteSize' => [
            'image_resize' => true,
            'image_x' => 800,
            'image_ratio_y' => true,
            'image_watermark' => 'img/closed_pitch_watermark.png',
            'image_watermark_position' => 'TR',
        ],
    ];
    protected static $tallImageModifier = [
        'galleryLargeSize' => [
            'image_resize' => true,
            'image_x' => 180,
            'image_y' => 135,
            'image_ratio_crop' => 'T',
            'file_overwrite' => true,
        ],
        'tutdesign' => [
            'image_resize' => true,
            'image_x' => 267,
            'image_y' => 200,
            'image_ratio_crop' => 'T',
            'file_overwrite' => true
        ],
        'leftFeed' => [
            'image_resize' => true,
            'image_x' => 310,
            'image_y' => 240,
            'image_ratio_crop' => 'T',
            'file_overwrite' => true
        ],
        'pdfSummary' => [
            'image_resize' => true,
            'image_x' => 333,
            'image_y' => 224,
            'image_ratio_crop' => 'T',
            'file_overwrite' => true
        ],
        'middleFeed' => [
            'image_resize' => true,
            'image_x' => 600,
            'image_y' => 500,
            'image_ratio_crop' => 'T',
            'file_overwrite' => true
        ],
        'mobile' => [
            'image_resize' => true,
            'image_x' => 590,
            'image_y' => 448,
            'image_ratio_crop' => 'T',
            'file_overwrite' => true
        ],
    ];

    public static function resize($params)
    {
        $options = self::$processImage;
        if ($params['solution']->pitch->private > 0 && false) { // false turns off the watermarking
            $options = self::$processImageWatermark;
        }
        foreach ($options as $option => $imageParams) {
            $newname = self::first([
                        'fields' => 'filename',
                        'conditions' => [
                            'model_id' => $params['solution']->id,
                            'originalbasename' => $params['name'],
                        ],
            ]);
            $newfiledata = pathinfo($newname->filename);
            $newfilename = $newfiledata['dirname'] . '/' . $newfiledata['filename'] . '_' . $option . '.' . $newfiledata['extension'];
            $imageProcessor = new \upload($newname->filename);
            $imageProcessor->upload($newname->filename);
            $imageProcessor->init();
            $imageProcessor->uploaded = true;
            $imageProcessor->no_upload_check = true;
            $imageProcessor->file_src_pathname = $newname->filename;
            $imageProcessor->file_src_name_ext = $newfiledata['extension'];
            $imageProcessor->file_new_name_body = $newfiledata['filename'] . '_' . $option;
            foreach ($imageParams as $param => $value) {
                $imageProcessor->{$param} = $value;
            }

            $imageProcessor->process($newfiledata['dirname']);
            $conditions = ['model' => '\app\models\Solution', 'model_id' => $params['solution']->id, 'filekey' => 'solution' . '_' . $option, 'filename' => $newfilename];
            $data = ['filename' => $newfilename] + $conditions;
            if ($existingRow = self::first(['conditions' => $conditions])) {
                $existingRow->set($data);
                $existingRow->save();
            } else {
                self::create($data)->save();
            }
        }

        return true;
    }

    public static function getParams()
    {
        return self::$processImage;
    }

    public static function paramsModify($file)
    {
        $res = self::$processImage;
        if (file_exists($file['tmp_name'])) {
            $image_info = getimagesize($file["tmp_name"]);
            $image_width = $image_info[0];
            $image_height = $image_info[1];
            if ($image_width < $image_height) {
                $res = self::$tallImageModifier + self::$processImage;
            }
        }
        return $res;
    }

    public static function copy($model_id, $new_model)
    {
        $files = self::all(['conditions' => ['model_id' => $model_id, 'originalbasename' => ['!=' => '']]]);
        $options = self::$processImage;
        if (count($files) > 0) {
            foreach ($files as $file) {
                $newfiledata = pathinfo($file->filename);
                $newfiledata['filename'] = md5(uniqid('', true));

                foreach ($options as $option => $imageParams) {
                    $newfilename = $newfiledata['dirname'] . '/' . $newfiledata['filename'] . '_' . $option . '.' . $newfiledata['extension'];
                    $imageProcessor = new \upload($file->filename);
                    $imageProcessor->upload($file->filename);
                    $imageProcessor->init();
                    $imageProcessor->uploaded = true;
                    $imageProcessor->no_upload_check = true;
                    $imageProcessor->file_src_pathname = $file->filename;
                    $imageProcessor->file_src_name_ext = $newfiledata['extension'];
                    $imageProcessor->file_new_name_body = $newfiledata['filename'] . '_' . $option;
                    foreach ($imageParams as $param => $value) {
                        $imageProcessor->{$param} = $value;
                    }

                    $imageProcessor->process($newfiledata['dirname']);
                    $conditions = ['model' => '\app\models\Solution', 'model_id' => $new_model, 'filekey' => 'solution' . '_' . $option, 'filename' => $newfilename];
                    $data = ['filename' => $newfilename] + $conditions;
                    if ($existingRow = self::first(['conditions' => $conditions])) {
                        $existingRow->set($data);
                        $existingRow->save();
                    } else {
                        self::create($data)->save();
                    }
                }
            }
        } else {
            return false;
        }
        return true;
    }
}
