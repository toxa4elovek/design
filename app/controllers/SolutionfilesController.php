<?php

namespace app\controllers;

use \lithium\storage\Session;
use \app\models\Solutionfile;

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

}