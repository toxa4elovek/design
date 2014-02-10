<?php

namespace app\controllers;

use \app\models\User;
use \app\models\Sendemail;
use \app\models\Category;
use \app\models\Solution;
use \app\models\Wincomment;
use \app\models\Grade;
use \app\models\Pitch;
use \app\models\Event;
use \app\models\Invite;
use \app\models\Avatar;
use \app\models\Moderation;
use \app\extensions\mailers\UserMailer;
use \app\extensions\mailers\SpamMailer;
use \app\extensions\mailers\ContactMailer;
use \lithium\storage\Session;
use \lithium\security\Auth;
use \li3_flash_message\extensions\storage\FlashMessage;
use \lithium\util\String;
use \lithium\analysis\Logger;
use app\extensions\storage\Rcache;
use \tmhOAuth\tmhOAuth;
use \tmhOAuth\tmhUtilities;
use \Exception;

class UsersController extends \app\controllers\AppController {

    /**
     * Методы, доступные без аутентификации
     *
     * @var array
     */
	public $publicActions = array(
		'vklogin', 'unsubscribe', 'registration', 'login', /*'info', 'sendmail', */'confirm', 'checkform', 'recover', 'setnewpassword', 'loginasadmin', 'view', 'updatetwitter', 'banned', 'activation', 'need_activation', 'requesthelp', 'testemail'
	);

    public $nominatedCount = false;

    public function _init() {
        parent::_init();
        //var_dump($_COOKIE);
        $withMenu = array(
            'office',
            'solutions',
            'awarded',
            'nominated',
            'step1',
            'step2',
            'step3',
            'step4',
            'mypitches'
        );
        if(in_array($this->request->action, $withMenu)) {
            $myPitches = Pitch::all(array(
                'conditions' => array('user_id' => Session::read('user.id')),

            ));
            $pitchIds = array();
            foreach($myPitches as $pitch) {
                $pitchIds[] = $pitch->id;
            }
            $solutionsFromMyPitches = array();
            if(!empty($pitchIds)) {
                $solutionsFromMyPitches = Solution::all(array('conditions' => array('pitch_id' => $pitchIds, 'nominated' => '1')));
            }

            $conditions = array('Solution.user_id' => Session::read('user.id'), 'nominated' => '1', 'status' => array('<' => 2));
            $solutions = Solution::all(array('conditions' => $conditions, 'with' => array('Pitch')));

            if(count($solutionsFromMyPitches) > 0) {
                $solutions = array_merge($solutions->data(), $solutionsFromMyPitches->data());
            }else {
                $solutions = $solutions->data();
            }
            $this->nominatedCount = count($solutions);
        }
    }

    public function avatar() {
        $allowedExtensions = array('png', 'gif', 'jpeg', 'jpg');
        // max file size in bytes
        $sizeLimit = 100 * 1024 * 1024;
        if($this->request->env('REQUEST_METHOD') == 'POST') {
            $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
            $result = $uploader->handleUpload(LITHIUM_APP_PATH . '/webroot/avatars/');
            if($result['success']) {
                //Avatar::remove(array('model_id' => Session::read('user.id')));
                Avatar::clearOldAvatars(Session::read('user.id'));
                $user = User::first(Session::read('user.id'));
                $user->set(array('avatar' => array('name' => $result['name'], 'tmp_name' => $result['tmpname'], 'error' => 0)));
                $user->save();
                $user = User::first(Session::read('user.id'));
                unlink($result['tmpname']);
                return array('result' => 'true', 'data' => $user->data());
            }
        }
    }

    public function sendmail() {
        $data = array('user' => User::first(Session::read('user.id')), 'pitch' => Pitch::first());
        $res = SpamMailer::newpitch($data);
        $mail = Sendemail::first(array('conditions' => array('id' => $this->request->id), 'order' => array('id' => 'desc')));

            echo $mail->text;

        die();


        /*if(Session::read('user.email') == $mail->email) {
            echo $mail->text;
            die();
        }else {
            return $this->redirect('Users::office');
        }*/
    }


