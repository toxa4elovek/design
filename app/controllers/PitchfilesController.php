<?php

namespace app\controllers;

use \app\models\Pitchfile;
use \lithium\storage\Session;

class PitchfilesController extends AppController
{

    public $publicActions = [
        'index', 'add', 'addDescription', 'delete'
    ];


    public function index()
    {
        $this->render(['layout' => null, 'data' => null]);
    }

    public function add()
    {
        $file = Pitchfile::create();
        if ($this->userHelper->isLoggedIn()) {
            $file->user_id = $this->userHelper->getId();
        }
        $file->save($this->request->data);
        $file = Pitchfile::first($file->id);
        $res = json_encode($file->data());
        if ($this->request->is('json')) {
            return $res;
        } else {
            $this->render(['layout' => null, 'template' => 'index', 'data' => ['res' => $res]]);
        }
    }

    public function delete()
    {
        $file = Pitchfile::first($this->request->params['id']);

        //if((($file->user_id) && ($file->user_id == Session::read('user.id'))) || ($file->user_id == 0)){
            $file->delete();
        return 'true';
        //}
        //return false;
    }


    public function download()
    {
        if (!empty($this->request->filename) && $file = Pitchfile::first(['conditions' => ['filename' => ['LIKE' => '%' . substr($this->request->filename, 1)]]])) {
            if (file_exists($file->filename)) {
                $mimeType = $this->__getMimeType($file->filename);
                header('Content-Type: ' . $mimeType);
                header('Content-Disposition: attachment; filename="' . $file->originalbasename . '"');
                readfile($file->filename);
            }
        }
        die();
    }

    private function __getMimeType($filename)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $filename);
        finfo_close($finfo);
        return $mime;
    }
}
