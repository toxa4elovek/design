<?php

namespace app\controllers;

use \lithium\storage\Session;
use \app\models\Solutionfile;
use \app\models\Solution;
use \app\models\Uploadnonce;

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

    public function solutions() {
        if (null != Session::read('user.id') && $file = Solutionfile::first(array('conditions' => array('filename' => array('LIKE' => '%' . substr($this->request->url, -30)))))) {
            if (file_exists($file->filename)) {
                header('Content-Type: application/download');
                header('Content-Disposition: attachment; filename="' . $file->originalbasename . '"');
                readfile($file->filename);
            }
        }
        exit;
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

    public function delete() {
        $res = array(
            'error' => false,
        );
        if (!$this->request->is('json') || (Session::read('user') == null)) {
            $this->redirect('/pitches/');
        }
        if (empty($this->request->data['name']) || empty($this->request->data['nonce']) || empty($this->request->data['position'])) {
            $res['error'] = 'wrong data';
            return compact('res');
        }
        if ($nonce = Uploadnonce::first(array('fields' => array('id'), 'conditions' => array('nonce' => $this->request->data['nonce'])))) {
            $nonce = $nonce->id;
            $files = Solutionfile::all(array(
                'conditions' => array(
                    'model' => '\app\models\Uploadnonce',
                    'model_id' => $nonce,
                    'position' => $this->request->data['position'],
                ),
            ));
            foreach ($files as $file) {
                if (file_exists($file->filename)) {
                    unlink($file->filename);
                }
                $file->delete();
            }
            $res['result'] = true;
        }
        return compact('res');
    }
}
