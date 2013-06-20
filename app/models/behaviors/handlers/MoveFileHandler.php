<?php

namespace app\models\behaviors\handlers;

class MoveFileHandler extends \app\models\behaviors\handlers\StaticHandler {

	static public function useHandler($behavior){
		$behavior::applyFilter('afterSave', function($self, $params, $chain) {
            function generateHashName($base, $path) {
                do {
                    $hashedName = md5($base . uniqid());
                }while(file_exists($path . $hashedName));
                return $hashedName;
            }
			if(isset($params['uploadedFile']['data'])){
                if(isset($params['uploadedFile']['data'][0])) {
                    foreach($params['uploadedFile']['data'] as &$file) {
                        if(!isset($params['uploadedFile']['attachInfo']['moveFile'])){
                            $useroptions = array();
                        }else{
                            $useroptions = $params['uploadedFile']['attachInfo']['moveFile'];
                        }
                        $options = $useroptions + $self::$defaults['moveFile'];
                        if($options['preserveFileName']){
                            $newfilename = $file['name'];
                        }else{
                            $info = pathinfo($file['name']);

                            $newfilename = generateHashName($params['record']->id, LITHIUM_APP_PATH . $options['path']) . '.' . $info['extension'];
                        }
                        $path = LITHIUM_APP_PATH . $options['path'].$newfilename;
                        if(copy($file['tmp_name'], $path)){
                            $file['newname'] = $path;
                        }
                    }
                }else {
                    if(!isset($params['uploadedFile']['attachInfo']['moveFile'])){
                        $useroptions = array();
                    }else{
                        $useroptions = $params['uploadedFile']['attachInfo']['moveFile'];
                    }
                    $options = $useroptions + $self::$defaults['moveFile'];
                    if($options['preserveFileName']){
                        $newfilename = $params['uploadedFile']['data']['name'];
                    }else{
                        $info = pathinfo($params['uploadedFile']['data']['name']);

                        $newfilename = generateHashName($params['record']->id, LITHIUM_APP_PATH . $options['path']) . '.' . $info['extension'];
                    }
                    $path = LITHIUM_APP_PATH . $options['path'].$newfilename;
                    if(copy($params['uploadedFile']['data']['tmp_name'], $path)){
                        $params['uploadedFile']['data']['newname'] = $path;
                    }
                }
				
			}
			return $chain->next($self, $params, $chain);
		});
	}
	
}