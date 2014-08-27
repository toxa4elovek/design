<?php

namespace app\controllers;

use \app\models\Pitchfile;
use \lithium\storage\Session;

class PitchfilesController extends \app\controllers\AppController {

	public $publicActions = array(
		'index', 'add', 'addDescription', 'testdelete', 'delete'
	);


	public function index() {
	    $this->render(array('layout' => null, 'data' => null));
	}

	public function add() {
		$file = Pitchfile::create();
        if(Session::read('user.id')) {
            $file->user_id = Session::read('user.id');
        }
		$file->save($this->request->data);
        $file = Pitchfile::first($file->id);
        $res = json_encode($file->data());
        if ($this->request->is('json')) {
		  return $res;
        } else {
            $this->render(array('layout' => null, 'template' => 'index', 'data' => array('res' => $res)));
        }
	}

    public function delete() {
        $file = Pitchfile::first($this->request->data['id']);

        //if((($file->user_id) && ($file->user_id == Session::read('user.id'))) || ($file->user_id == 0)){
            $file->delete();
            return 'true';
        //}
        //return false;
    }

    public function testdelete() {


        $file = Pitchfile::first(array('order' => array('id' => 'desc')));
        $file->delete();
        die();
    }

    public function download() {
        if (!empty($this->request->filename) && $file = Pitchfile::first(array('conditions' => array('filename' => array('LIKE' => '%' . substr($this->request->filename, 1)))))) {
            if (file_exists($file->filename)) {
                header('Content-Type: application/download');
                header('Content-Disposition: attachment; filename="' . $file->originalbasename . '"');
                readfile($file->filename);
            }
        }
        exit;
    }
}

?>