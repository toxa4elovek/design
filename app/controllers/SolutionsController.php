<?php

namespace app\controllers;

use \lithium\storage\Session;
use \app\models\Solution;
use \app\models\User;
use app\models\Tag;
use app\models\Solutiontag;
use \app\extensions\mailers\UserMailer;
use \lithium\analysis\Logger;

class SolutionsController extends \app\controllers\AppController {

    public $publicActions = array('like', 'unlike', 'logosale');

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
        $result = $likes['result'];
        $likes = $likes['likes'];
        return compact('likes', 'result');
    }

    public function unlike() {
        $likes = Solution::decreaseLike($this->request->id, Session::read('user.id'));
        $result = $likes['result'];
        $likes = $likes['likes'];
        return compact('likes', 'result');
    }

    public function rating() {
        $rating = Solution::setRating($this->request->data['id'], $this->request->data['rating'], Session::read('user.id'));
        return compact('rating');
    }

    public function select() {
        if ($solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('Pitch')))) {
            if ((Session::read('user.id') != $solution->pitch->user_id) && (Session::read('user.isAdmin') != 1) && !User::checkRole('admin')) {
                $result = false;
                return compact('result');
            }
            $nominatedSolutionOfThisPitch = Solution::first(array(
                        'conditions' => array('nominated' => 1, 'pitch_id' => $solution->pitch->id)
            ));
            if ($nominatedSolutionOfThisPitch) {
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
        if (($solution = Solution::first($this->request->id)) && (($isAdmin == 1) || User::checkRole('admin') || ($solution->user_id == Session::read('user.id')))) {
            $data = array(
                'id' => $solution->id,
                'num' => $solution->num,
                'user_who_deletes' => Session::read('user.id'),
                'user_id' => $solution->user_id,
                'date' => date('Y-m-d H:i:s'),
                'isAdmin' => $isAdmin
            );
            Logger::write('info', serialize($data), array('name' => 'deleted_solutions'));
            $result = $solution->delete();
        }
        if ($this->request->is('json')) {
            return compact('result');
        } else {
            $this->redirect(array('Pitches::view', 'id' => $solution->pitch_id));
        }
    }

    public function warn() {
        $user = Session::read('user');
        if ($solution = Solution::first($this->request->params['id'])) {
            $data = array('text' => $this->request->data['text'], 'user' => $user, 'solution' => $solution->data());
            UserMailer::warn_solution($data);
        }
        return $this->request->params['id'];
    }

    public function saveSelected() {
        if (isset($this->request->data['selectedSolutions'])) {
            if (($solution = Solution::first($this->request->data['selectedSolutions'])) && ($solution->user_id == Session::read('user.id'))) {
                if ($solution->selected == 1) {
                    $solution->selected = 0;
                } else {
                    $solution->selected = 1;
                }
                $solution->save();
            }
        }
        return $this->request->data;
    }

    public function logosale() {
        $count = 0;
        $params = array('conditions' => array('Solution.multiwinner' => 0, 'Solution.awarded' => 0, 'private' => 0, 'category_id' => 1, 'rating' => array('>=' => 3)), 'with' => array('Pitch'), 'limit' => 12, 'page' => $this->request->id);
        if ($this->request->is('json')) {
            $solutions = Solution::all($params);
            $params['page'] += 1;
            $next = Solution::all($params);
            if ($next) {
                $count = count($next);
            }
        } else {
            $params['page'] = 1;
            $solutions = Solution::all($params);
        }
        if ($solutions) {
            $black_list = array();
            foreach ($solutions as $v) {
                if ($v->awarded) {
                    $black_list[] = array('user' => $v->user_id, 'pitch' => $v->pitch_id);
                }
            }
            $solutions = $solutions->data();
            foreach ($solutions as $k => $solution) {
                foreach ($black_list as $v) {
                    if ($v['pitch'] == $solution['pitch_id'] && $v['user'] == $solution['user_id']) {
                        unset($solutions[$k]);
                    }
                }
            }
        } else {
            $solutions = array();
        }
        return compact('solutions', 'count');
    }

    public function search_logo() {
        if ($this->request->is('json') && isset($this->request->data['search'])) {
            $words = explode(' ', $this->request->data['search']);
            $tag_params = array('conditions' => array());
            foreach ($words as $w) {
                $tag_params['conditions']['OR'][] = array('name' => $w);
            }

            $tags = Tag::all($tag_params);
            if (count($tags) > 0) {
                $tags_id = array_keys($tags->data());
            } else {
                $tags_id = 0;
            }
            $params = array('conditions' => array('Solution.multiwinner' => 0, 'Solution.awarded' => 0, 'private' => 0, 'category_id' => 1, 'rating' => array('>=' => 3), 'Solutiontag.id' => $tags_id), 'with' => array('Pitch', 'Solutiontag'));
            $solutions = Solution::all($params);
            if (count($solutions > 0)) {
                $black_list = array();
                foreach ($solutions as $v) {
                    if ($v->awarded) {
                        $black_list[] = array('user' => $v->user_id, 'pitch' => $v->pitch_id);
                    }
                }
                $solutions = $solutions->data();
                foreach ($solutions as $k => $solution) {
                    foreach ($black_list as $v) {
                        if ($v['pitch'] == $solution['pitch_id'] && $v['user'] == $solution['user_id']) {
                            unset($solutions[$k]);
                        }
                    }
                }
            }
        }
        return compact('solutions');
    }

}
