<?php

namespace app\controllers;

use \app\models\File;

class FilesController extends \app\controllers\AppController {

    public function download() {
        if (!empty($this->request->filename) && $file = File::first(array('conditions' => array('filename' => array('LIKE' => '%' . substr($this->request->filename, 1)))))) {
            if (file_exists($file->filename)) {
                header('Content-Type: application/download');
                header('Content-Disposition: attachment; filename="' . $file->originalbasename . '"');
                readfile($file->filename);
            }
        }
        exit;
    }
}