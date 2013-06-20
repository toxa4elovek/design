<?php

namespace app\controllers;

use \app\models\Pitch;
use \app\models\Pitchfile;
use \app\models\Category;
use \app\models\Solution;
use \app\models\Comment;
use \app\models\User;
use \app\models\Receipt;
use \app\models\Request;
use \app\models\Expert;
use \app\models\Promocode;

use \lithium\storage\Session;
use \lithium\analysis\Logger;

class PitchesController extends \app\controllers\AppController {

	/**
     * Методы, доступные без аутентификации
     *
     * @var array
     */
	public $publicActions = array(
        'crowdsourcing', 'blank',  'promocode', 'index', 'printpitch', 'robots', 'fillbrief', 'finished', 'add', 'create', 'brief', 'activate', 'view', 'details', 'callback', 'viewsolution', 'getlatestsolution', 'getpitchdata'
	);


    public function agreement() {
        if(isset($this->request->params['id'])) {
            $pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->params['id']), 'with' => array('User')));
            if($pitch->private == 1) {
                return $this->render(array('layout' => false, 'data' => compact('pitch')));
            }else {
                return $this->redirect('/pitches/details/' . $this->request->params['id']);
            }
        }
        die();
    }

    public function participate() {
        $categories = Category::all();
        $pitchesId = User::getParticipatePitches(Session::read('user.id'));
        $data = array(
            'pitches' => array(),
            'info' => array(
                'page' => 1,
                'total' => 0
            ),
        );
        if(!empty($pitchesId)) {
            $pitchesId = array_keys($pitchesId);
            $allowedCategories = array();
            foreach($categories as $catI) {
                $allowedCategories[] = $catI->id;
            }
            $limit = 5;
            $page = 1;
            $types = array(
                'finished' => array('status' => 2),
                'current' => array()
            );
            if(isset($this->request->query['page'])) {
                $page = abs(intval($this->request->query['page']));
            }
            $conditions = array('Pitch.id' => $pitchesId);
            $conditions += $types['current'];
            /*******/
            $total = ceil(Pitch::count(array(
                'conditions' => $conditions,
            )) / $limit);
            $pitches = Pitch::all(array(
                'with' => 'Category',
                'conditions' => $conditions,
                'order' => array('started' => 'desc'),
                'limit' => $limit,
                'page' => $page,
            ));
            foreach($pitches as $pitch) {
                $pitch->winlink = false;
                if($pitch->status > 0) {
                    if($pitch->nominated > 0) {
                        $winnerSolution = Solution::first($pitch->nominated);
                    }elseif($pitch->awarded > 0) {
                        $winnerSolution = Solution::first($pitch->awarded);
                    }
                    if($winnerSolution->user_id == Session::read('user.id')) {
                        $pitch->winlink = true;
                    }
                }
                if($pitch->user_id == Session::read('user.id')) {
                    $pitch->winlink = true;
                }
            }
            $i = 1;
            $tempPitchList = $pitches->data();
            $pitchList = array();
            foreach($tempPitchList as &$pitch) {
                $pitch['sort'] = $i;
                $pitchList[] = $pitch;
                $i++;
            }
            $data = array(
                'pitches' => $pitchList,
                'info' => array(
                    'page' => $page,
                    'total' => $total
                ),
            );
        }
        $query = $this->request->query;
        return compact('data', 'categories', 'query', 'selectedCategory');
    }

    public function updatefiles() {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        if($pitch = Pitch::first($this->request->data['id'])) {
            $existingArray = unserialize($pitch->filesId);
            foreach($this->request->data['fileids'] as $key => $item) {
                if(!in_array($item, $existingArray)) {
                    $existingArray[] = $item;
                }
            }
            $fileIds = serialize($existingArray);
            $pitch->filesId = $fileIds;
            $res = $pitch->save();
            return $fileIds;
        }
        return 'false';
    }

	public function create() {
		$temp = Category::all();
		$categories = array();
		foreach($temp as $cat) {
			$categories[$cat->id] = $cat;
		}
		return compact('categories');
	}

    public function fillbrief() {
        Session::write('fillbrief', true);
        return $this->redirect('/pitches/create');
    }

	public function brief() {
		if(!$this->request->category) {
			return $this->redirect('Pitches::create');
		}
		if($category = Category::first($this->request->category)) {
            $experts = Expert::all(array('order' => array('id' => 'asc')));
			return compact('category', 'experts');
		}
		return $this->redirect('Pitches::create');
	}

	public function add () {
		if($this->request->data) {
			$featuresData = $this->request->data['features'];
			$commonPitchData = $this->request->data['commonPitchData'];
            if((int) $featuresData['award'] == 0) {
                return 'noaward';
            }
			if(!isset($commonPitchData['id'])) {
				$commonPitchData['id'] = 0;
			}
			if(!isset($commonPitchData['materials'])) {
				$commonPitchData['materials'] = 0;
				$commonPitchData['materials-limit'] = '';
			}
			if(!isset($commonPitchData['fileFormats'])) {
				$commonPitchData['fileFormats'] = array();
			}

			/*if(!isset($commonPitchData['fileFormatDesc'])) {
				$commonPitchData['fileFormatDesc'] = '';
			}*/
			$specificPitchData = $this->request->data['specificPitchData'];
			$pinned = $private = $social = $email = $brief = $timelimit = 0;
            $freePinned = false;
            if((isset($commonPitchData['promocode'])) && (!empty($commonPitchData['promocode']))) {
                $code = Promocode::first(array('conditions' => array('code' => $commonPitchData['promocode'])));
                if($code->type == 'pinned') {
                    $freePinned = true;
                }
            }

			if(($featuresData['pinned'] > 0) || ($freePinned == true)) {
				$pinned = 1;
			}
			if($featuresData['private'] > 0) {
				$private = 1;
			}
			if($featuresData['social'] > 0) {
				$social = 1;
			}
			if($featuresData['email'] > 0) {
				$email = 1;
			}
			if($featuresData['brief'] > 0) {
				$brief = 1;
			}
            if(!empty($commonPitchData['phone-brief'])) {
                //$brief = 1;
            }
            if((isset($featuresData['guaranteed'])) && ($featuresData['guaranteed'] > 0)) {
                $guaranteed = 1;
            }else {
                $guaranteed = 0;
            }
			if($featuresData['timelimitOption'] > 0) {
				$timelimit = abs(intval($featuresData['timelimitOption']));
				$validValues = array(0, 1, 2, 3 ,4);
                if(in_array($timelimit, $validValues)) {
                    $timelimit = $timelimit;
                }else {
                    $timelimit = 0;
                }
			}
			if(!isset($featuresData['experts'])) {
				$expert = 0;
				$expertId = serialize(array());
			}else {
				$expert = 1;
				$expertId = serialize($featuresData['experts']);
			}
			if(!isset($commonPitchData['filesId'])) {
				$commonPitchData['filesId'] = array();
			}
            $redirect = false;
            $edit = false;

			if($commonPitchData['id']) {
                $edit = true;
                $pitch = Pitch::first($commonPitchData['id']);
                if($pitch->billed == 1) {
                    $data = array(
                        'title' => $commonPitchData['title'],
                        'industry' => $commonPitchData['industry'],
                        'description' => $commonPitchData['description'],
                        'business-description' => $commonPitchData['business-description'],
                        'materials' => $commonPitchData['materials'],
                        'materials-limit' => $commonPitchData['materials-limit'],
                        'fileFormats' => serialize($commonPitchData['fileFormats']),
                        'fileFormatDesc' => $commonPitchData['fileFormatDesc'],
                        'filesId' => serialize($commonPitchData['filesId']),
                        'specifics' => serialize($specificPitchData),
                    );
                }else {
                    $data = array(
                        'title' => $commonPitchData['title'],
                        'industry' => $commonPitchData['industry'],
                        'description' => $commonPitchData['description'],
                        'business-description' => $commonPitchData['business-description'],
                        'price' => $featuresData['award'],
                        'pinned' => $pinned,
                        'expert' => $expert,
                        'private' => $private,
                        'social' => $social,
                        'expert-ids' => $expertId,
                        'email' => $email,
                        'timelimit' => $timelimit,
                        'brief' => $brief,
                        'phone-brief' => $commonPitchData['phone-brief'],
                        'guaranteed' => $guaranteed,
                        'materials' => $commonPitchData['materials'],
                        'materials-limit' => $commonPitchData['materials-limit'],
                        'fileFormats' => serialize($commonPitchData['fileFormats']),
                        'fileFormatDesc' => $commonPitchData['fileFormatDesc'],
                        'filesId' => serialize($commonPitchData['filesId']),
                        'specifics' => serialize($specificPitchData),
                    );
                }
			}else {
                $userId = Session::read('user.id');

                if(is_null($userId)) {
                    $userId = 0;
                    $redirect = true;
                    //временная заглушка
                    $redirect = false;

                }


				$data = array(
	                'user_id' => $userId,
					'category_id' => $commonPitchData['category_id'],
					'title' => $commonPitchData['title'],
					'industry' => $commonPitchData['industry'],
					'description' => $commonPitchData['description'],
					'business-description' => $commonPitchData['business-description'],
					'started' => date('Y-m-d H:i:s'),
					'ideas_count' => 0,
					'price' => $featuresData['award'],
					'status' => 0,
					'pinned' => $pinned,
					'expert' => $expert,
					'private' => $private,
					'social' => $social,
					'expert-ids' => $expertId,
					'email' => $email,
					'timelimit' => $timelimit,
					'brief' => $brief,
					'phone-brief' => $commonPitchData['phone-brief'],
                    'guaranteed' => $guaranteed,
					'materials' => $commonPitchData['materials'],
					'materials-limit' => $commonPitchData['materials-limit'],
					'fileFormats' => serialize($commonPitchData['fileFormats']),
					'fileFormatDesc' => $commonPitchData['fileFormatDesc'],
					'filesId' => serialize($commonPitchData['filesId']),
					'specifics' => serialize($specificPitchData),
				);
			}
			if(!$pitch = Pitch::first(array('conditions' => array('id' => $commonPitchData['id'])))) {
				$pitch = Pitch::create();
			}
			$pitch->set($data);
			if($pitch->save()) {
                if(($edit == true) && ($pitch->published == 1)) {
                    $message = 'Друзья, в брифе возникли изменения, и мы убедительно просим вас с ними ознакомиться.';
                    $admin = $admin = User::getAdmin();
                    $data = array('pitch_id' => $pitch->id, 'reply_to' => 0, 'user_id' => $admin, 'text' => $message);
                    Comment::createComment($data);
                }

                if($code) {
                    $code->pitch_id = $pitch->id;
                    $code->save();
                }
            }

            $this->request->data['commonPitchData']['id'] = $pitch->id;
            // Receipt here
            if($pitch->billed == 0) {
			    Receipt::createReceipt($this->request->data);
			    $total = Receipt::findTotal($pitch->id);
			    $pitch->total = $total;
            }
			$pitch->save();
            Logger::write('debug', serialize($pitch->data()));
            if($redirect == true) {
                Session::write('temppitch', $pitch->id);
                return 'redirect';
            }
			Session::write('unpublished.pitch', $pitch->id);
			return $pitch->id;
		}		
	}

    public function edit() {
        if(($pitch = Pitch::first($this->request->id)) && (($pitch->user_id == Session::read('user.id')) || (Session::read('user.isAdmin') == 1|| (in_array(Session::read('user.id'), User::$admins))))) {
            $category = Category::first($pitch->category_id);
            $files = array();
            if(count(unserialize($pitch->filesId)) > 0) {
            	$files = Pitchfile::all(array('conditions' => array('id' => unserialize($pitch->filesId))));
            }
            $code = Promocode::first(array('conditions' => array('pitch_id' => $pitch->id)));
            $experts = Expert::all(array('order' => array('id' => 'asc')));
            return compact('pitch', 'category', 'files', 'experts', 'code');
        }
    }


    public function robots() {
        $pitches = Pitch::all(array('conditions' => array('private' => 1)));
        $text = 'User-agent: *';
        foreach($pitches->data() as $pitch):
        $text .= '
Disallow: /pitches/view/' . $pitch['id'] . '
Disallow: /pitches/details/'. $pitch['id'] . '
Disallow: /pitches/upload/' . $pitch['id'];
        endforeach;
        file_put_contents(LITHIUM_APP_PATH . '/webroot/robots.txt', $text);
        die();
    }

    public function details() {
        if($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) {
            $allpitches = Pitch::all(array('conditions' => array('status' => 0, 'published' => 1), 'order' => array(
                'price' => 'desc',
                'started' => 'desc'
            )));
            $first = null;
            $flag = false;
            $i = 0;
            $count = count($allpitches);
            foreach($allpitches as $cpitch) {
                if($i == 0) $first = $cpitch;

                if($flag == true) {
                    $prevpitch = $cpitch;
                    break;
                }
                if($cpitch->id == $pitch->id) {
                    $flag = true;
                    if(($count - 1) == $i) {
                        $prevpitch = $first;
                    }
                }
                $i ++;
            }

            $currentUser = Session::read('user.id');
            if(($pitch->published == 0) && (($currentUser != $pitch->user_id) && ($currentUser['isAdmin'] != 1) && (!in_array($currentUser['id'], User::$admins)))) {
                return $this->redirect('/pitches');
            }
            if($pitch->private == 1) {
                if(($pitch->user_id != Session::read('user.id')) && (!in_array(Session::read('user.id'), User::$admins)) && (!$isExists = Request::first(array('conditions' => array('user_id' => Session::read('user.id'), 'pitch_id' => $pitch->id))))) {
                    return $this->redirect('/requests/sign/' . $pitch->id);
                }
            }
            $pitch->views += 1;
            $pitch->save();

            $fileIds = unserialize($pitch->filesId);
            $files = array();
            $comments = Comment::all(array('conditions' => array('pitch_id' => $this->request->id), 'order' => array('Comment.created' => 'desc'), 'with' => array('User')));
            if(!empty($fileIds)) {
                $files = Pitchfile::all(array('conditions' => array('id' => $fileIds)));
            }
            if(is_null($this->request->env('HTTP_X_REQUESTED_WITH'))){
                return compact('pitch', 'files', 'comments', 'prevpitch');
            }else {
                //return compact('pitch', 'files');
                return $this->render(array('layout' => false, 'data' => compact('pitch', 'files', 'comments')));
            }
        }
    }



}