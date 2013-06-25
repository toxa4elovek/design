<?php

namespace app\controllers;

use app\models\Comment;
use \lithium\storage\Session;
use \app\models\User;
use \app\models\Solution;
use \app\extensions\mailers\UserMailer;
use \app\extensions\helper\Brief;

class CommentsController extends \lithium\action\Controller {

	public function add() {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $allowedAction = array('view', 'viewsolution');
        if((!isset($this->request->data['action'])) || (!in_array($this->request->data['action'], $allowedAction))) {
            $this->request->redirectAction = 'view';
        }
		$this->request->data['user_id'] = Session::read('user.id');
        $user = User::first($this->request->data['user_id']);
        if(strtotime($user->silenceUntil) < time()) {
            $result = Comment::createComment($this->request->data);
        }else {
            $result = array('solution_id' => $this->request->data['solution_id']);
        }
        if(isset($this->request->data['from'])) {
            return $this->redirect($this->request->data['from']);
        }
        if($this->request->redirectAction == 'view') {
            return $this->redirect(array('controller' => 'pitches', 'action' => 'view', 'id' => $this->request->data['pitch_id']));
        }else {
            return $this->redirect(array('controller' => 'pitches', 'action' => 'viewsolution', 'id' => $result['solution_id']));
        }
	}

    public function edit() {
        if((((Session::read('user.isAdmin') == 1) || (in_array(Session::read('user.id'), array(32, 4, 5, 108, 81)))) && ($comment = Comment::first($this->request->id))) || (($comment = Comment::first($this->request->id)) && (Session::read('user.id') == $comment->user_id))) {

            $comment = Comment::first($this->request->id);
            $comment->text = $this->request->data['text'];
            $comment->save();
            $comment = Comment::first($this->request->id);
            $brief = new Brief();
            return $brief->stripemail($comment->text);
        }
    }

    public function delete() {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        if((((Session::read('user.isAdmin') == 1) || (in_array(Session::read('user.id'), array(32, 4, 5, 108, 81)))) && ($comment = Comment::first($this->request->id))) || (($comment = Comment::first($this->request->id)) && (Session::read('user.id') == $comment->user_id))) {
            $comment->delete();
            if($comment->solution_id != 0) {
                if($solution = Solution::first($comment->solution_id)) {
                    return $this->redirect(array('controller' => 'pitches', 'action' => 'view', 'id' => $comment->pitch_id));
                }else {
                    return $this->redirect(array('controller' => 'pitches', 'action' => 'view', 'id' => $comment->pitch_id));
                }
            }else {
                return $this->redirect(array('controller' => 'pitches', 'action' => 'view', 'id' => $comment->pitch_id));
            }
        }
    }

    public function warn() {
        $user = Session::read('user');
        $comment = Comment::first($this->request->data['id']);
        $data = array('comment' => $comment->data(), 'text' => $this->request->data['text'], 'user' => $user, 'id' => $this->request->data['id']);
        UserMailer::warn_comment($data);
        return $this->request->data;
    }
}
?>