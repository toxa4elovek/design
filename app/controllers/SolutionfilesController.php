<?php

namespace app\controllers;

use \lithium\storage\Session;
use \app\models\Solutionfile;
use \app\models\Solution;

class SolutionfilesController extends \app\controllers\AppController {

    public function download() {
        $originalfilename = $this->request->params['args'][1];
        $decodedArray = explode('separator', base64_decode($this->request->params['args'][0]));
        $fileId = $decodedArray[0];
        $modelId = $decodedArray[1];
        if($fileRecord = Solutionfile::first(array('conditions' => array(
            'id' => $fileId,
            'model_id' => $modelId,
            'originalbasename' => $originalfilename
        )))) {
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . $originalfilename . "\"");
            readfile($fileRecord->filename);
        }
        die();
    }

    public function resize() {
        $res = array(
            'error' => false,
        );
        $params = array();
        if (!isset($this->request->data['id']) || empty($this->request->data['id']) || !isset($this->request->data['name']) || empty($this->request->data['name'])) {
            $res['error'] = 'Wrong request';
            return compact('res');
        }
        $params['name'] = $this->request->data['name'];
        $params['solution'] = Solution::first(array(
            'conditions' => array(
                'Solution.id' => $this->request->data['id'],
            ),
            'with' => array(
                'Pitch',
            ),
        ));

        if ($params['solution']->user_id != Session::read('user.id')) {
            $res['error'] = 'Wrong user';
            return compact('res');
        }
        if (Solutionfile::resize($params)) {
            $res['result'] = true;
        }
        return compact('res');
    }
}
