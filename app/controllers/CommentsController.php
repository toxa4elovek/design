<?php

namespace app\controllers;

use app\models\Comment;
use \lithium\storage\Session;
use \app\models\User;
use \app\models\Solution;
use \app\extensions\mailers\UserMailer;
use \app\extensions\helper\Brief;
use \app\models\Avatar;
use \app\extensions\helper\Avatar as AvatarHelper;
use app\extensions\storage\Rcache;

class CommentsController extends \lithium\action\Controller
{

    public function add()
    {
        $allowedAction = ['view', 'viewsolution'];
        if ((!isset($this->request->data['action'])) || (!in_array($this->request->data['action'], $allowedAction))) {
            $this->request->redirectAction = 'view';
        }
        $this->request->data['user_id'] = Session::read('user.id');
        $user = User::first($this->request->data['user_id']);
        if (strtotime($user->silenceUntil) < time()) {
            $result = Comment::createComment($this->request->data);
            if (isset($this->request->data['fromAjax'])) {
                if (isset($result['error'])) {
                    return compact('result');
                }
                $avatarHelper = new AvatarHelper;
                $userAvatar = $avatarHelper->show($user->data(), false, true);
                $comment = Comment::first(['conditions' => ['Comment.id' => $result['id']], 'with' => ['User', 'Pitch']]);
                if ($comment->solution_id) {
                    $solution = Solution::first($comment->solution_id);
                    $result['solution_num'] = $solution->num;
                }
                return compact('result', 'comment', 'userAvatar');
            }
        } else {
            $result = ['solution_id' => $this->request->data['solution_id']];
        }
        if (isset($this->request->data['from'])) {
            return $this->redirect($this->request->data['from']);
        }
        if ($this->request->redirectAction == 'view') {
            return $this->redirect(['controller' => 'pitches', 'action' => 'view', 'id' => $this->request->data['pitch_id']]);
        } else {
            return $this->redirect(['controller' => 'pitches', 'action' => 'viewsolution', 'id' => $result['solution_id']]);
        }
    }

    public function edit()
    {
        if ((((Session::read('user.isAdmin') == 1) || User::checkRole('admin')) && ($comment = Comment::first($this->request->id))) || (($comment = Comment::first($this->request->id)) && (Session::read('user.id') == $comment->user_id))) {
            $comment = Comment::first($this->request->id);
            $comment->text = $this->request->data['text'];
            $comment->save();
            $comment = Comment::first($this->request->id);
            $brief = new Brief();
            $cacheKey = 'commentsraw_' . $comment->pitch_id;
            Rcache::delete($cacheKey);
            return $brief->stripEmail($comment->text);
        }
    }

    public function delete()
    {
        if ((((Session::read('user.isAdmin') == 1) || User::checkRole('admin')) && ($comment = Comment::first($this->request->id))) || (($comment = Comment::first($this->request->id)) && (Session::read('user.id') == $comment->user_id))) {
            $cacheKey = 'commentsraw_' . $comment->pitch_id;
            Rcache::delete($cacheKey);
            $comment->delete();
            if ($this->request->is('json')) {
                return 'true';
            }
            if ($comment->solution_id != 0) {
                if ($solution = Solution::first($comment->solution_id)) {
                    return $this->redirect(['controller' => 'pitches', 'action' => 'view', 'id' => $comment->pitch_id]);
                } else {
                    return $this->redirect(['controller' => 'pitches', 'action' => 'view', 'id' => $comment->pitch_id]);
                }
            } else {
                return $this->redirect(['controller' => 'pitches', 'action' => 'view', 'id' => $comment->pitch_id]);
            }
        }
    }

    public function warn()
    {
        $user = Session::read('user');
        $comment = Comment::first($this->request->data['id']);
        $data = ['comment' => $comment->data(), 'text' => $this->request->data['text'], 'user' => $user, 'id' => $this->request->data['id']];
        UserMailer::warn_comment($data);
        return $this->request->data;
    }
}
