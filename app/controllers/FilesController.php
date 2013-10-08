<?php

namespace app\controllers;

use \app\models\File;

class FilesController extends \app\controllers\AppController {

    public function download() {
        if (!empty($this->request->filename) && $file = File::first(array('conditions' => array('originalbasename' => $this->request->filename)))) {
            if (file_exists($file->filename)) {
                header('Content-Type: application/download');
                header('Content-Disposition: attachment; filename="' . $this->request->filename . '"');
                readfile($file->filename);
            }
        }
        exit;
    }
}