    /**
     * Кабинет
     *
     */
	public function office() {
        $date = date('Y-m-d H:i:s');
        if((Session::read('user.id' > 0)) && (Session::read('user.events') != null)) {
            $date = Session::read('user.events.date');
            Session::delete('user.events');
        }
        $gallery = Solution::getUserSolutionGallery(Session::read('user.id'));
        $winnersData = Solution::all(array('conditions' => array('Solution.awarded' => 1, 'Pitch.private' => 0), 'order' => array('Solution.created' => 'desc'), 'limit' => 50,  'with' => array('Pitch')));
        $winners = array();
        foreach($winnersData as $winner) {
            if($winner->pitch->category_id != 7) {
                $winners[] = $winner;
            }
        }
        $winners = array();
        $updates = Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), 1, null);
        if(is_null($this->request->env('HTTP_X_REQUESTED_WITH'))){
            return compact('gallery', 'winners', 'date', 'updates');
        }else {
            return $this->render(array('layout' => false, 'data' => compact('gallery', 'winners', 'date', 'updates')));
        }
	}

	public function referal() {
	    $user = User::first(Session::read('user.id'));
	    if (empty($user->referal_token)) {
	        $user->referal_token = User::generateReferalToken();
	        $user->save(null, array('validate' => false));
	    }
	    $refPitches = Pitch::all(array(
	        'conditions' => array(
	            'user_id' => array(
	               '!=' => 0,
	            ),
	            'referal' => $user->id
	        ),
	        'with' => array('User'),
	    ));
	    if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
	        return compact('user', 'refPitches');
	    } else {
	        return $this->render(array('layout' => false, 'data' => compact('user', 'refPitches')));
	    }
	}

	public function deletePhone() {
	    if ($this->request->is('json') && (Session::read('user.id') > 0) && ($user = User::first((int) Session::read('user.id')))) {
	        $user->phone = 0;
	        $user->phone_operator = 0;
	        $user->phone_code = 0;
	        $user->phone_valid = 0;
	        $user->save(null, array('validate' => false));
	        $result = array(
	            'code' => true,
	            'phone' => 0,
	            'phone_valid' => 0,
	        );
	        return $result;
	    }
	    $this->redirect('/');
	}

    public function solutions() {
        $conditions = array('Solution.user_id' => Session::read('user.id'));
        $solutions = Solution::all(array('conditions' => $conditions, 'with' => array('Pitch')));
        $nominatedCount = $this->nominatedCount;
        $myPitches = Pitch::all(array('conditions' => array('user_id' => Session::read('user.id'), 'published' => 1, 'billed' => 1)));

        if($myPitches->data()) {
            $idList = array();
            foreach($myPitches as $pitch) {
                $idList[] = $pitch->id;
            }
            $solutions = Solution::all(array('conditions' => array('pitch_id' => $idList), 'order' => array('Solution.id' => 'desc'), 'with' => array('Pitch')));
        }
        $filterType = 'solutions';
        if(is_null($this->request->env('HTTP_X_REQUESTED_WITH'))){
            return compact('solutions', 'filterType', 'nominatedCount');
        }else{
            return $this->render(array('layout' => false, 'data' => compact('solutions', 'filterType', 'nominatedCount')));
        }
    }

    public function awarded() {
        $myPitches = Pitch::all(array(
            'conditions' => array('user_id' => Session::read('user.id')),

        ));
        $pitchIds = array();
        foreach($myPitches as $pitch) {
            $pitchIds[] = $pitch->id;
        }
        $solutionsFromMyPitches = array();
        if(!empty($pitchIds)) {
            $solutionsFromMyPitches = Solution::all(array('conditions' => array('pitch_id' => $pitchIds, 'Solution.awarded' => '1'), 'with' => array('Pitch')));
        }

        $conditions = array('Solution.user_id' => Session::read('user.id'), 'Solution.awarded' => 1, 'Solution.nominated' => 0);
        $solutions = Solution::all(array('conditions' => $conditions, 'with' => array('Pitch')));

        if(count($solutionsFromMyPitches) > 0) {
            $solutions = array_merge($solutions->data(), $solutionsFromMyPitches->data());
        }else {
            $solutions = $solutions->data();
        }

        $filterType = 'awarded';

        if(is_null($this->request->env('HTTP_X_REQUESTED_WITH'))){
            return compact('solutions', 'filterType');
        }else {
            return $this->render(array('layout' => false, 'data' => compact('solutions', 'filterType')));
        }
    }

    public function nominated() {
        $myPitches = Pitch::all(array(
            'conditions' => array('user_id' => Session::read('user.id')),

        ));
        $pitchIds = array();
        foreach($myPitches as $pitch) {
            $pitchIds[] = $pitch->id;
        }
        $solutionsFromMyPitches = array();
        if(!empty($pitchIds)) {
            $solutionsFromMyPitches = Solution::all(array('conditions' => array('pitch_id' => $pitchIds, 'nominated' => '1', 'Solution.awarded' => '0'), 'with' => array('Pitch')));
        }

        $conditions = array('Solution.user_id' => Session::read('user.id'), 'Solution.nominated' => '1');
        $solutions = Solution::all(array('conditions' => $conditions, 'with' => array('Pitch')));

        if(count($solutionsFromMyPitches) > 0) {
            $solutions = array_merge($solutions->data(), $solutionsFromMyPitches->data());
        }else {
            $solutions = $solutions->data();
        }
        $filterType = 'nominated';
        if(is_null($this->request->env('HTTP_X_REQUESTED_WITH'))){
            return compact('solutions', 'filterType');
        }else {
            return $this->render(array('layout' => false, 'data' => compact('solutions', 'filterType')));
        }
    }

    public function testemail () {
        $email = Sendemail::all(array('fields' => array('count(id)', 'created'), 'conditions' =>  array('created' => array('>=' => '2013-02-20 00:00:00')), 'group' => array('created')));
        echo '<pre>';
        var_dump($email->data());
        echo '</pre>';
        die();
    }

    public function step1() {
        if(($solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('Pitch', 'User')))) && ($solution->nominated == 1 || $solution->awarded == 1)) {
            if((Session::read('user.id') != $solution->user_id) && (Session::read('user.isAdmin') != 1) && (Session::read('user.id') != $solution->pitch->user_id)) {
                return $this->redirect('Users::office');
            }
            if(Session::read('user.id') == $solution->pitch->user_id) {
                return $this->redirect(array('controller' => 'users', 'action' => 'step2', 'id' => $solution->id));
            }
            $solution->pitch->category = Category::first($solution->pitch->category_id);
            if($solution->user_id == Session::read('user.id')) {
                $type = 'designer';
            }else {
                $type = 'client';
            }
            if(Session::read('user.isAdmin') == 1) {
                $type = 'admin';
            }
            $step = 1;
            return compact('type', 'solution', 'step');
        }
    }

    public function step2() {
        \lithium\net\http\Media::type('json', array('text/html'));
        if(($solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('Pitch', 'User')))) && ($solution->nominated == 1 || $solution->awarded == 1)) {
            if((Session::read('user.id') != $solution->user_id) && (Session::read('user.isAdmin') != 1) && (Session::read('user.id') != $solution->pitch->user_id)) {
                return $this->redirect('Users::office');
            }
            $solution->pitch->category = Category::first($solution->pitch->category_id);
            if($solution->user_id == Session::read('user.id')) {
                $type = 'designer';
                $messageTo = User::first($solution->pitch->user_id);
            }else {
                $type = 'client';
                $messageTo = User::first($solution->user_id);
            }
            if(Session::read('user.isAdmin') == 1) {
                $type = 'admin';
                $messageTo = User::first($solution->pitch->user_id);
            }
            if($this->request->data) {
                $newComment = Wincomment::create();
                if(count($this->request->data['file']) > 0) {
                    foreach($this->request->data['file'] as $index => &$file) {
                        if($file['error'] > 0) {
                            unset($this->request->data['file'][$index]);
                        }
                    }
                }
                if(empty($this->request->data['file'])) {
                    unset($this->request->data['file']);
                }
                $newComment->set($this->request->data);
                $newComment->user_id = Session::read('user.id');
                $newComment->solution_id =$solution->id;
                $newComment->created = date('Y-m-d H:i:s');
                $newComment->step = 2;
                $newComment->save();
                if($type == 'designer') {
                    $recipient = User::first($solution->pitch->user_id);
                }else{
                    $recipient = User::first($solution->user_id);
                }
                User::sendSpamWincomment($newComment, $recipient);
            }
            $files = array();
            $comments = Wincomment::all(array('conditions' => array('step' => 2, 'solution_id' => $solution->id), 'order' => array('created' => 'desc'), 'with' => array('User')));
            foreach($comments as $comment) {
                if($comment->user_id == $solution->user_id) {
                    $comment->type = 'designer';
                    if(!empty($comment->images)) {
                        $files[] = $comment->images;
                    }
                }else {
                    $comment->type = 'client';
                }
            }
            $step = 2;
            if(empty($files)) {
                $nofiles = true;
            }else {
                $nofiles = false;
            }
            return compact('type', 'solution', 'comments', 'step', 'nofiles', 'messageTo');
        }
    }

    public function step3() {
        \lithium\net\http\Media::type('json', array('text/html'));
        if(($solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('Pitch', 'User')))) && ($solution->nominated == 1 || $solution->awarded == 1)) {
            if(($this->request->params['confirm']) && ($this->request->params['confirm'] == 'confirm') &&
                ((Session::read('user.isAdmin') == 1) || (Session::read('user.id') == $solution->pitch->user_id)) && ($solution->step < 3)) {
                $user = User::first($solution->user_id);
                User::sendSpamWinstep($user, $solution, '3');
                $solution->step = 3;
                $solution->save();
                return $this->redirect(array('controller' => 'users', 'action' => 'step3', 'id' => $solution->id));
            }

            if((Session::read('user.id') != $solution->user_id) && (Session::read('user.isAdmin') != 1) && (Session::read('user.id') != $solution->pitch->user_id)) {
                return $this->redirect('Users::office');
            }
            if($solution->step < 3) {
                return $this->redirect(array('controller' => 'users', 'action' => 'step2', 'id' => $this->request->id));
            }
            if (($solution->pitch->category_id == 7) && ($solution->step == 4)) {
                return $this->redirect('/users/step4/' . $solution->id);
            }
            $solution->pitch->category = Category::first($solution->pitch->category_id);
            if($solution->user_id == Session::read('user.id')) {
                $type = 'designer';
                $messageTo = User::first($solution->pitch->user_id);
            }else {
                $type = 'client';
                $messageTo = User::first($solution->user_id);
            }
            if(Session::read('user.isAdmin') == 1) {
                $type = 'admin';
                $messageTo = User::first($solution->pitch->user_id);
            }
            if($this->request->data) {
                $newComment = Wincomment::create();
                if(count($this->request->data['file']) > 0) {
                    foreach($this->request->data['file'] as $index => &$file) {
                        if($file['error'] > 0) {
                            unset($this->request->data['file'][$index]);
                        }
                    }
                }
                if(empty($this->request->data['file'])) {
                    unset($this->request->data['file']);
                }
                $newComment->set($this->request->data);
                $newComment->user_id = Session::read('user.id');
                $newComment->solution_id =$solution->id;
                $newComment->created = date('Y-m-d H:i:s');
                $newComment->step = 3;
                $newComment->save();
                if($type == 'designer') {
                    $recipient = User::first($solution->pitch->user_id);
                }else{
                    $recipient = User::first($solution->user_id);
                }
                User::sendSpamWincomment($newComment, $recipient);
            }
            $comments = Wincomment::all(array('conditions' => array('step' => 3, 'solution_id' => $solution->id), 'order' => array('created' => 'desc'), 'with' => array('User')));
            $files = array();
            foreach($comments as $comment) {

                if($comment->user_id == $solution->user_id) {
                    $comment->type = 'designer';
                    if(!empty($comment->images)) {
                        $files[] = $comment->images;
                    }
                }else {
                    $comment->type = 'client';
                }
            }
            $step = 3;
            if(empty($files)) {
                $nofiles = true;
            }else {
                $nofiles = false;
            }
            return compact('type', 'solution', 'comments', 'step', 'nofiles', 'messageTo');
        }
    }

    public function step4() {
        if(($solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('Pitch', 'User'))))) {
            if(($this->request->params['confirm']) && ($this->request->params['confirm'] == 'confirm') &&
                ((Session::read('user.id') == $solution->pitch->user_id) || (Session::read('user.isAdmin') == 1)) && ($solution->step == 3)) {
                $user = User::first($solution->user_id);
                if (Session::read('user.isAdmin') == 1) {
                    User::sendSpamWinstepGo($user, $solution, '4');
                } else {
                    User::sendSpamWinstep($user, $solution, '4');
                }
                $solution->step = 4;
                $solution->save();
                Pitch::finishPitch($solution->pitch_id);
                return $this->redirect(array('controller' => 'users', 'action' => 'step4', 'id' => $solution->id));
            }

            if(($this->request->params['confirm']) && ($this->request->params['confirm'] == 'confirm') &&
                ((Session::read('user.id') == $solution->pitch->user_id) || (Session::read('user.isAdmin') == 1)) && ($solution->pitch->category_id == 7)) {
                $user = User::first($solution->user_id);
                if (Session::read('user.isAdmin') == 1) {
                    User::sendSpamWinstepGo($user, $solution, '4');
                } else {
                    User::sendSpamWinstep($user, $solution, '4');
                }
                $solution->step = 4;
                $solution->save();
                Pitch::finishPitch($solution->pitch_id);
                return $this->redirect(array('controller' => 'users', 'action' => 'step4', 'id' => $solution->id));
            }

            if((Session::read('user.id') != $solution->user_id) && (Session::read('user.isAdmin') != 1) && (Session::read('user.id') != $solution->pitch->user_id)) {
                return $this->redirect('Users::office');
            }
            if($solution->step < 4) {
                return $this->redirect(array('controller' => 'users', 'action' => 'step3', 'id' => $this->request->id));
            }
            $solution->pitch->category = Category::first($solution->pitch->category_id);
            if($solution->user_id == Session::read('user.id')) {
                $type = 'designer';
                $gradeByOtherParty = Grade::first(array('conditions' => array('user_id' => $solution->pitch->user_id, 'pitch_id' => $solution->pitch->id)));
            }else {
                $type = 'client';
                $gradeByOtherParty = Grade::first(array('conditions' => array('user_id' => $solution->user_id, 'pitch_id' => $solution->pitch->id)));
            }
            if(Session::read('user.isAdmin') == 1) {
                $type = 'admin';
            }
            $grade = Grade::first(array('conditions' => array('user_id' => Session::read('user.id'), 'pitch_id' => $solution->pitch->id, 'type' => $type)));
            if($this->request->data) {
                $grade = Grade::create();
                $grade->set($this->request->data);
                $grade->pitch_id = $solution->pitch->id;
                $grade->user_id = Session::read('user.id');
                $grade->type = $type;
                $grade->save();
                if($gradeByOtherParty) {
                    $solution->nominated = 0;
                    $solution->awarded = 1;
                    $solution->save();
                }
            }

            $step = 4;
            return compact('type', 'solution', 'comments', 'step', 'grade');
        }
    }

    public function mypitches() {
        if(!is_null($this->request->env('HTTP_X_REQUESTED_WITH'))){
            return $this->render(array('layout' => false));
        }
    }

    public function viewmail() {
        $mail = Sendemail::findByHash($this->request->id);
        if(Session::read('user.email') == $mail->email) {
            echo $mail->text;
            die();
        }else {
            return $this->redirect('Users::office');
        }
    }

    public function suicide() {
        $id = Session::read('user.id');
        User::remove($id);
        Auth::clear('user');
        return $this->redirect('/');
    }

    public function unsubscribe() {
        if (($this->request->query) && (isset($this->request->query['token'])) && (isset($this->request->query['from']))) {
            $email = base64_decode($this->request->query['from']);
            if ($user = User::first(array('conditions' => array('email' => $email)))) {
                if (sha1($user->id . $user->created) == base64_decode($this->request->query['token'])) {
                    $user->email_newpitch = 0;
                    $user->email_newcomments = 0;
                    $user->email_newpitchonce = 0;
                    $user->email_newsolonce = 0;
                    $user->email_newsol = 0;
                    $user->email_digest = 0;
                    $user->save(null, array('validate' => false));
                    Auth::set('user', $user->data());
                    return $this->redirect('/users/profile');
                }
            }
        }
        return $this->render(array('layout' => 'default', 'data' => false));
    }

    /**
     * Метод регистрации
     *
     *
     * @return array|\lithium\action\Returns|object
     */
	public function registration() {
        if(Session::read('user')) {
            return $this->redirect('/');
        }
        /*if(((!isset($this->request->query['invite'])) && (!isset($this->request->data['id']))) || ((isset($this->request->query['invite'])) && (!$isValid = Invite::isValidInvite($this->request->query['invite'])))) {
            return $this->redirect('Invites::index');
        }  */
        $user = User::create();
		if($this->request->data) {
            // фейсбук регисстрация
            if((isset($this->request->data['id'])) && (isset($this->request->data['name']))) {
                // регился ли пользователей через обычную регистрацию?
                if($isUserExists = $user->isUserExistsByEmail($this->request->data['email'])) {
                    // если он уже регился обычным способом, сохраняем его фейсбук айди
                    $userToLog = User::first(array('conditions' => array('email' => $this->request->data['email'])));
                    $userToLog->facebook_uid = $this->request->data['id'];
                    if (!Avatar::first(array('conditions' => array('model_id' => $userToLog->id)))) {
                        $userToLog->getFbAvatar();
                    }
                    $userToLog->save();
                }else{
                    // регился ли пользователей через фейсбук?
                    $this->request->data['facebook_uid'] = $this->request->data['id'];
                    unset($this->request->data['id']);
                    $isFBUserExists = $user->checkFacebookUser($this->request->data);
                    if(!$isFBUserExists) {
                        // если пользователей фейсбука у нас отсутствует, то сохраняем его в базу
                        if($user->saveFacebookUser($this->request->data)) {
                            $userToLog = User::first(array('conditions' => array('facebook_uid' => $this->request->data['facebook_uid'])));
                            $userToLog->setLastActionTime();
                            $userToLog->getFbAvatar();
                            UserMailer::hi_mail($userToLog);
                            $newuser = true;
                        }else {
                            return $this->redirect('Users::login');
                        }

                    }else {
                        // если он уже у нас есть, то вытаскиваем все его данные по айди
                        $userToLog = User::first(array('conditions' => array('facebook_uid' => $this->request->data['facebook_uid'])));
                        $newuser = false;
                    }
                }
                if($userToLog->banned) {
                    Auth::clear('user');
                    return array('data' => true, 'redirect' => '/users/banned');
                }
                if($userToLog->active == 0) {
                    Auth::clear('user');
                    return array('data' => true, 'redirect' => '/users/login');
                }
                $userToLog->token = User::generateToken();
                if (isset($this->request->data['accessToken'])) {
                    $userToLog->accessToken = $this->request->data['accessToken'];
                }
                setcookie('autologindata', 'id=' . $userToLog->id . '&token=' . sha1($userToLog->token), time() + strtotime('+1 month'), '/');
                $userToLog->lastTimeOnline = date('Y-m-d H:i:s');
                $userToLog->save(null, array('validate' => false));
                // производим аутентификацию
                Auth::set('user', $userToLog->data());
                // отдаем тру для того, чтобы джаваскрипт сделал редирект
                $redirect = false;

                $pitchId = Session::read('temppitch');
                if(!is_null($pitchId)) {
                    if($pitch = Pitch::first($pitchId)) {
                        $pitch->user_id = $userToLog->id;
                        $pitch->save();
                    }
                    Session::delete('temppitch');
                    $redirect = '/pitches/edit/' . $pitchId . '#step3';
                }
                if(!is_null(Session::read('redirect'))) {
                    $redirect = Session::read('redirect');
                    Session::delete('redirect');
                }
                return array('data' => true, 'redirect' => $redirect, 'newuser' => $newuser);
            }else {
                // обычная регистрация
                if (!isset($this->request->data['case']) || $this->request->data['case'] != 'fu27fwkospf') { // Check for bots
                    return $this->redirect('/');
                }
                $user->token = User::generateToken();
                $user->created = date('Y-m-d H:i:s');

                $user->set($this->request->data) ;
			    if(($user->validates()) && ($user->save($this->request->data))) {
                    $userToLog = User::first(array('conditions' => array('id' => $user->id)));
                    $userToLog->lastTimeOnline = date('Y-m-d H:i:s');
                    $userToLog->setLastActionTime();
                    $res = UserMailer::verification_mail($userToLog);
                    // производим аутентификацию
                    Auth::set('user', $userToLog->data());

                    $pitchId = Session::read('temppitch');
                    if(!is_null($pitchId)) {
                        if($pitch = Pitch::first($pitchId)) {
                           $pitch->user_id = $userToLog->id;
                           $pitch->save();
                        }
                        Session::delete('temppitch');
                        return $this->redirect('/pitches/edit/' . $pitchId . '#step3');
                    }
                    return $this->redirect('/');
			    }

            }
		}
        /*$invite = false;
        if(isset($this->request->query['invite'])) {
            $invite = $this->request->query['invite'];
        }*/
        $url = 'http://oauth.vk.com/authorize';

        $client_id = '2950889'; // ID приложения
        $client_secret = 'j1bFzKfXP4lIa0wA7vaV'; // Защищённый ключ
        $redirect_uri = 'http://www.godesigner.ru/users/vklogin'; // Адрес сайта


        $params = array(
            'client_id'     => $client_id,
            'redirect_uri'  => $redirect_uri,
            'scope' => 'friends',
            'response_type' => 'code'
        );

		return compact('user', 'invite', 'params', 'url');
	}


    /**
     *  Метод входа, устанавлививет сессию и делает редирект в рабочий кабинет
     *
     *
     * @return \lithium\action\Returns|object*
     */
	public function login() {
        $user = User::create();
		if($this->request->data) {
			$this->request->data['password'] = String::hash($this->request->data['password']);
	        if (Auth::check('user', $this->request, array('checkSession' => true))) {

                $userToLog = User::first(Session::read('user.id'));
                /*if(!$userToLog->invited) {
                    Session::clear();
                    return $this->redirect("Users::login");
                } */
                if($userToLog->banned) {
                    Auth::clear('user');
                    return $this->redirect('/users/banned');
                }
                if($userToLog->active == 0) {
                    Auth::clear('user');
                    return array('data' => true, 'redirect' => '/users/login');
                }
                $userToLog->lastTimeOnline = date('Y-m-d H:i:s');
                if((isset($this->request->data['remember'])) && ($this->request->data['remember'] == 'on')) {
                    $userToLog->token = User::generateToken();
                    setcookie('autologindata', 'id=' . $userToLog->id . '&token=' . sha1($userToLog->token), time() + strtotime('+1 month'), '/');
                }
                $userToLog->save(null, array('validate' => false));
                //var_dump(Session::read('redirect'));die();
                if(!is_null(Session::read('redirect'))) {
                    $red = Session::read('redirect');
                    Session::delete('redirect');
                    return $this->redirect($red);
                }else {
	                return $this->redirect('Users::office');
                }
	        }else{
				Session::write('flash.login', 'Неверный адрес почты или пароль.');
				return $this->redirect("Users::login");
	        }
        }
        if (is_null(Session::read('redirect')) && !is_null($_SERVER['HTTP_REFERER'])) {
            Session::write('redirect', $_SERVER['HTTP_REFERER']);
        }
        if(!is_null(Session::read('user.id'))) {
            return $this->redirect('Users::office');
        }
        return compact('user');
	}

    public function banned() {

    }

    public function need_activation() {

    }

    public function resend() {
        $user = User::first(Session::read('user.id'));
        $user->token = User::generateToken();
        $user->save(null, array('validate' => false));
        UserMailer::verification_mail($user);
        return true;
    }

    /**
     *  Метод выхода, удаляем сессию и делаем редирект на главную страницу
     *
     *
     * */
	public function logout() {
        setcookie("autologindata", "", time()-3600000, '/');
        Auth::clear('user');
        return $this->redirect('/');
    }

    /**
     * Метод подтверждения почтового адреса пользователя
     *
     * @return array|\lithium\action\Returns|object
     */
    public function confirm() {
        if(isset($this->request->query['token'])) {
            $user = User::first(array('conditions' => array('token' => $this->request->query['token'])));
            if($user) {
                $user->activateUser();
                UserMailer::hi_mail($user);
                Auth::clear('user');
                Auth::set('user', $user->data());
                return $this->redirect('Users::office');
            }else {
                return $this->redirect('Users::registration');
            }
        }else {
            return $this->redirect('Users::registration');
        }
    }

    /**
     * Метод проверки существования имейла
     *
     * @TODO Cooldown timer
     */
    public function checkform() {
        if(isset($this->request->data['email'])) {
            if($user = User::find('first', array('conditions' => array('email' => $this->request->data['email'])))) {
                return array('data' => true);
            }
        }
        return array('data' => false);
    }

    public function setnewpassword() {
        $token = $this->request->query['token'];
        if(isset($token)) {
            $user = User::first(array('conditions' => array('token' => $token)));
            if($user) {
                Auth::set('user', $user->data());
                if($this->request->data) {
                    $errors = array();
                    if(empty($this->request->data['password'])) {
                        $errors['password'] = 'Пароль пустой';
                    }
                    if(($this->request->data['password']) != ($this->request->data['confirm_password'])) {
                        $errors['password'] = 'Пароли не совпадают';
                    }
                    if(empty($errors)) {
                        $user->password = String::hash($this->request->data['password']);
                        $user->token = '';
                        $user->success = true;
                        $user->save(null, array('validate' => false));
                    }
                }
                return compact('token', 'user', 'errors');
            }else {
                return $this->redirect('Users::registration');
            }
        }else {
            return $this->redirect('Users::registration');
        }
    }

    public function second() {

    }

    public function recover() {
        if(Session::read('user.id')) {
            return $this->redirect('/users/profile');
        }
        $errors = array();
        $success = false;
        if($this->request->data)  {
            if($user = User::findByEmail($this->request->data['email'])) {
                $user->token = User::generateToken();
                $user->save(null, array('validate' => false));
                UserMailer::forgotpassword_mail($user);
                $success = true;
            }else {
                $errors[] = 'Пользователь с таким Email не найден.';
            }
        }
        return compact('errors', 'success');
    }

    public function profile() {
        $user = User::first(Session::read('user.id'));
        $winnersData = Solution::all(array('conditions' => array('Solution.awarded' => 1, 'Pitch.private' => 0), 'order' => array('Solution.created' => 'desc'), 'limit' => 50,  'with' => array('Pitch')));
        $winners = array();
        foreach($winnersData as $winner) {
            if($winner->pitch->category_id != 7) {
                $winners[] = $winner;
            }
        }


        $passwordInfo = false;
        $emailInfo = false;
        if($this->request->data) {

            if(($this->request->data['newpassword'] != '') && ($this->request->data['confirmpassword'] != '')) {
                $hashedCurrentPassword = String::hash($this->request->data['currentpassword']);
                if($hashedCurrentPassword != $user->password) {
                    $passwordInfo = 'Старый пароль не верен!';
                }elseif(($this->request->data['newpassword']) != ($this->request->data['confirmpassword'])) {
                    $passwordInfo = 'Пароли не совпадают!';
                }else {
                    $user->password = String::hash($this->request->data['newpassword']);
                    $passwordInfo = 'Пароль изменен!';
                }
            }
            $user->userdata = serialize(array(
                'birthdate' => $this->request->data['birthdate'],
                'city' => $this->request->data['city'],
                'profession' => $this->request->data['profession'],
                'about' => $this->request->data['about'],
            ));
            $user->isClient = $this->request->data['isClient'];
            $user->isDesigner = $this->request->data['isDesigner'];
            $user->isCopy = $this->request->data['isCopy'];
            if(isset($this->request->data['email_newpitch'])) {
                $user->email_newpitch = 1;
            }else{
                $user->email_newpitch = 0;
            }
            if(isset($this->request->data['email_newcomments'])) {
                $user->email_newcomments = 1;
            }else{
                $user->email_newcomments = 0;
            }
            if(isset($this->request->data['email_newpitchonce'])) {
                $user->email_newpitchonce = 1;
            }else{
                $user->email_newpitchonce = 0;
            }
            if(isset($this->request->data['email_newsolonce'])) {
                $user->email_newsolonce = 1;
            }else{
                $user->email_newsolonce = 0;
            }
            if(isset($this->request->data['email_newsol'])) {
                $user->email_newsol = 1;
            }else{
                $user->email_newsol = 0;
            }
            if(isset($this->request->data['email_digest'])) {
                $user->email_digest = 1;
            }else{
                $user->email_digest = 0;
            }
            if(isset($this->request->data['email_onlycopy'])) {
                $user->email_onlycopy = 1;
            }else{
                $user->email_onlycopy = 0;
            }
            if ($userWithEmail = User::first(array(
                'conditions' => array(
                    'email' => $this->request->data['email'],
                    'id' => array(
                        '!=' => $user->id,
                    ),
                )))) {
                $emailInfo = 'Пользователь с таким адресом электронной почты уже существует!';
            } else {
                $user->email = $this->request->data['email'];
            }

            $user->save(null, array('validate' => false));
        }
        return compact('user', 'winners', 'passwordInfo', 'emailInfo');
    }

    public function preview() {
        if($user = User::first(Session::read('user.id'))) {
            $pitchCount = User::getPitchCount(Session::read('user.id'));
            $averageGrade = User::getAverageGrade(Session::read('user.id'));
            if(!$averageGrade) {
                $averageGrade = 0;
            }
            $totalViews = (int) User::getTotalViews(Session::read('user.id'));
            $totalLikes = (int) User::getTotalLikes(Session::read('user.id'));
            $awardedSolutionNum = (int) User::getAwardedSolutionNum(Session::read('user.id'));
            $totalSolutionNum = (int) User::getTotalSolutionNum(Session::read('user.id'));
            if(User::checkRole('admin')) {
                $selectedSolutions = Solution::all(array('conditions' => array('Solution.user_id' => $this->request->id), 'with' => array('Pitch')));
            }else {
                $selectedSolutions = Solution::all(array('conditions' => array('selected' => 1, 'Solution.user_id' => $this->request->id), 'with' => array('Pitch')));
            }
            $isClient = false;
            $userPitches = Pitch::all(array('conditions' => array('user_id' => $user->id)));
            if(count($userPitches) > 0) {
                $isClient = true;
            }
            return compact('user', 'pitchCount', 'averageGrade', 'totalViews', 'totalLikes' ,'awardedSolutionNum' , 'totalSolutionNum', 'selectedSolutions', 'isClient');
        }
    }

    public function view() {
        if($user = User::first($this->request->id)) {
            if(($user->active == 0) && !User::checkRole('admin')):
                return $this->redirect('/');
            endif;

            $pitchCount = User::getPitchCount($this->request->id);
            $averageGrade = User::getAverageGrade($this->request->id);
            if(!$averageGrade) {
                $averageGrade = 0;
            }
            $totalViews = (int) User::getTotalViews($this->request->id);
            $totalLikes = (int) User::getTotalLikes($this->request->id);
            $awardedSolutionNum = (int) User::getAwardedSolutionNum($this->request->id);
            $totalSolutionNum = (int) User::getTotalSolutionNum($this->request->id);
            if(User::checkRole('admin')) {
                $selectedSolutions = Solution::all(array('conditions' => array('Solution.user_id' => $this->request->id), 'with' => array('Pitch')));
            }else {
                $selectedSolutions = Solution::all(array('conditions' => array('selected' => 1, 'Solution.user_id' => $this->request->id), 'with' => array('Pitch')));
            }
            $moderations = null;
            if (User::checkRole('admin') || (Session::read('user.isAdmin') == 1)) {
                $moderations = Moderation::all(array('conditions' => array('model_user' => $user->id)));
            }
            $isClient = false;
            $userPitches = Pitch::all(array('conditions' => array('user_id' => $user->id)));
            if(count($userPitches) > 0) {
                $isClient = true;
                $ids = array();
                foreach($userPitches as $pitch){
                    $ids[] = $pitch->id;
                }
                $selectedSolutions = Solution::all(array('conditions' => array(
                    'pitch_id' => $ids,
                    'OR' => array(array('rating = 4'), array('rating = 5'), array('Solution.awarded = 1'), array('Solution.nominated = 1'))
                ),
                    'with' => array('Pitch')
                ));
            }
            return compact('user', 'pitchCount', 'averageGrade', 'totalViews', 'totalLikes' ,'awardedSolutionNum' , 'totalSolutionNum', 'selectedSolutions', 'isClient', 'moderations');
        }
        throw new Exception('Public:Такого пользователя не существует.', 404);
    }

    public function savePaymentData() {
        $user = User::first(Session::read('user.id'));
        $user->paymentOptions = serialize(array($this->request->data));
        $user->save(null, array('validate' => false));
        return $user->paymentOptions;
    }


    public function spblist() {
        $list = User::all(array('conditions' => array(
            'userdata' => array('LIKE' => '%Санкт-Петербург%')
        )));
        $list2 = User::all(array('conditions' => array(
            'userdata' => array('LIKE' => '%Petersburg%')
        )));
        $list3 = User::all(array('conditions' => array(
            'userdata' => array('LIKE' => '%спб%')
        )));
        $list4 = User::all(array('conditions' => array(
            'userdata' => array('LIKE' => '%Петербург%')
        )));
        header('Content-Type: text/html; charset=UTF-8');
        $counter = 0;
        foreach($list as $user) {
            echo $user->email .  '</br>';
            $counter++;
        }
        foreach($list2 as $user) {
            echo $user->email .  '</br>';
            $counter++;
        }
        foreach($list3 as $user) {
            echo $user->email .  '</br>';
            $counter++;
        }
        foreach($list4 as $user) {
            echo $user->email .  '</br>';
            $counter++;
        }
        echo 'Всего ' . $counter++;
        die();
    }

    public function loginasadmin() {
        $token = $this->request->query['token'];
        $redirect = false;
        if(isset($this->request->query['redirect'])) {
            $redirect = $this->request->query['redirect'];
        }
        if(isset($token)) {
            $user = User::first(array('conditions' => array('token' => $token, 'isAdmin' => 1)));
            if($user) {
                Auth::clear('user');
                Auth::set('user', $user->data());
                if(!$redirect) {
                    return $this->redirect('/');
                }else {
                    return $this->redirect($redirect);
                }

            }else {
                return $this->redirect('Users::registration');
            }
        }else {
            return $this->redirect('Users::registration');
        }
    }

    public function updatetwitter() {
        $string = base64_encode('8r9SEMoXAacbpnpjJ5v64A:I1MP2x7guzDHG6NIB8m7FshhkoIuD6krZ6xpN4TSsk');
        $tmhOAuth = new tmhOAuth(array(
            'consumer_key'    => '8r9SEMoXAacbpnpjJ5v64A',
            'consumer_secret' => 'I1MP2x7guzDHG6NIB8m7FshhkoIuD6krZ6xpN4TSsk',
            'user_token'      => '513074899-IvVlKCCD0kEBicxjrLGLjW2Pb7ZiJd1ZjQB9mkvN',
            'user_secret'     => 'ldmaK6qmlzA3QJPQemmVWJGUpfST3YuxrzIbhaArQ9M'
        ));
        $tmhOAuth->headers['Authorization'] = 'Basic ' . $string;
        $params = array('grant_type' => 'client_credentials');
        $response = $tmhOAuth->request('POST',
            'https://api.twitter.com/oauth2/token',
            $params,
            false
        );
        $data = json_decode($tmhOAuth->response['response'], true);
        $bearerToken = $data['access_token'];
        $tmhOAuth->headers['Authorization'] = 'Bearer ' . $bearerToken;

        $params = array('rpp' => 5, 'q' => 'godesigner.ru', 'include_entities' => true);
        $code = $tmhOAuth->request('GET',
            'https://api.twitter.com/1.1/search/tweets.json',
            $params,
            false
        );
        if ($code == 200) {
            $data = json_decode($tmhOAuth->response['response'], true);
            $censoredTweets = array();
            $censoredTweets['statuses'] = array();
            foreach($data['statuses'] as $key => &$tweet) {
                $delete = false;
                if(isset($tweet['entities']) and isset($tweet['entities']['urls'])) {
                    foreach($tweet['entities']['urls'] as $url) {
                        if($matches = preg_match('*godesigners.ru/\?ref\=*', $url['expanded_url'])) {
                            $delete = true;
                        }
                    }
                }
                if($delete == false) {
                    $censoredTweets['statuses'][$key] = $tweet;
                }
            }
            $res = Rcache::write('twitterstream', $censoredTweets);
            echo '<pre>';
            var_dump($censoredTweets['statuses']);
            die();
        }else {
            echo '<pre>';
            var_dump($tmhOAuth->response);
            echo '</pre>';
            die();
        }
    }

    public function details() {
        $user = User::first(Session::read('user.id'));
        if(is_null($this->request->env('HTTP_X_REQUESTED_WITH'))){
            return compact('user');
        }else {
            return $this->render(array('layout' => false, 'data' => compact('user')));
        }
    }

    public function ban() {
        if(($user = User::first($this->request->data['id'])) && (Session::read('user.isAdmin') == 1|| (in_array(Session::read('user.id'), User::$admins)))){
            $term = $this->request->data['term'] * DAY;
            $user->silenceUntil = date('Y-m-d H:i:s', time() + $term);
            $user->silenceCount += 1;
            $user->save(null, array('validate' => false));
            UserMailer::ban(array('user' => $user->data(), 'term' => $this->request->data['term']));
        }

        return $user->data();
    }

    public function unban() {
        if(($user = User::first($this->request->data['id'])) && (Session::read('user.isAdmin') == 1|| (in_array(Session::read('user.id'), User::$admins)))){
            $user->silenceUntil = date('Y-m-d H:i:s');
            $user->save(null, array('validate' => false));
        }
        return $user->data();
    }

    public function unblock() {
        if(($user = User::first($this->request->data['id'])) && (Session::read('user.isAdmin') == 1|| (in_array(Session::read('user.id'), User::$admins)))){
            $user->banned = 0;
            $user->save(null, array('validate' => false));
            UserMailer::block(array('user' => $user->data()));
            return $this->request->data;
        }
    }

    public function block() {
        if(($user = User::first($this->request->data['id'])) && (Session::read('user.isAdmin') == 1|| (in_array(Session::read('user.id'), User::$admins)))){
            $user->block();
            UserMailer::block(array('user' => $user->data()));
            return $this->request->data;
        }
    }

    public function loginasuser() {
        if(in_array(Session::read('user.id'), User::$admins)) {
            if($user = User::first($this->request->id)) {
                Auth::clear('user');
                Auth::set('user', $user->data());
                return $this->redirect('/');
            }
        }
    }

    public function stats() {
        $count = User::count();
        return array('data' => array('count' => $count));
    }

    public function deleteaccount() {
        $user = User::find(Session::read('user.id'));
        $user->active = 0;
        $user->oldemail = $user->email;
        $user->email = '';
        $user->save(null, array('validate' => false));
        Auth::clear('user');
        session_destroy();
        unset($_SESSION);

        setcookie("PHPSESSID", null);
        die();
    }

    public function requesthelp() {
        $success = 'false';
        if(($this->request->data) && ($this->request->data['case'] == 'fu27fwkospf')) {
            if($this->request->data['target'] != 0) {
                $emails = array(
                    1 => 'devochkina@godesigner.ru',
                    2 => 'va@godesigner.ru',
                    3 => 'nyudmitriy@godesigner.ru',
                    4 => 'fedchenko@godesigner.ru'
                );
                $this->request->data['target'] = $emails[$this->request->data['target']];
            }else {
                $this->request->data['target'] = 'team@godesigner.ru';
            }
            $this->request->data['subject'] = 'Сообщение с сайта GoDesigner.ru';
            if ($this->request->data['target'] != 'va@godesigner.ru') {
                $this->request->data['needInfo'] = true;
                $this->request->data['user'] = User::getUserInfo();
            }
            ContactMailer::contact_mail2($this->request->data);
            $success = 'true';
        }
        return compact('success');
    }

    public function vklogin() {
        if(isset($_GET['code'])) {
            var_dump($this->request->data);
            var_dump($this->request->params);
            $client_id = '2950889'; // ID приложения
            $client_secret = 'j1bFzKfXP4lIa0wA7vaV'; // Защищённый ключ

            // получаем access_token
            $resp = file_get_contents('https://api.vk.com/oauth/access_token?client_id='.$client_id.'&code='.$_REQUEST['code'].'&client_secret='.$client_secret);
            $data = json_decode($resp, true);


            if($data['access_token']){

                $token = $data['access_token'];
                //Session:write('');
                $resp = file_get_contents('https://api.vk.com/method/getProfiles?uid=66748&access_token=' . $token);
                $data = json_decode($resp, true);
                var_dump($data);
                die();
                // запишем данные в сессию
                $_SESSION['access_token'] = $data['access_token'];
                $_SESSION['user_id'] = $data['user_id'];
                // переадресуем пользователя на нужную страницу
                header('Location: '.PATH.'index.php');
                exit();
            }
        }
        die();
    }

    public function addSocial() {
        if ($this->request->is('json') && (($this->request->id == 1 || $this->request->id == 2)) && (Session::read('user.id') > 0) && ($user = User::first((int) Session::read('user.id')))) {
            $user->social = (int) $this->request->id;
            Session::write('user.social', $user->social);
            return $user->save(null, array('validate' => false));
        }
        $this->redirect('/');
    }

    public function checkPhone() {
        if ($this->request->is('json') && (Session::read('user.id') > 0) && ($user = User::first((int) Session::read('user.id')))) {
            if (!preg_match("/^[0-9]{11,12}+$/", $this->request->data['userPhone']) || empty($this->request->data['phoneOperator'])) {
                return json_encode(false);
            }

            // SMS Spam Prevention
            if ($smsCount = Session::read('user.smsCount')) {
                if (end($smsCount) > (time() - HOUR)) {
                    $i = 1;
                    while ((prev($smsCount) > (time() - HOUR)) && $i < 10) {
                        $i++;
                    }
                    if ($i >= 10) {
                        return json_encode('limit');
                    }
                    $smsCount[] = time();
                } else {
                    $smsCount = array(time());
                }
            } else {
                $smsCount = array(time());
            }
            Session::write('user.smsCount', $smsCount);

            // Добавляем семерку к номеру телефону, если мы рассылаем по России.

            //$this->request->data['userPhone'] = "7" . $this->request->data['userPhone'];

            // Иногда возникает небходимость проверить первую цифру номера, например если он
            // 11-ти значный то для корректной отправки через наш API необходимо,
            // чтобы номер начинался с 7, проверим это

            /* For Russian Phones Only
             *
             * $first = substr($this->request->data['userPhone'], "0", 1);
            if ($first != 7) {
                return json_encode(false);
            } */

            return User::phoneValidationStart($user->id, $this->request->data['userPhone'], $this->request->data['phoneOperator']);
        }
        $this->redirect('/');
    }

    public function validateCode() {
        if ($this->request->is('json') && (Session::read('user.id') > 0) && ($user = User::first((int) Session::read('user.id'))) && $this->request->data['verifyCode']) {
            return json_encode(User::phoneValidationFinish($user->id, (int) $this->request->data['verifyCode']));
        }
        $this->redirect('/');
    }
}




class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()){
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 104857600;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 104857600){
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false;
        }
    }

    private function checkServerSettings(){
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = true){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }

        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }



        $pathinfo = pathinfo($this->file->getName());
        $originalname = $pathinfo['filename'];
        $filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }

        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }

        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
            return array('success'=>true, 'name' => $originalname . '.' . $ext, 'tmpname' => $uploadDirectory . $filename . '.' . $ext);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }

    }



}