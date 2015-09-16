<?php

namespace app\models\behaviors\handlers;

use \image_manipulation\processor\Upload;
use \app\models\Solutionfile;

class ProcessImageHandler extends \app\models\behaviors\handlers\StaticHandler {

	static public function useHandler($behavior){
		$behavior::applyFilter('afterSave', function($self, $params, $chain) {
            set_time_limit(120);
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

                    if ($self::$fileModel == 'app\\models\\Solutionfile') {
                        $options = Solutionfile::paramsModify($file) + $options;
                    }
                    foreach($options as $option => $imageParams){
                        $isAnimatedGif = false;
                        if(isset($imageParams['convert_animation']) && $imageParams['convert_animation']) {
                            // проверка на гифку и анимированность
                            function isAnimatedGif($filename) {
                                if(!($fh = @fopen($filename, 'rb')))
                                    return false;
                                $count = 0;
                                //an animated gif contains multiple "frames", with each frame having a
                                //header made up of:
                                // * a static 4-byte sequence (\x00\x21\xF9\x04)
                                // * 4 variable bytes
                                // * a static 2-byte sequence (\x00\x2C)

                                // We read through the file til we reach the end of the file, or we've found
                                // at least 2 frame headers
                                while(!feof($fh) && $count < 2) {
                                    $chunk = fread($fh, 1024 * 100); //read 100kb at a time
                                    $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00\x2C#s', $chunk, $matches);
                                }

                                fclose($fh);
                                return $count > 1;
                            }

                            $possibleGif = $file['tmp_name'];
                            if(($file['type'] == 'image/gif') && (isAnimatedGif($possibleGif))) {
                                $isAnimatedGif = true;
                                $newfiledata = pathinfo($file['newname']);
                                $newfilename = $newfiledata['dirname'] . '/' . $newfiledata['filename'] . '_' . $option . '.mp4';
                                $newfilenamewebm = $newfiledata['dirname'] . '/' . $newfiledata['filename'] . '_' . $option . '.webm';
                                $newfilenameogv = $newfiledata['dirname'] . '/' . $newfiledata['filename'] . '_' . $option . '.ogv';
                                //$executable = '/usr/local/Cellar/ffmpeg/2.5.4/bin/ffmpeg';
                                $executable = '/usr/local/bin/ffmpeg';
                                //var_dump($executable . ' -f gif -i ' . $possibleGif . ' -vcodec libx264 -b 250k -bt 50k ' . $newfilename . ' 2>&1');
                                // mp4
                                // /usr/local/bin/ffmpeg -f gif -i /root/test.gif -vcodec libx264 -b 250k -bt 50k /var/godesigner/webroot/video5.mp4 2>&1
                                exec($executable . ' -f gif -i ' . $possibleGif . ' -vcodec libx264 -b 250k -bt 50k ' . $newfilename . ' 2>&1', $out);
                                //var_dump($out);
                                // webm
                                // /usr/local/bin/ffmpeg -f gif -i /root/test.gif -vcodec libvpx  /var/godesigner/webroot/video4.webm 2>&1
                                //var_dump($executable . ' -f gif -i ' . $possibleGif . ' -vcodec libvpx ' . $newfilenamewebm . ' 2>&1');
                                exec($executable . ' -f gif -i ' . $possibleGif . ' -vcodec libvpx ' . $newfilenamewebm . ' 2>&1', $out);
                                //var_dump($out);

                                // ogv
                                // /usr/local/bin/ffmpeg -f gif -i /root/test.gif -vcodec libtheora  /var/godesigner/webroot/video5.ogv 2>&1
                                exec($executable . ' -f gif -i ' . $possibleGif . ' -vcodec libtheora ' . $newfilenameogv . ' 2>&1', $out);
                                //var_dump($out);
                                //die();
                            }
                        }
                        if($isAnimatedGif == false) {
                            $imageProcessor = new Upload();
                            $imageProcessor->uploadandinit($file['newname']);
                            $imageProcessor->uploaded = true;
                            $imageProcessor->no_upload_check = true;
                            $newfiledata = pathinfo($file['newname']);
                            $newfilename = $newfiledata['dirname'] . '/' . $newfiledata['filename'] . '_' . $option . '.' . $newfiledata['extension'];
                            $imageProcessor->file_src_pathname = $file['newname'];
                            $imageProcessor->file_src_name_ext = $newfiledata['extension'];
                            $imageProcessor->file_new_name_body = $newfiledata['filename'] . '_' . $option;
                            foreach ($imageParams as $param => $value) {
                                $imageProcessor->{$param} = $value;
                            }
                            $imageProcessor->process($newfiledata['dirname']);
                        }
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
