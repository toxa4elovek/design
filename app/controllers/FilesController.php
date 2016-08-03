<?php

namespace app\controllers;

use \app\models\File;

class FilesController extends AppController
{

    public function download()
    {
        if (!empty($this->request->filename) && $file = File::first(['conditions' => ['filename' => ['LIKE' => '%' . substr($this->request->filename, 1)]]])) {
            if (file_exists($file->filename)) {
                header('Content-Type: application/download');
                header('Content-Disposition: attachment; filename="' . $file->originalbasename . '"');
                $this->readfile_chunked($file->filename);
            }
        }
        die();
    }

    private function readfile_chunked($filename, $retbytes = true)
    {
        $cnt    = 0;
        $handle = fopen($filename, 'rb');

        if ($handle === false) {
            return false;
        }

        while (!feof($handle)) {
            $buffer = fread($handle, 1024*1024);
            echo $buffer;
            ob_flush();
            flush();

            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }

        $status = fclose($handle);

        if ($retbytes && $status) {
            return $cnt;
        }

        return $status;
    }
}
