<?php

namespace app\controllers;

use \lithium\storage\Session;
use \app\models\Solution;
use \app\models\User;
use \app\extensions\mailers\UserMailer;

class SolutionsController extends \app\controllers\AppController {

    public $publicActions = array('like', 'unlike');

    public function hide() {
        $result = $this->request;
        $result = Solution::hideimage($this->request->id, Session::read('user.id'));
        return compact('result');
    }

    public function unhide() {
        $result = true;
        $result = Solution::unhideimage($this->request->id, Session::read('user.id'));
        return compact('result');
    }


	public function like() {
		$likes = Solution::increaseLike($this->request->id, Session::read('user.id'));
		return compact('likes');
	}

    public function unlike() {
        $likes = Solution::decreaseLike($this->request->id, Session::read('user.id'));
        return compact('likes');
    }

	public function rating() {
		$rating = Solution::setRating($this->request->data['id'], $this->request->data['rating'], Session::read('user.id'));
		return compact('rating');
	}

    public function select() {
        if($solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('Pitch')))){
            if ((Session::read('user.id') != $solution->pitch->user_id) && (Session::read('user.isAdmin') != 1) && !User::checkRole('admin')) {
                $result = false;
                return compact('result');
            }
            $nominatedSolutionOfThisPitch = Solution::first(array(
                'conditions' => array('nominated' => 1, 'pitch_id' => $solution->pitch->id)
            ));
            if($nominatedSolutionOfThisPitch) {
                $result = false;
                return compact('result');
            }
            $result = Solution::selectSolution($solution);
            return $result;
        }
    }

    public function delete() {
        //error_reporting(E_ALL);
        //ini_set('display_errors', '1');
        $result = false;
        $isAdmin = Session::read('user.isAdmin');
        if(($solution = Solution::first($this->request->id)) && (($isAdmin == 1) || (in_array(Session::read('user.id'), array(32, 4, 5, 108, 81))) || ($solution->user_id == Session::read('user.id')))) {
            $result = $solution->delete();
        }
        if ($this->request->is('json')) {
            return compact('result');
        }
        $this->redirect(array('Pitches::view', 'id' => $solution->pitch_id));
    }

    public function warn() {
        $user = Session::read('user');
        if($solution = Solution::first($this->request->params['id'])) {
            $data = array('text' => $this->request->data['text'], 'user' => $user, 'solution' => $solution->data());
            UserMailer::warn_solution($data);
        }
        return $this->request->params['id'];
    }

    public function saveSelected() {
        if(isset($this->request->data['selectedSolutions'])) {
                if(($solution = Solution::first($this->request->data['selectedSolutions'])) && ($solution->user_id == Session::read('user.id'))) {
                    if($solution->selected == 1) {
                        $solution->selected = 0;
                    }else {
                        $solution->selected = 1;
                    }
                    $solution->save();
                }
        }
        return $this->request->data;
    }

}