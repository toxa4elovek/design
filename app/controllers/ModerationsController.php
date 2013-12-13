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
        if ((isset($this->request->data))
        && (($isAdmin == 1) || (in_array($currentUser, array(32, 4, 5, 108, 81))))) {
            if ( ($this->request->data['model'] == 'comment') && ($comment = Comment::first($this->request->data['model_id'])) ) {
                $data = array(
                    'model' => '\app\models\Comment',
                    'model_id' => $comment->id,
                    'model_data' => serialize(array(
                        'user_id' => $comment->user_id,
                        'created' => $comment->created,
                        'text' => $comment->text,
                )));
            }
            if ( ($this->request->data['model'] == 'solution') && ($solution = Solution::first($this->request->data['model_id'])) ) {
                $data = array(
                    'model' => '\app\models\Solution',
                    'model_id' => $solution->id,
                    'model_data' => serialize(array(
                        'user_id' => $solution->user_id,
                        'created' => $solution->created,
                        'description' => $solution->description,
                        'image' => $solution->images['solution_galleryLargeSize'][0]['weburl'],
                )));
            }
            $data['user_id'] = $currentUser;
            $data['reason'] = $this->request->data['reason'];
            $data['penalty'] = $this->request->data['penalty'];

            $moderation = Moderation::create();
            $moderation->set($data);
            $moderation->save();
            $result = $moderation->id;
        }

        if ($this->request->is('json')) {
            return json_encode($result);
        }
        return $this->redirect('/pitches/view/' . $pitch->id);
    }
}
