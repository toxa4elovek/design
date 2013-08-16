<?php

namespace app\models;

use \image_manipulation\processor\Upload;

class Solutionfile extends \app\models\AppModel {

    protected static $processImage = array(
        'solutionView' => array(
            'image_resize' => true,
            'image_x' => 600,
            'image_ratio_y' => true,
        ),
        'galleryLargeSize' => array(
            'image_resize' => true,
            'image_ratio_fill' => true,
            'image_x' => 180,
            'image_background_color' => '#ffffff',
            'image_y' => 135,
            'file_overwrite' => true,
        ),
        'gallerySiteSize' => array(
            'image_resize' => true,
            'image_x' => 800,
            'image_ratio_y' => true,
        ),
    );

    protected static $processImageWatermark = array(
        'solutionView' => array(
            'image_resize' => true,
            'image_x' => 600,
            'image_ratio_y' => true,
            'image_watermark' => 'img/closed_pitch_watermark.png',
            'image_watermark_position' => 'TR',
        ),
        'galleryLargeSize' => array(
            'image_resize' => true,
            'image_ratio_fill' => true,
            'image_x' => 180,
            'image_background_color' => '#ffffff',
            'image_y' => 135,
            'file_overwrite' => true,
        ),
        'gallerySiteSize' => array(
            'image_resize' => true,
            'image_x' => 800,
            'image_ratio_y' => true,
            'image_watermark' => 'img/closed_pitch_watermark.png',
            'image_watermark_position' => 'TR',
        ),
    );

    public static function resize($params) {
        $options = self::$processImage;
        if ($params['solution']->pitch->private > 0 && false) { // false turns off the watermarking
            $options = self::$processImageWatermark;
        }
        foreach ($options as $option => $imageParams) {
            $newname = self::first(array(
                'fields' => 'filename',
                'conditions' => array(
                    'model_id' => $params['solution']->id,
                    'originalbasename' => $params['name'],
                ),
            ));
            $newfiledata = pathinfo($newname->filename);
            $newfilename = $newfiledata['dirname'] . '/' . $newfiledata['filename'] . '_' . $option . '.' . $newfiledata['extension'];
            $imageProcessor = new Upload();
            $imageProcessor->uploadandinit($newname->filename);
            $imageProcessor->uploaded = true;
            $imageProcessor->no_upload_check = true;
            $imageProcessor->file_src_pathname = $newname->filename;
            $imageProcessor->file_src_name_ext = $newfiledata['extension'];
            $imageProcessor->file_new_name_body = $newfiledata['filename'] . '_' . $option;
            foreach ($imageParams as $param => $value) {
                $imageProcessor->{$param} = $value;
            }

            $imageProcessor->process($newfiledata['dirname']);
            $conditions = array('model' => '\app\models\Solution', 'model_id' => $params['solution']->id, 'filekey' => 'solution' . '_' . $option, 'filename' => $newfilename);
            $data = array('filename' => $newfilename) + $conditions;
            if ($existingRow = self::first(array('conditions' => $conditions))) {
                $existingRow->set($data);
                $existingRow->save();
            } else {
                self::create($data)->save();
            }
        }

        return true;
    }
}