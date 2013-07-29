<?php

namespace app\controllers;

use \lithium\storage\Session;
use \app\models\Solutionfile;
use \app\models\Solution;
use \image_manipulation\processor\Upload;

class SolutionfilesController extends \app\controllers\AppController {

    public function download() {
        $originalfilename = $this->request->params['args'][1];
        $decodedArray = explode('separator', base64_decode($this->request->params['args'][0]));
        $fileId = $decodedArray[0];
        $modelId = $decodedArray[1];
        if($fileRecord = Solutionfile::first(array('conditions' => array(
            'id' => $fileId,
            'model_id' => $modelId,
            'originalbasename' => $originalfilename
        )))) {
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . $originalfilename . "\"");
            readfile($fileRecord->filename);
        }
        die();
    }

    public function resize() {
        $res = array(
            'error' => false,
        );
        if (!isset($this->request->data['id']) || empty($this->request->data['id']) || !isset($this->request->data['name']) || empty($this->request->data['name'])) {
            $res['error'] = 'Wrong request';
            return compact('res');
        }
        $solution = Solution::first((int)$this->request->data['id']);
        if ($solution->user_id != Session::read('user.id')) {
            $res['error'] = 'Wrong user';
            return compact('res');
        }


        $options = array(
            //'largest' => array('image_resize' => true, 'image_ratio_crop' => true, 'image_x' => 960, 'image_y' => 740, 'file_overwrite' => true),
            'solutionView' => array('image_resize' => true, 'image_ratio_fill' => true, 'image_x' => 488, 'image_background_color' => '#dddddd',  'image_y' => 366, 'file_overwrite' => true),
            //'gallerySmallSize' => array('image_resize' => true, 'image_ratio_crop' => 'T', 'image_x' => 99, 'image_y' => 75, 'file_overwrite' => true),
            'galleryLargeSize' => array('image_resize' => true, 'image_ratio_fill' => true, 'image_x' => 180, 'image_background_color' => '#ffffff', 'image_y' => 135, 'file_overwrite' => true),
            'gallerySiteSize' => array('image_resize' => true, 'image_x' => 800, 'image_ratio_y' => true),
            /*'galleryLargeSize' => array('image_resize' => true, 'image_ratio_crop' => 'TB', 'image_x' => 179, 'image_y' => 135, 'file_overwrite' => true),*/
            //'promoSize' => array('image_resize' => true, 'image_ratio_crop' => 'T', 'image_x' => 259, 'image_y' => 258, 'file_overwrite' => true),
        );

        foreach ($options as $option => $imageParams) {
            $newname = Solutionfile::first(array(
                'fields' => 'filename',
                'conditions' => array(
                    'model_id' => $solution->id,
                    'originalbasename' => $this->request->data['name'],
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
            $conditions = array('model' => '\app\models\Solution', 'model_id' => $solution->id, 'filekey' => 'solution' . '_' . $option, 'filename' => $newfilename);
            $fileModel = 'app\models\Solutionfile';
            $data = array('filename' => $newfilename) + $conditions;
            if ($existingRow = $fileModel::first(array('conditions' => $conditions))) {
                $existingRow->set($data);
                $existingRow->save();
            } else {
                $fileModel::create($data)->save();
            }
        }

        $res['result'] = true;
        return compact('res');
    }
}