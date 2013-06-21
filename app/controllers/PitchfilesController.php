<?php

namespace app\controllers;

use \app\models\Pitchfile;
use \lithium\storage\Session;

class PitchfilesController extends \app\controllers\AppController {

	public $publicActions = array(
		'index', 'add', 'addDescription', 'testdelete', 'delete'
	);


	public function index() {

	}

	public function add() {
		$file = Pitchfile::create();
        if(Session::read('user.id')) {
            $file->user_id = Session::read('user.id');
        }
		$file->save($this->request->data);
        $file = Pitchfile::first($file->id);
		return json_encode($file->data());
	}

	public function addDescription() {
	    if (true == $this->request->data['description']) {
            $file = Pitchfile::first((int)$this->request->data['id']);
            $file->{'file-description'} = $this->request->data['description'];
            $file->save();
	    }
	    $this->render(array('head' => true));
	}

    public function delete() {
        $file = Pitchfile::first($this->request->id);

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

}

?>