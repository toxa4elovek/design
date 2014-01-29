<?php

namespace app\models\behaviors\handlers;

use \image_manipulation\processor\Upload;

class ProcessImageHandler extends \app\models\behaviors\handlers\StaticHandler {

	static public function useHandler($behavior){
		$behavior::applyFilter('afterSave', function($self, $params, $chain) {
            if((isset($params['uploadedFile']['data'])) && (!isset($params['uploadedFile']['data'][0]))){
				$path = 'image_manipulation\Upload';

				if(!isset($params['uploadedFile']['attachInfo']['processImage'])){
					$useroptions = array();
				}else{
					$useroptions = $params['uploadedFile']['attachInfo']['processImage'];
				}
				$options = $useroptions + $self::$defaults['processImage'];

				foreach($options as $option => $imageParams){
					$imageProcessor = new Upload();
					$imageProcessor->uploadandinit($params['uploadedFile']['data']['newname']);
					$imageProcessor->uploaded = true;
					$imageProcessor->no_upload_check = true;
					$newfiledata = pathinfo($params['uploadedFile']['data']['newname']);
					$newfilename = $newfiledata['dirname'] . '/' . $newfiledata['filename'] . '_' . $option . '.' . $newfiledata['extension'];
					$imageProcessor->file_src_pathname = $params['uploadedFile']['data']['newname'];
					$imageProcessor->file_src_name_ext = $newfiledata['extension'];
					$imageProcessor->file_new_name_body = $newfiledata['filename'] . '_' . $option;
					foreach($imageParams as $param => $value){
						$imageProcessor->{$param} = $value;
					}

					$imageProcessor->process($newfiledata['dirname']);
					$conditions = array('model' => $params['model'], 'model_id' => $params['record']->id, 'filekey' => $params['key'] . '_' . $option, 'filename' => $newfilename);
   					$fileModel = $self::$fileModel;
   					$data = array('filename' => $newfilename, 'position' => $params['record']->position) + $conditions;
   					if($existingRow = $fileModel::first(array('conditions' => $conditions))) {
   						$existingRow->set($data);
	   					$existingRow->save();
	   				}else {
	   					$fileModel::create($data)->save();
	   				}
				}
			}else {
                foreach($params['uploadedFile']['data'] as &$file) {
                    $path = 'image_manipulation\Upload';

                    if(!isset($params['uploadedFile']['attachInfo']['processImage'])){
                        $useroptions = array();
                    }else{
                        $useroptions = $params['uploadedFile']['attachInfo']['processImage'];
                    }
                    $options = $useroptions + $self::$defaults['processImage'];
                    foreach($options as $option => $imageParams){
                        $imageProcessor = new Upload();
                        $imageProcessor->uploadandinit($file['newname']);
                        $imageProcessor->uploaded = true;
                        $imageProcessor->no_upload_check = true;
                        $newfiledata = pathinfo($file['newname']);
                        $newfilename = $newfiledata['dirname'] . '/' . $newfiledata['filename'] . '_' . $option . '.' . $newfiledata['extension'];
                        $imageProcessor->file_src_pathname = $file['newname'];
                        $imageProcessor->file_src_name_ext = $newfiledata['extension'];
                        $imageProcessor->file_new_name_body = $newfiledata['filename'] . '_' . $option;
                        foreach($imageParams as $param => $value){
                            $imageProcessor->{$param} = $value;
                        }

                        $imageProcessor->process($newfiledata['dirname']);
                        $conditions = array('model' => $params['model'], 'model_id' => $params['record']->id, 'filekey' => $params['key'] . '_' . $option, 'filename' => $newfilename);
                        $fileModel = $self::$fileModel;
                        $data = array('filename' => $newfilename, 'position' => $params['record']->position) + $conditions;
                        if($existingRow = $fileModel::first(array('conditions' => $conditions))) {
                            $existingRow->set($data);
                            $existingRow->save();
                        }else {
                            $fileModel::create($data)->save();
                        }
                    }
                }
            }
			return $chain->next($self, $params, $chain);
		});
	}

}
