<?php

namespace app\controllers;

use \app\models\Moderation;
use \app\models\Comment;
use \app\models\Solution;
use \app\models\User;
use \lithium\storage\Session;

class ModerationsController extends \app\controllers\AppController {

    public function add() {
        $result = false;
        $isAdmin = Session::read('user.isAdmin');
        $currentUser = Session::read('user.id');
        if (isset($this->request->data) && (($isAdmin == 1) || User::checkRole('admin'))) {
            if ( ($this->request->data['model'] == 'comment') && ($comment = Comment::first($this->request->data['model_id'])) ) {
                $data = array(
                    'model' => '\app\models\Comment',
                    'model_id' => $comment->id,
                    'model_user' => $comment->user_id,
                    'model_data' => serialize(array(
                        'created' => $comment->created,
                        'text' => $comment->text,
                )));
                $pitch_id = $comment->pitch_id;
            }
            if ( ($this->request->data['model'] == 'solution') && ($solution = Solution::first($this->request->data['model_id'])) ) {
                $data = array(
                    'model' => '\app\models\Solution',
                    'model_id' => $solution->id,
                    'model_user' => $solution->user_id,
                    'model_data' => serialize(array(
                        'created' => $solution->created,
                        'description' => $solution->description,
                        'image' => self::getThumbnail($solution),
                )));
                $pitch_id = $solution->pitch_id;
            }
            $data['user_id'] = $currentUser;
            $data['reason'] = $this->request->data['reason'];
            $data['penalty'] = $this->request->data['penalty'];
            $data['explanation'] = $this->request->data['explanation'];
            $data['created'] = date('Y-m-d H:i:s');

            $moderation = Moderation::create();
            $moderation->set($data);
            $moderation->save();
            $result = $moderation->id;
        }

        if ($this->request->is('json')) {
            return json_encode($result);
        }
        return $this->redirect('/pitches/view/' . $pitch_id);
    }

    private static function getThumbnail($solution) {
        if (isset($solution->images['solution_galleryLargeSize'][0])) {
            $image = $solution->images['solution_galleryLargeSize'][0];
        } else {
            $image = $solution->images['solution_galleryLargeSize'];
        }
        if (file_exists($image['filename'])) {
            $newFileName = LITHIUM_APP_PATH . '/webroot/solutions/deleted/' . pathinfo($image['filename'], PATHINFO_BASENAME);
            copy($image['filename'], $newFileName);
        } else {
            $newFileName = null;
        }
        return $newFileName;
    }
}
