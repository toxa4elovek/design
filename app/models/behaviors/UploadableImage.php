<?php

namespace app\models\behaviors;

use \app\models\File;

/**
 * ��������� UploadableImage
 *
 * @description	��������� ����������� ��������� ����� ������ � ����� ������.
 *
 * 
 */
class UploadableImage extends \app\models\behaviors\UploadableFile{

	public static $defaults = array(
		//'validate' => array('uploadedOnly' => true), 
		//'moveFile' => array('preserveFileName' => false, 'path' => '/resources/tmp/'), 
		//'setPermission' => array('mode' => 0644),
		//'processImage' => array('crop' => array('image_crop' => array(38), 'file_overwrite' => true)),
	);
	
	public static $fileModel = 'app\models\File';
	
	protected function _init(){
		parent::_init();
		static::$name = __CLASS__;
	}
	
	public function afterDelete($result){
		if($result){
			$model = $this->_model;
			$configuration = static::$_storage[$model];
			foreach($configuration['attaches'] as $key => &$attachInfo){
				if(isset($attachInfo['deleteId'])){
					$fileModel = static::$fileModel;
					$filerecord = $fileModel::find('first', array('conditions' => array(
						'model' => $model, 
						'model_id' => $attachInfo['deleteId'], 
						'filekey' => $key,
					)));
					if(!is_null($filerecord)){
						if(file_exists($filerecord->filename)){
							unlink($filerecord->filename);
						}
						$filerecord->delete();
					}
					foreach($attachInfo['attachInfo']['processImage'] as $resizeName => $resizeOptions){
						$filerecord = $fileModel::find('first', array('conditions' => array(
							'model' => $model, 
							'model_id' => $attachInfo['deleteId'], 
							'filekey' => $key . '_' . $resizeName,
						)));
						if(!is_null($filerecord)){
							if(file_exists($filerecord->filename)){
								unlink($filerecord->filename);
							}
							$filerecord->delete();
						}
					}
					
				}
			} 
		}
		return $result;
	}

}