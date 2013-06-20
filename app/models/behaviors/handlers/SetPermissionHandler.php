<?php

namespace app\models\behaviors\handlers;

Class SetPermissionHandler extends \app\models\behaviors\handlers\StaticHandler {
	
	static public function useHandler($behavior){
		$behavior::applyFilter('afterSave', function($self, $params, $chain) {
            if((isset($params['uploadedFile']['data'])) && (!isset($params['uploadedFile']['data'][0]))){

				if(!isset($params['uploadedFile']['attachInfo']['setPermission'])){
					$useroptions = array();
				}else{
					$useroptions = $params['uploadedFile']['attachInfo']['setPermission'];
				}
				$options = $useroptions + $self::$defaults['setPermission'];

				chmod($params['uploadedFile']['data']['newname'], $options['mode']);
			}else {
                foreach($params['uploadedFile']['data'] as &$file) {

                    if(!isset($params['uploadedFile']['attachInfo']['setPermission'])){
                        $useroptions = array();
                    }else{
                        $useroptions = $params['uploadedFile']['attachInfo']['setPermission'];
                    }
                    $options = $useroptions + $self::$defaults['setPermission'];

                    chmod($file['newname'], $options['mode']);
                }
            }
			return $chain->next($self, $params, $chain);
		});   
	}
	
}