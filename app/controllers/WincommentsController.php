<?php

namespace app\controllers;

use app\models\Wincomment;
use \lithium\storage\Session;
use \app\extensions\helper\Brief;

class WincommentsController extends \lithium\action\Controller {

    /*public function add() {
        $allowedAction = array('view', 'viewsolution');
        if((!isset($this->request->data['action'])) || (!in_array($this->request->data['action'], $allowedAction))) {
            $this->request->redirectAction = 'view';
        }
        $this->request->data['user_id'] = Session::read('user.id');
        $result = Comment::createComment($this->request->data);
        if($this->request->redirectAction == 'view') {
            return $this->redirect(array('controller' => 'pitches', 'action' => 'view', 'id' => $this->request->data['pitch_id']));
        }else {
            return $this->redirect(array('controller' => 'pitches', 'action' => 'viewsolution', 'id' => $result['solution_id']));
        }
    }*/

    public function delete() {
        $allowedSteps = array('2', '3');
        if(!in_array($this->request->query['step'], $allowedSteps)) {
            $step = '3';
        }else {
            $step = $this->request->query['step'];
        }
        if(((Session::read('user.isAdmin') == 1) && ($comment = Wincomment::first($this->request->id))) || (($comment = Wincomment::first($this->request->id)) && (Session::read('user.id') == $comment->user_id))) {
            $comment->delete();
            if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
                return $this->redirect('/users/step' . $step . '/' . $comment->solution_id);
            } else {
                return json_encode('true');
            }
        }
    }

    public function edit() {
        if (($comment = Wincomment::first($this->request->id)) && (Session::read('user.isAdmin') == 1 || Session::read('user.id') == $comment->user_id || User::checkRole('admin') )) {
            $comment->text = $this->request->data['text'];
            $comment->save();
            $comment = Wincomment::first($this->request->id);
            $brief = new Brief();
            return html_entity_decode($brief->deleteHtmlTagsAndInsertHtmlLinkInTextAndMentions($comment->text));
        }
    }
}
?>