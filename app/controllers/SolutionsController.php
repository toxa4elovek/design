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
use \lithium\analysis\Logger;

class SolutionsController extends \app\controllers\AppController {

    public $publicActions = array('like', 'unlike', 'logosale', 'search_logo');

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
        $sort_tags = array();
        $search_tags = array();
        $params = array(
            'conditions' =>
                array(
                    'Solution.multiwinner' => 0,
                    'Solution.awarded' => 0,
                    'Pitch.awarded' => array('>' => date('Y-m-d H:i:s', time() - MONTH)),
                    'Pitch.status' => array('>' => 0),
                    'private' => 0,
                    'category_id' => 1,
                    'rating' => array('>=' => 3)
                ),
            'order' => array('likes' => 'desc', 'views' => 'desc', 'rating' => 'desc'),
            'with' => array('Pitch'),
            'limit' => 12,
            'page' => $this->request->id);
        if ($this->request->is('json')) {
            $solutions = Solution::all($params);
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
        $solutions = Solution::filterLogoSolutions(Solution::all($params));
        return compact('solutions', 'count', 'sort_tags', 'search_tags', 'data', 'total_count');
    }

    public function search_logo() {
        if ($this->request->is('json') && (isset($this->request->data['search_list']) || (isset($this->request->data['prop'])))) {
            //$words = explode(' ', preg_replace('/[^a-zа-яё]+/iu', ' ', trim($this->request->data['search'])));
            $words = $this->request->data['search_list'];
            $industries = array();
            $dict = array(
                'realty' => 'Недвижимость / Строительство',
                'auto' => 'Автомобили / Транспорт',
                'finances' => 'Финансы / Бизнес',
                'food' => 'Еда / Напитки',
                'adv' => 'Реклама / Коммуникации',
                'tourism' => 'Туризм / Путешествие',
                'sport' => 'Спорт',
                'sci' => 'Образование / Наука',
                'fashion' => 'Красота / Мода',
                'music' => 'Развлечение / Музыка',
                'culture' => 'Искусство / Культура',
                'animals' => 'Животные',
                'childs' => 'Дети',
                'security' => 'Охрана / Безопасность',
                'health' => 'Медицина / Здоровье'
            );
            $flippedDict = array_flip($dict);
            foreach($words as $key => $word) {
                if(preg_match('@\/@', $word)) {
                    if(isset($flippedDict[$word])) {
                        $industries[] = $flippedDict[$word];
                    }
                    $exploded = explode('/', $word);
                    foreach($exploded as $newWord) {
                        $newWord = trim($newWord);
                        $newWord = (mb_strtolower($newWord, 'utf-8'));
                        $words[] = $newWord;
                    }
                    unset($words[$key]);
                }
            }
            $tags_id = 0;
            if (!is_null($words)) {
                $tag_params = array('conditions' => array());
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
                $page = (isset($this->request->id) && !empty($this->request->id)) ? $this->request->id : 1;
                $tags = Tag::all($tag_params);
                if (count($tags) > 0) {
                    $tags_id = array_keys($tags->data());
                } else {
                    $tags_id = 0;
                }
            }
            $regexp = implode($words, '|');
            //$descriptionWord = array();
            //foreach($words as $word) {
            //    $descriptionWord[] = '%' . $word . '%';
            //}
            $descriptionWord = implode($words, ' ');

            $params = array('conditions' => array(
                array('OR' => array(
                    array("Pitch.title REGEXP '" . $regexp . "'"),
                    array("Pitch.description LIKE '%$descriptionWord%'"),
                )),
                'Solution.multiwinner' => 0,
                'Solution.awarded' => 0,
                'Pitch.awarded' => array('>' => date('Y-m-d H:i:s', time() - MONTH)),
                'Pitch.status' => array('>' => 0),
                'private' => 0,
                'category_id' => 1,
                'rating' => array('>=' => 3)
            ),
            'limit' => 30,
            'page' => $page,
            'order' => array('likes' => 'desc', 'views' => 'desc', 'rating' => 'desc'),
            'with' => array('Pitch'));

            if(!empty($industries)) {
                $params['conditions'][0]['OR'][] = array("Pitch.industry LIKE '%" . $industries[0] . "%'");
            }

            $totalParams = array('conditions' => array(
                array('OR' => array(
                    array("Pitch.title REGEXP '" . $regexp . "'"),
                    array("Pitch.description LIKE '%$descriptionWord%'"),
                )),
                'Solution.multiwinner' => 0,
                'Solution.awarded' => 0,
                'Pitch.awarded' => array('>' => date('Y-m-d H:i:s', time() - MONTH)),
                'Pitch.status' => array('>' => 0),
                'private' => 0,
                'category_id' => 1,
                'rating' => array('>=' => 3)
            ),
            'order' => array('likes' => 'desc', 'views' => 'desc', 'rating' => 'desc'),
            'with' => array('Pitch'));

            if(!empty($totalParams)) {
                $totalParams['conditions'][0]['OR'][] = array("Pitch.industry LIKE '%" . $industries[0] . "%'");
            }

            $solutions = Solution::all($params);
            if($page == 1) {
                $total_solutions = Solution::all($totalParams);
            }
            if ($solutions && count($solutions) > 0) {
                $black_list = array();
                $winnersArray = array();
                $solutionsArray = array();
                foreach ($solutions as $v) {
                    if(!in_array($v->pitch->awarded, $winnersArray)) {
                        $solutionsArray[] = $v->pitch->awarded;
                    }
                    if ($v->awarded) {
                        $winnersArray[] = $v->user_id;
                        $black_list[] = array('user' => $v->user_id, 'pitch' => $v->pitch_id);
                    }
                }
                foreach($solutionsArray as $winnerSolution) {
                    $sol = Solution::first($winnerSolution);
                    if(!in_array($sol->user_id, $winnersArray)) {
                        $winnersArray[] = $sol->user_id;
                    }
                }
                $prop = array();
                $variant = array();
                if (isset($this->request->data['prop'])) {
                    $prop = $this->request->data['prop'];
                }
                if (isset($this->request->data['variants'])) {
                    $variant = $this->request->data['variants'];
                }
                $solutions = $solutions->data();
                $removeCount = 0;
                foreach ($solutions as $k => $solution) {
                    $specific = unserialize($solution['pitch']['specifics']);
                    if (count($prop) > 0) {
                        $diff_prop = count(array_diff_assoc($prop, $specific['logo-properties']));
                    } else {
                        $diff_prop = false;
                    }
                    if (isset($specific['logoType']) && count($variant) > 0) {
                        $diff_variant = count(array_diff($specific['logoType'], $variant));
                    } else {
                        $diff_variant = false;
                    }
                    if ($diff_prop > 3 || $diff_variant == count($specific['logoType'])) {
                        $removeCount++;
                        unset($solutions[$k]);
                    } else {
                        foreach ($black_list as $v) {
                            if ($v['pitch'] == $solution['pitch_id'] && $v['user'] == $solution['user_id']) {
                                $removeCount++;
                                unset($solutions[$k]);
                            }
                        }
                    }
                    if(in_array($solution['user_id'], $winnersArray)) {
                        $removeCount++;
                        unset($solutions[$k]);
                    }
                    if(in_array($solution['id'], $solutionsArray)) {
                        $removeCount++;
                        unset($solutions[$k]);
                    }
                }
            }

            if($page == 1) {
                /*$solutionTags = Solutiontag::all(array('conditions' => array('tag_id' => $tags_id)));
                foreach($solutionTags as $tag) {
                    if(!isset($solutions[$tag->solution_id])) {
                        $temp = Solution::first(array(
                            'conditions' => array(
                                'Solution.id' => $tag->solution_id,
                                'Solution.multiwinner' => 0,
                                'Solution.awarded' => 0,
                                'Pitch.awarded' => array('>' => date('Y-m-d H:i:s', time() - MONTH)),
                                'Pitch.status' => array('>' => 0),
                                'Pitch.private' => 0,
                                'Pitch.category_id' => 1,
                                'Solution.rating' => array('>=' => 3)
                            ),
                            'with' => array('Pitch')
                        ));
                        if($temp) {
                            $solutions[$tag->solution_id] = $temp->data();
                        }
                    }
                }*/
            }

            if($page == 1) {
                if ($total_solutions && count($total_solutions) > 0) {
                    $black_list = array();
                    $winnersArray = array();
                    $solutionsArray = array();
                    foreach ($total_solutions as $v) {
                        if(!in_array($v->pitch->awarded, $winnersArray)) {
                            $solutionsArray[] = $v->pitch->awarded;
                        }
                        if ($v->awarded) {
                            $winnersArray[] = $v->user_id;
                            $black_list[] = array('user' => $v->user_id, 'pitch' => $v->pitch_id);
                        }
                    }
                    foreach($solutionsArray as $winnerSolution) {
                        $sol = Solution::first($winnerSolution);
                        if(!in_array($sol->user_id, $winnersArray)) {
                            $winnersArray[] = $sol->user_id;
                        }
                    }
                    $prop = array();
                    $variant = array();
                    if (isset($this->request->data['prop'])) {
                        $prop = $this->request->data['prop'];
                    }
                    if (isset($this->request->data['variants'])) {
                        $variant = $this->request->data['variants'];
                    }
                    $total_solutions = $total_solutions->data();
                    $removeCount = 0;
                    foreach ($total_solutions as $k => $solution) {
                        $specific = unserialize($solution['pitch']['specifics']);
                        if (count($prop) > 0) {
                            $diff_prop = count(array_diff_assoc($prop, $specific['logo-properties']));
                        } else {
                            $diff_prop = false;
                        }
                        if (isset($specific['logoType']) && count($variant) > 0) {
                            $diff_variant = count(array_diff($specific['logoType'], $variant));
                        } else {
                            $diff_variant = false;
                        }
                        //var_dump($specific['logoType']);
                        //var_dump($variant);
                        //var_dump($diff_variant);die();
                        if ($diff_prop > 3 || $diff_variant == count($specific['logoType'])) {
                            $removeCount++;
                            unset($total_solutions[$k]);
                        } else {
                            foreach ($black_list as $v) {
                                if ($v['pitch'] == $solution['pitch_id'] && $v['user'] == $solution['user_id']) {
                                    $removeCount++;
                                    unset($total_solutions[$k]);
                                }
                            }
                        }
                        if(in_array($solution['user_id'], $winnersArray)) {
                            $removeCount++;
                            unset($total_solutions[$k]);
                        }
                        if(in_array($solution['id'], $total_solutions)) {
                            $removeCount++;
                            unset($total_solutions[$k]);
                        }
                    }
                }
            }
        }
        $total_solutions = count($total_solutions);
        return compact('solutions', 'total_solutions');
    }

}
