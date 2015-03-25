<?php

namespace app\controllers;

use \lithium\storage\Session;
use \app\models\Solution;
use \app\models\User;
use app\models\Tag;
use app\models\Searchtag;
use app\models\Solutiontag;
use \app\extensions\helper\User as UserHelper;
use \app\extensions\mailers\UserMailer;
use \app\extensions\mailers\SolutionsMailer;
use \lithium\analysis\Logger;

class SolutionsController extends \app\controllers\AppController {

    public $publicActions = array('like', 'unlike', 'logosale', 'search_logo', 'testmail');

    public function testmail() {
        SolutionsMailer::sendSolutionBoughtNotification(139860);
        die();
    }

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
            // Already has selected winner, need buy second winner
            if($solution->pitch->awarded > 0) {
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
        $sort_tags = array();
        $search_tags = array();
        if(($this->request->query['search']) && (!empty($this->request->query['search']))) {
            $words = Solution::stringToWordsForSearchQuery($this->request->query['search']);
            $industries = Solution::getListOfIndustryKeys($words);
            $words = Solution::injectIndustryWords($words);

            $tags_id = 0;
            if (!is_null($words)) {
                $tag_params = array('conditions' => array());
                // сохранение поиска в статистику
                $search_tags = Searchtag::all(array('conditions' => array('name' => $words)));
                if (count($search_tags) < 1) {
                    foreach ($words as $w) {
                        $tag_params['conditions']['OR'][] = array('name' => $w);
                        if (!empty($w)) {
                            $result = Searchtag::create(array(
                                'name' => $w
                            ));
                            $result->save();
                        }
                    }
                } else {
                    foreach ($search_tags as $v) {
                        $v->searches += 1;
                    }
                    $search_tags->save();
                    foreach ($words as $w) {
                        $tag_params['conditions']['OR'][] = array('name' => $w);
                    }
                }
                // конец страницы поика в статистику
                // поиск существующих тегов
                $tags = Tag::all($tag_params);
                if (count($tags) > 0) {
                    $tags_id = array_keys($tags->data());
                } else {
                    $tags_id = 0;
                }
            }
            $page = (isset($this->request->id) && !empty($this->request->id)) ? $this->request->id : 1;
            // Ищем указанную страницу результатов
            $params = Solution::buildSearchQuery($words, $industries, $tags_id, $page);
        }else {
            $params = Solution::buildStreamQuery($this->request->id);
        }
        if ($this->request->is('json')) {
            $params['page'] += 1;
            $next = Solution::all($params);
            if ($next) {
                $count = count($next);
            }
        } else {
            $params['page'] = 1;
            $sort_tags = Tag::getPopularTags(15);
            $search_tags = Searchtag::all(array('order' => array('searches' => 'desc'), 'limit' => 15));
            $countParams = array('conditions' => array('Solution.multiwinner' => 0, 'Solution.awarded' => 0, 'private' => 0, 'category_id' => 1, 'rating' => array('>=' => 3)), 'order' => array('created' => 'desc'), 'with' => array('Pitch'));
            $total_count = Solution::count($countParams);
        }
        $userHelper = new UserHelper(array());
        if ($userHelper->isLoggedIn()) {
            $data = Solution::addBlankPitchForLogosale($userHelper->getId(), 0);
        }

        $solutions = Solution::all($params);
        if(count($solutions) != 30) {
            $needToAddSolution = true;
        }
        if($needToAddSolution) {
            $params = Solution::buildStreamQuery();
            $addedSolutions = Solution::filterLogoSolutions(Solution::all($params));
        }

        $solutions = Solution::filterLogoSolutions($solutions);
        $solutions = Solution::applyUserFilters($solutions, $this->request->data['prop'], $this->request->data['variants']);

        if($needToAddSolution) {
            foreach($addedSolutions as $key => $addedSolution) {
                $solutions[$key] = $addedSolution;
                $solutions[$key]['sort'] = 1;
            }
        }

        return compact('solutions', 'count', 'sort_tags', 'search_tags', 'data', 'total_count');
    }

    public function search_logo() {
        if((!empty($this->request->query)) && (count($this->request->query) > 1)) {
            $this->request->data = $this->request->query;
        }
        if ($this->request->is('json') && (isset($this->request->data['search_list']) || (isset($this->request->data['prop'])))) {

            $words = Solution::stringToWordsForSearchQuery($this->request->data['search_list']);
            $industries = Solution::getListOfIndustryKeys($words);
            $words = Solution::injectIndustryWords($words);

            $tags_id = 0;
            if (!is_null($words)) {
                $tag_params = array('conditions' => array());
                // сохранение поиска в статистику
                $search_tags = Searchtag::all(array('conditions' => array('name' => $words)));
                if (count($search_tags) < 1) {
                    foreach ($words as $w) {
                        $tag_params['conditions']['OR'][] = array('name' => $w);
                        if (!empty($w)) {
                            $result = Searchtag::create(array(
                                        'name' => $w
                            ));
                            $result->save();
                        }
                    }
                } else {
                    foreach ($search_tags as $v) {
                        $v->searches += 1;
                    }
                    $search_tags->save();
                    foreach ($words as $w) {
                        $tag_params['conditions']['OR'][] = array('name' => $w);
                    }
                }
                // конец страницы поика в статистику
                // поиск существующих тегов
                $tags = Tag::all($tag_params);
                if (count($tags) > 0) {
                    $tags_id = array_keys($tags->data());
                } else {
                    $tags_id = 0;
                }
            }

            $page = (isset($this->request->id) && !empty($this->request->id)) ? $this->request->id : 1;
            // Ищем указанную страницу результатов
            $params = Solution::buildSearchQuery($words, $industries, $tags_id, $page);


            $solutions = Solution::all($params);
            $needToAddSolution = false;
            if ($solutions && count($solutions) > 0) {
                if(count($solutions) != 30) {
                    $needToAddSolution = true;
                }
                if($needToAddSolution) {
                    $params = Solution::buildStreamQuery();
                    $addedSolutions = Solution::filterLogoSolutions(Solution::all($params));
                }

                $solutions = Solution::filterLogoSolutions($solutions);
                $solutions = Solution::applyUserFilters($solutions, $this->request->data['prop'], $this->request->data['variants']);

                if($needToAddSolution) {
                    foreach($addedSolutions as $key => $addedSolution) {
                        $solutions[$key] = $addedSolution;
                        $solutions[$key]['sort'] = 1;
                    }
                }

            }elseif($page > 1) {
                $totalParams = Solution::buildSearchQuery($words, $industries, $tags_id, false, false);
                $totalSolutions = Solution::all($totalParams);
                if ($totalSolutions && count($totalSolutions) > 0) {
                    $totalSolutions = Solution::filterLogoSolutions($totalSolutions);
                    $totalSolutions = Solution::applyUserFilters($totalSolutions, $this->request->data['prop'], $this->request->data['variants']);
                }
                $totalPages = ceil(count($totalSolutions) / 4);
                $filteredPage = $page - $totalPages + 1;
                $params = Solution::buildStreamQuery($filteredPage);
                $solutions = Solution::filterLogoSolutions(Solution::all($params));
                $solutions = Solution::applyUserFilters($solutions);
                foreach($solutions as $key => $solution) {
                    $solution['sort'] = 1;
                }
            }
        }
        $total_solutions = count($solutions);
        return compact('solutions', 'total_solutions', 'page', 'pageParams');
    }

}
