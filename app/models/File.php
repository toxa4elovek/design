<?php

namespace app\models;

Class File extends \lithium\data\Model{
	
	protected static $_behaviors = array('');
	protected $_meta = array('model' => 'app\models\File');
	
	public function _init(){
		parent::_init();
	}
	
	public static function createFileRecord($data){
		$self = static::_instance();
		$filerecord = $self->create($data);
		$self->save($filerecord);
	}
}