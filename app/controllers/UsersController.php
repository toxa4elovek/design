<?php

namespace app\controllers;

use app\extensions\smsfeedback\SmsFeedback;
use app\extensions\social\TwitterAPI;
use app\models\Logreferal;
use \app\models\User;
use \app\models\Sendemail;
use \app\models\Category;
use \app\models\Solution;
use \app\models\Wincomment;
use \app\models\Grade;
use \app\models\Pitch;
use app\models\Post;
use \app\models\Event;
use \app\models\News;
use \app\models\Invite;
use \app\models\Avatar;
use \app\models\Moderation;
use \app\models\Wp_post;
use \app\models\Favourite;
use \app\models\Tweet;
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
use \Exception;
use \app\extensions\helper\Avatar as AvatarHelper;

class UsersController extends \app\controllers\AppController {

    /**
     * Методы, доступные без аутентификации
     *
     * @var array
     */
    public $publicActions = array(
        'vklogin', 'unsubscribe', 'registration', 'login', 'sale', /* 'info', 'sendmail', */ 'confirm', 'checkform', 'recover', 'setnewpassword', 'loginasadmin', 'view', 'updatetwitter', 'updatetwitterfeed', 'banned', 'activation', 'need_activation', 'requesthelp', 'testemail', 'feed'
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
        if (in_array($this->request->action, $withMenu)) {
            $myPitches = Pitch::all(array(
                        'conditions' => array('user_id' => Session::read('user.id')),
            ));
            $pitchIds = array();
            foreach ($myPitches as $pitch) {
                $pitchIds[] = $pitch->id;
            }
            $solutionsFromMyPitches = array();
            if (!empty($pitchIds)) {
                $solutionsFromMyPitches = Solution::all(array('conditions' => array('pitch_id' => $pitchIds, 'nominated' => '1')));
            }

            $conditions = array('Solution.user_id' => Session::read('user.id'), 'nominated' => '1', 'status' => array('<' => 2));
            $solutions = Solution::all(array('conditions' => $conditions, 'with' => array('Pitch')));

            if (count($solutionsFromMyPitches) > 0) {
                $solutions = array_merge($solutions->data(), $solutionsFromMyPitches->data());
            } else {
                $solutions = $solutions->data();
            }
            $this->nominatedCount = count($solutions);
        }
    }

    public function avatar() {
        $allowedExtensions = array('png', 'gif', 'jpeg', 'jpg');
        // max file size in bytes
        $sizeLimit = 100 * 1024 * 1024;
        if ($this->request->env('REQUEST_METHOD') == 'POST') {
            $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
            $result = $uploader->handleUpload(LITHIUM_APP_PATH . '/webroot/avatars/');
            if ($result['success']) {
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

    /**
     * Кабинет
     *
     */
    public function office() {
        return $this->redirect('Users::feed');
        $date = date('Y-m-d H:i:s');
        if ((Session::read('user.id' > 0)) && (Session::read('user.events') != null)) {
            $date = Session::read('user.events.date');
            Session::delete('user.events');
        }
        $gallery = Solution::getUserSolutionGallery(Session::read('user.id'));
        $winnersData = Solution::all(array('conditions' => array('Solution.awarded' => 1, 'Pitch.private' => 0), 'order' => array('Solution.created' => 'desc'), 'limit' => 50, 'with' => array('Pitch')));
        $winners = array();
        foreach ($winnersData as $winner) {
            if ($winner->pitch->category_id != 7) {
                $winners[] = $winner;
            }
        }
        $winners = array();
        $updates = Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), 1, null);
        $nextUpdates = count(Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), 2, null));
        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('gallery', 'winners', 'date', 'updates', 'nextUpdates');
        } else {
            return $this->render(array('layout' => false, 'data' => compact('gallery', 'winners', 'date', 'updates', 'nextUpdates')));
        }
    }

    public function feed() {
        $date = date('Y-m-d H:i:s');
        if ((Session::read('user')) && (Session::read('user.id' > 0)) && (Session::read('user.events') != null)) {
            $date = Session::read('user.events.date');
            Session::delete('user.events');
            $pitchIds = User::getSubscribedPitches(Session::read('user.id'));
        } else {
            $pitchIds = array();
        }
        $pitches = Pitch::all(array('conditions' => array('status' => 0, 'published' => 1, 'multiwinner' => 0), 'order' => array('started' => 'desc'), 'limit' => 5));
        $middlePost = false;
        $shareEvent = null;
        if((isset($this->request->query['event'])) && (is_numeric($this->request->query['event']))) {
            $shareEvent = Event::first($this->request->query['event']);
        }
        $news = News::getNews();
        $solutions = Event::getEventSolutions(Session::read('user.id'));
        $tag = null;
        if(isset($this->request->query['tag'])) {
            $tag = $this->request->query['tag'];
        }
        $updates = Event::getEvents($pitchIds, 1, null, Session::read('user.id'), $tag);
        $nextUpdates = count(Event::getEvents($pitchIds, 2, null, Session::read('user.id'), $tag));
        $banner = News::getBanner();
        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            $accessToken = Event::getBingAccessToken();
            return compact('date', 'updates', 'nextUpdates', 'news', 'pitches', 'solutions', 'middlePost', 'banner', 'shareEvent', 'accessToken', 'tag');
        } else {
            return $this->render(array('layout' => false, 'data' => compact('pitchIds', 'date', 'updates', 'nextUpdates', 'pitches')));
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
        $completePaymentCount = Logreferal::getCompletePaymentCount(Session::read('user.id'));
        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('user', 'refPitches', 'completePaymentCount');
        } else {
            return $this->render(array('layout' => false, 'data' => compact('user', 'refPitches', 'completePaymentCount')));
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
        $solutions = Solution::all(array(
            'conditions' => $conditions,
            'with' => array('Pitch', 'Solutiontag'),
            'order' => array('Solution.id' => 'desc')
        ));
        $nominatedCount = $this->nominatedCount;
        $myPitches = Pitch::all(array('conditions' => array('user_id' => Session::read('user.id'), 'published' => 1, 'billed' => 1)));

        if ($myPitches->data()) {
            $idList = array();
            foreach ($myPitches as $pitch) {
                $idList[] = $pitch->id;
            }
            $solutions = Solution::all(array(
                'conditions' => array('pitch_id' => $idList),
                'order' => array('Solution.id' => 'desc'),
                'with' => array('Pitch', 'Solutiontag')
            ));
        }
        foreach($solutions as $solution) {
            $solution->tags = Solution::getTagsArrayForSolution($solution);
        }
        $filterType = 'solutions';
        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('solutions', 'filterType', 'nominatedCount');
        } else {
            return $this->render(array('layout' => false, 'data' => compact('solutions', 'filterType', 'nominatedCount')));
        }
    }

    public function awarded() {
        $myPitches = Pitch::all(array(
                    'conditions' => array('user_id' => Session::read('user.id')),
        ));
        $pitchIds = array();
        foreach ($myPitches as $pitch) {
            $pitchIds[] = $pitch->id;
        }
        $solutionsFromMyPitches = array();
        if (!empty($pitchIds)) {
            $solutionsFromMyPitches = Solution::all(array('conditions' => array('pitch_id' => $pitchIds, 'Solution.awarded' => '1'), 'with' => array('Pitch')));
        }

        $conditions = array('Solution.user_id' => Session::read('user.id'), 'Solution.awarded' => 1, 'Solution.nominated' => 0);
        $solutions = Solution::all(array('conditions' => $conditions, 'with' => array('Pitch')));

        if (count($solutionsFromMyPitches) > 0) {
            $solutions = array_merge($solutions->data(), $solutionsFromMyPitches->data());
        } else {
            $solutions = $solutions->data();
        }

        $filterType = 'awarded';

        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('solutions', 'filterType');
        } else {
            return $this->render(array('layout' => false, 'data' => compact('solutions', 'filterType')));
        }
    }

    public function nominated() {
        $myPitches = Pitch::all(array(
                    'conditions' => array('user_id' => Session::read('user.id')),
        ));
        $pitchIds = array();
        foreach ($myPitches as $pitch) {
            $pitchIds[] = $pitch->id;
        }
        $solutionsFromMyPitches = array();
        if (!empty($pitchIds)) {
            $solutionsFromMyPitches = Solution::all(array('conditions' => array('pitch_id' => $pitchIds, 'nominated' => '1', 'Solution.awarded' => '0'), 'with' => array('Pitch')));
        }

        $conditions = array('Solution.user_id' => Session::read('user.id'), 'Solution.nominated' => '1');
        $solutions = Solution::all(array('conditions' => $conditions, 'with' => array('Pitch')));

        if (count($solutionsFromMyPitches) > 0) {
            $solutions = array_merge($solutions->data(), $solutionsFromMyPitches->data());
        } else {
            $solutions = $solutions->data();
        }
        $filterType = 'nominated';
        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('solutions', 'filterType');
        } else {
            return $this->render(array('layout' => false, 'data' => compact('solutions', 'filterType')));
        }
    }

    public function step1() {
        if (($solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('Pitch', 'User')))) && ($solution->nominated == 1 || $solution->awarded == 1)) {
            if ((Session::read('user.id') != $solution->user_id) && (Session::read('user.isAdmin') != 1) && (!User::checkRole('admin')) && (Session::read('user.id') != $solution->pitch->user_id)) {
                return $this->redirect('Users::feed');
            }
            if (Session::read('user.id') == $solution->pitch->user_id) {
                return $this->redirect(array('controller' => 'users', 'action' => 'step2', 'id' => $solution->id));
            }
            $solution->pitch->category = Category::first($solution->pitch->category_id);
            if ($solution->user_id == Session::read('user.id')) {
                $type = 'designer';
            } else {
                $type = 'client';
            }
            if ((Session::read('user.isAdmin') == 1) || User::checkRole('admin')) {
                $type = 'admin';
            }
            $step = 1;
            return compact('type', 'solution', 'step');
        }
    }

    public function step2() {
        \lithium\net\http\Media::type('json', array('text/html'));
        if (($solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('Pitch', 'User')))) && ($solution->nominated == 1 || $solution->awarded == 1)) {
            if ((Session::read('user.id') != $solution->user_id) && (Session::read('user.isAdmin') != 1) && (!User::checkRole('admin')) && (Session::read('user.id') != $solution->pitch->user_id)) {
                return $this->redirect('Users::feed');
            }
            $solution->pitch->category = Category::first($solution->pitch->category_id);
            if ($solution->user_id == Session::read('user.id')) {
                $type = 'designer';
                $messageTo = User::first($solution->pitch->user_id);
            } else {
                $type = 'client';
                $messageTo = User::first($solution->user_id);
            }
            if ((Session::read('user.isAdmin') == 1) || User::checkRole('admin')) {
                $type = 'admin';
                $messageTo = User::first($solution->pitch->user_id);
            }
            if ($this->request->data) {
                $newComment = Wincomment::create();
                if (count($this->request->data['file']) > 0) {
                    foreach ($this->request->data['file'] as $index => &$file) {
                        if ($file['error'] > 0) {
                            unset($this->request->data['file'][$index]);
                        }
                    }
                }
                if (empty($this->request->data['file'])) {
                    unset($this->request->data['file']);
                }
                $newComment->set($this->request->data);
                $newComment->user_id = Session::read('user.id');
                $newComment->solution_id = $solution->id;
                $newComment->created = date('Y-m-d H:i:s');
                $newComment->step = 2;
                $newComment->save();
                if ($type == 'designer') {
                    $recipient = User::first($solution->pitch->user_id);
                } else {
                    $recipient = User::first($solution->user_id);
                }
                User::sendSpamWincomment($newComment, $recipient);
                $user = User::first(Session::read('user.id'));
                $avatarHelper = new AvatarHelper;
                $userAvatar = $avatarHelper->show($user->data(), false, true);
                $comment = Wincomment::first(array('conditions' => array('Wincomment.id' => $newComment->id), 'with' => array('User', 'Solution')));
                $comment = $comment->data();
                return json_encode(compact('newComment', 'comment', 'userAvatar'));
            }
            $files = array();
            $comments = Wincomment::all(array('conditions' => array('step' => 2, 'solution_id' => $solution->id), 'order' => array('created' => 'desc'), 'with' => array('User')));
            foreach ($comments as $comment) {
                if ($comment->user_id == $solution->user_id) {
                    $comment->type = 'designer';
                    if (!empty($comment->images)) {
                        $files[] = $comment->images;
                    }
                } else {
                    $comment->type = 'client';
                }
                if (($type != 'admin') && ($comment->type != $type) && ($comment->touch == '0000-00-00 00:00:00')) {
                    $comment->touch = date('Y-m-d H:i:s');
                    $comment->save();
                }
            }
            $step = 2;
            if (empty($files)) {
                $nofiles = true;
            } else {
                $nofiles = false;
            }
            return compact('type', 'solution', 'comments', 'step', 'nofiles', 'messageTo');
        }
    }

    public function step3() {
        \lithium\net\http\Media::type('json', array('text/html'));
        if (($solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('Pitch', 'User')))) && ($solution->nominated == 1 || $solution->awarded == 1)) {
            if (($this->request->params['confirm']) && ($this->request->params['confirm'] == 'confirm') &&
                    ((Session::read('user.isAdmin') == 1) || (Session::read('user.id') == $solution->pitch->user_id)) && ($solution->step < 3)) {
                $user = User::first($solution->user_id);
                User::sendSpamWinstep($user, $solution, '3');
                $solution->step = 3;
                $solution->save();
                return $this->redirect(array('controller' => 'users', 'action' => 'step3', 'id' => $solution->id));
            }

            if ((Session::read('user.id') != $solution->user_id) && (Session::read('user.isAdmin') != 1) && (!User::checkRole('admin')) && (Session::read('user.id') != $solution->pitch->user_id)) {
                return $this->redirect('Users::feed');
            }
            if ($solution->step < 3) {
                return $this->redirect(array('controller' => 'users', 'action' => 'step2', 'id' => $this->request->id));
            }
            if (($solution->pitch->category_id == 7) && ($solution->step == 4)) {
                return $this->redirect('/users/step4/' . $solution->id);
            }
            $solution->pitch->category = Category::first($solution->pitch->category_id);
            if ($solution->user_id == Session::read('user.id')) {
                $type = 'designer';
                $messageTo = User::first($solution->pitch->user_id);
            } else {
                $type = 'client';
                $messageTo = User::first($solution->user_id);
            }
            if ((Session::read('user.isAdmin') == 1) || User::checkRole('admin')) {
                $type = 'admin';
                $messageTo = User::first($solution->pitch->user_id);
            }
            if ($this->request->data) {
                $newComment = Wincomment::create();
                if (count($this->request->data['file']) > 0) {
                    foreach ($this->request->data['file'] as $index => &$file) {
                        if ($file['error'] > 0) {
                            unset($this->request->data['file'][$index]);
                        }
                    }
                }
                if (empty($this->request->data['file'])) {
                    unset($this->request->data['file']);
                }
                $newComment->set($this->request->data);
                $newComment->user_id = Session::read('user.id');
                $newComment->solution_id = $solution->id;
                $newComment->created = date('Y-m-d H:i:s');
                $newComment->step = 3;
                $newComment->save();
                if ($type == 'designer') {
                    $recipient = User::first($solution->pitch->user_id);
                } else {
                    $recipient = User::first($solution->user_id);
                }
                User::sendSpamWincomment($newComment, $recipient);
                $user = User::first(Session::read('user.id'));
                $avatarHelper = new AvatarHelper;
                $userAvatar = $avatarHelper->show($user->data(), false, true);
                $comment = Wincomment::first(array('conditions' => array('Wincomment.id' => $newComment->id), 'with' => array('User', 'Solution')));
                $comment = $comment->data();
                return json_encode(compact('newComment', 'comment', 'userAvatar'));
            }
            $comments = Wincomment::all(array('conditions' => array('step' => 3, 'solution_id' => $solution->id), 'order' => array('created' => 'desc'), 'with' => array('User')));
            $files = array();
            foreach ($comments as $comment) {

                if ($comment->user_id == $solution->user_id) {
                    $comment->type = 'designer';
                    if (!empty($comment->images)) {
                        $files[] = $comment->images;
                    }
                } else {
                    $comment->type = 'client';
                }
                if (($type != 'admin') && ($comment->type != $type) && ($comment->touch == '0000-00-00 00:00:00')) {
                    $comment->touch = date('Y-m-d H:i:s');
                    $comment->save();
                }
            }
            $step = 3;
            if (empty($files)) {
                $nofiles = true;
            } else {
                $nofiles = false;
            }
            return compact('type', 'solution', 'comments', 'step', 'nofiles', 'messageTo');
        }
    }

    public function step4() {
        if (($solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('Pitch', 'User'))))) {
            if (($this->request->params['confirm']) && ($this->request->params['confirm'] == 'confirm') &&
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

            if (($this->request->params['confirm']) && ($this->request->params['confirm'] == 'confirm') &&
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

            if ((Session::read('user.id') != $solution->user_id) && (Session::read('user.isAdmin') != 1) && (!User::checkRole('admin')) && (Session::read('user.id') != $solution->pitch->user_id)) {
                return $this->redirect('Users::feed');
            }
            if ($solution->step < 4) {
                return $this->redirect(array('controller' => 'users', 'action' => 'step3', 'id' => $this->request->id));
            }
            $solution->pitch->category = Category::first($solution->pitch->category_id);
            if ($solution->user_id == Session::read('user.id')) {
                $type = 'designer';
                $gradeByOtherParty = Grade::first(array('conditions' => array('user_id' => $solution->pitch->user_id, 'pitch_id' => $solution->pitch->id)));
            } else {
                $type = 'client';
                $gradeByOtherParty = Grade::first(array('conditions' => array('user_id' => $solution->user_id, 'pitch_id' => $solution->pitch->id)));
            }
            if ((Session::read('user.isAdmin') == 1) || User::checkRole('admin')) {
                $type = 'admin';
            }
            $grade = Grade::first(array('conditions' => array('user_id' => Session::read('user.id'), 'pitch_id' => $solution->pitch->id, 'type' => $type)));
            if ($this->request->data) {
                $grade = Grade::create();
                $grade->set($this->request->data);
                $grade->pitch_id = $solution->pitch->id;
                $grade->user_id = Session::read('user.id');
                $grade->type = $type;
                $grade->save();
                if ($gradeByOtherParty) {
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
        if (!is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return $this->render(array('layout' => false));
        } else {
            $categories = Category::all();
            if(isset($this->request->query['category'])) {
                $selectedCategory = $this->request->query['category'];
            }
            return compact('categories', 'selectedCategory');
        }
    }

    public function viewmail() {
        $mail = Sendemail::findByHash($this->request->id);
        if (Session::read('user.email') == $mail->email) {
            echo $mail->text;
            die();
        } else {
            return $this->redirect('Users::feed');
        }
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
        if (Session::read('user')) {
            return $this->redirect('/');
        }
        /* if(((!isset($this->request->query['invite'])) && (!isset($this->request->data['id']))) || ((isset($this->request->query['invite'])) && (!$isValid = Invite::isValidInvite($this->request->query['invite'])))) {
          return $this->redirect('Invites::index');
          } */
        if ($vk_auth = Session::read('vk_data')) {
            $this->request->data = $vk_auth;
        }
        $user = User::create();
        if ($this->request->data) {
            // фейсбук регисстрация
            if ((isset($this->request->data['id']) && isset($this->request->data['name']) || (isset($this->request->data['service']) && isset($this->request->data['email'])))) {
                // регился ли пользователей через обычную регистрацию?
                $isUserExists = $user->isUserExistsByEmail($this->request->data['email']);
                if ($isUserExists) {
                    // если он уже регился обычным способом, сохраняем его фейсбук айди
                    $userToLog = User::first(array('conditions' => array('email' => $this->request->data['email'])));
                    if (isset($this->request->data['service'])) {
                        $userToLog->vkontakte_uid = $this->request->data['uid'];


                    } else {
                        $userToLog->facebook_uid = $this->request->data['id'];
                        if (!Avatar::first(array('conditions' => array('model_id' => $userToLog->id)))) {
                            $userToLog->getFbAvatar();
                        }
                    }
                    $userToLog->save();
                } else {
                    // регился ли пользователей через фейсбук?
                    $fb = false;
                    if (isset($this->request->data['service'])) {
                        $isFBUserExists = $user->checkVkontakteUser($this->request->data);
                        $vk = true;
                    } else {
                        $this->request->data['facebook_uid'] = $this->request->data['id'];
                        unset($this->request->data['id']);
                        $isFBUserExists = $user->checkFacebookUser($this->request->data);
                        $fb = true;
                    }

                    if (!$isFBUserExists) {
                        // если пользователей фейсбука у нас отсутствует, то сохраняем его в базу
                        if (($fb && $user->saveFacebookUser($this->request->data)) || (!$fb && $user->saveVkontakteUser($this->request->data))) {
                            if ($fb) {
                                $userToLog = User::first(array('conditions' => array('facebook_uid' => $this->request->data['facebook_uid'])));
                            } else {
                                $userToLog = User::first(array('conditions' => array('vkontakte_uid' => $this->request->data['uid'])));
                            }
                            $userToLog->setLastActionTime();
                            if($fb) {
                                $userToLog->getFbAvatar();
                            }else {
                                $userToLog->getVkAvatar(Session::read('vk_data.image_link'));
                                Session::delete('vk_data');
                            }
                            UserMailer::hi_mail($userToLog);
                            $newuser = true;
                            if (isset($_COOKIE['fastpitch'])) {
                                $fastId = unserialize($_COOKIE['fastpitch']);
                                $fastPitches = Pitch::all(array('conditions' => array('id' => $fastId)));
                                foreach ($fastPitches as $fastPitch) {
                                    $fastPitch->user_id = $userToLog->id;
                                }
                                $fastPitches->save();
                            }
                            //User::postOnFacebook('TEST');
                        } else {
                            return $this->redirect('Users::login');
                        }
                    } else {
                        // если он уже у нас есть, то вытаскиваем все его данные по айди
                        if ($fb) {
                            $userToLog = User::first(array('conditions' => array('facebook_uid' => $this->request->data['facebook_uid'])));
                        } else {
                            $userToLog = User::first(array('conditions' => array('vkontakte_uid' => $this->request->data['uid'])));
                        }
                        if (!$userToLog->gender) {
                            $gender = 0;
                            if (isset($this->request->data['gender']) && $this->request->data['gender'] == 'male') {
                                $gender = 1;
                            } elseif (isset($this->request->data['gender']) && $this->request->data['gender'] == 'female') {
                                $gender = 2;
                            }
                            $userToLog->gender = $gender;
                        }
                        $newuser = false;
                        if (isset($_COOKIE['fastpitch'])) {
                            $fastId = unserialize($_COOKIE['fastpitch']);
                            $fastPitches = Pitch::all(array('conditions' => array('id' => $fastId)));
                            foreach ($fastPitches as $fastPitch) {
                                $fastPitch->user_id = $userToLog->id;
                            }
                            $fastPitches->save();
                        }
                    }
                }
                if ($userToLog->banned) {
                    Auth::clear('user');
                    return array('data' => true, 'redirect' => '/users/banned');
                }
                if ($userToLog->active == 0) {
                    Auth::clear('user');
                    return array('data' => true, 'redirect' => '/users/login');
                }
                $userToLog->autologin_token = User::generateToken();
                if (isset($this->request->data['accessToken'])) {
                    $userToLog->accessToken = $this->request->data['accessToken'];
                }
                setcookie('autologindata', 'id=' . $userToLog->id . '&token=' . sha1($userToLog->autologin_token), time() + strtotime('+1 month'), '/');
                $userToLog->lastTimeOnline = date('Y-m-d H:i:s');
                $userToLog->save(null, array('validate' => false));
                // производим аутентификацию
                Auth::set('user', $userToLog->data());
                // отдаем тру для того, чтобы джаваскрипт сделал редирект
                $redirect = false;

                $pitchId = Session::read('temppitch');
                if (!is_null($pitchId)) {
                    if ($pitch = Pitch::first($pitchId)) {
                        $pitch->user_id = $userToLog->id;
                        $pitch->save();
                    }
                    Session::delete('temppitch');
                    $redirect = '/pitches/edit/' . $pitchId . '#step3';
                }
                if (!is_null(Session::read('redirect')) && false === $redirect) {
                    $redirect = Session::read('redirect');
                    if ($redirect == 'http://' . $_SERVER['HTTP_HOST'] . '/') {
                        $redirect = '/users/feed';
                    }
                    Session::delete('redirect');
                }
                if (isset($this->request->data['service'])) {
                    if(!$redirect) {
                        $redirect = '/users/feed';
                    }
                    $this->redirect($redirect);
                }
                return array('data' => true, 'redirect' => $redirect, 'newuser' => $newuser);
            } else {
                // обычная регистрация
                if (!isset($this->request->data['case']) || $this->request->data['case'] != 'fu27fwkospf' || !$this->request->is('json')) { // Check for bots
                    return $this->redirect('/');
                }
                $user->token = User::generateToken();
                $user->created = date('Y-m-d H:i:s');

                if (!is_null(Session::read('redirect'))) {
                    $redirect = Session::read('redirect');
                }else {
                    $redirect = '/users/feed';
                }
                if (isset($this->request->data['who_am_i'])) {
                    if ($this->request->data['who_am_i'] == 'client') {
                        $this->request->data['isClient'] = 1;
                        $this->request->data['email_newsolonce'] = 1;
                        $this->request->data['email_newsol'] = 1;
                        $this->request->data['email_newcomments'] = 1;
                        $this->request->data['email_digest'] = 1;
                    }
                    if ($this->request->data['who_am_i'] == 'company') {
                        $this->request->data['is_company'] = 1;
                        $this->request->data['email_newsolonce'] = 1;
                        $this->request->data['email_newsol'] = 1;
                        $this->request->data['email_newcomments'] = 1;
                        $this->request->data['email_digest'] = 1;
                    }
                    if ($this->request->data['who_am_i'] == 'designer') {
                        $this->request->data['isDesigner'] = 1;
                        $redirect = '/users/feed';
                    }
                }

                $user->set($this->request->data);
                if (($user->validates()) && ($user->save($this->request->data))) {
                    $userToLog = User::first(array('conditions' => array('id' => $user->id)));
                    $userToLog->lastTimeOnline = date('Y-m-d H:i:s');
                    $userToLog->setLastActionTime();
                    if ($user->isClient) {
                        $posts = Post::all(array('order' => array('id' => 'desc'), 'limit' => 2));
                        $res = UserMailer::verification_mail_client($userToLog, $posts);
                    } else {
                        $res = UserMailer::verification_mail($userToLog);
                    }
                    // производим аутентификацию
                    Auth::set('user', $userToLog->data());

                    $pitchId = Session::read('temppitch');

                    if (isset($_COOKIE['fastpitch'])) {
                        $fastId = unserialize($_COOKIE['fastpitch']);
                        $fastPitches = Pitch::all(array('conditions' => array('id' => $fastId)));
                        foreach ($fastPitches as $fastPitch) {
                            $fastPitch->user_id = $user->id;
                        }
                        $fastPitches->save();
                    }

                    if (!is_null($pitchId)) {
                        if ($pitch = Pitch::first($pitchId)) {
                            $pitch->user_id = $userToLog->id;
                            $pitch->save();
                        }
                        Session::delete('temppitch');
                        return array('redirect' => '/pitches/edit/' . $pitchId . '#step3', 'who_am_i' => 'client');
                    }
                    return array('redirect' => $redirect, 'who_am_i' => $this->request->data['who_am_i']);
                }
            }
        }
        $url = 'http://oauth.vk.com/authorize';

        $client_id = '2950889'; // ID приложения
        $client_secret = 'j1bFzKfXP4lIa0wA7vaV'; // Защищённый ключ
        $redirect_uri = 'http://www.godesigner.ru/users/vklogin'; // Адрес сайта


        $params = array(
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'scope' => 'friends',
            'response_type' => 'code'
        );
        $freePitch = Pitch::getFreePitch();
        return compact('user', 'invite', 'params', 'url', 'freePitch');
    }

    public function setStatus() {
        if (!$this->request->is('json')) {
            return $this->redirect('/');
        }

        $redirect = '/';
        if ($user = User::first((int) Session::read('user.id'))) {
            if (!$this->request->data || ($this->request->data['who_am_i_fb'] == 'designer')) {
                $user->isDesigner = 1;
                $redirect = '/news';
                $status = 'designer';
            }
            if ($this->request->data['who_am_i_fb'] == 'client') {
                $user->isClient = 1;
                $status = 'client';
            }
            if ($this->request->data['who_am_i_fb'] == 'company') {
                $user->is_company = 1;
                $redirect = '/users/profile';
                $status = 'client';
            }
            $user->save(null, array('validate' => false));
            return array('result' => true, 'redirect' => $redirect, 'status' => $status);
        }

        return array('result' => false, 'error' => 'no user', 'redirect' => '/');
    }

    /**
     *  Метод входа, устанавлививет сессию и делает редирект в рабочий кабинет
     *
     *
     * @return \lithium\action\Returns|object*
     */
    public function login() {
        $user = User::create();
        if ($this->request->data) {
            $this->request->data['password'] = String::hash($this->request->data['password']);
            if (Auth::check('user', $this->request, array('checkSession' => true))) {

                $userToLog = User::first(Session::read('user.id'));
                /* if(!$userToLog->invited) {
                  Session::clear();
                  return $this->redirect("Users::login");
                  } */
                if ($userToLog->banned) {
                    Auth::clear('user');
                    return $this->redirect('/users/banned');
                }
                if ($userToLog->active == 0) {
                    Auth::clear('user');
                    return array('data' => true, 'redirect' => '/users/login');
                }
                $userToLog->lastTimeOnline = date('Y-m-d H:i:s');
                if ((isset($this->request->data['remember'])) && ($this->request->data['remember'] == 'on')) {
                    $userToLog->autologin_token = User::generateToken();
                    setcookie('autologindata', 'id=' . $userToLog->id . '&token=' . sha1($userToLog->autologin_token), time() + strtotime('+1 month'), '/');
                }
                $userToLog->save(null, array('validate' => false));
                /// FastPitches
                if (isset($_COOKIE['fastpitch'])) {
                    $fastId = unserialize($_COOKIE['fastpitch']);
                    $fastPitches = Pitch::all(array('conditions' => array('id' => $fastId)));
                    foreach ($fastPitches as $fastPitch) {
                        $fastPitch->user_id = $userToLog->id;
                    }
                    $fastPitches->save();
                }
                ///
                $redirect = false;
                $pitchId = Session::read('temppitch');
                if (!is_null($pitchId)) {
                    if ($pitch = Pitch::first($pitchId)) {
                        $pitch->user_id = $userToLog->id;
                        $pitch->save();
                    }
                    Session::delete('temppitch');
                    $redirect = '/pitches/edit/' . $pitchId . '#step3';
                }
                if (!is_null(Session::read('redirect'))) {
                    if (false === $redirect) {
                        $redirect = Session::read('redirect');
                    }
                    Session::delete('redirect');
                    return $this->redirect($redirect);
                } else {
                    return $this->redirect('Users::feed');
                }
            } else {
                Session::write('flash.login', 'Неверный адрес почты или пароль.');
                return $this->redirect("Users::login");
            }
        }
        if (is_null(Session::read('redirect')) && !is_null($_SERVER['HTTP_REFERER'])) {
            Session::write('redirect', $_SERVER['HTTP_REFERER']);
        }
        if (!is_null(Session::read('user.id'))) {
            return $this->redirect('Users::feed');
        }
        return compact('user');
    }

    public function banned() {
        
    }

    public function need_activation() {
        
    }

    public function resend() {
        $userid = Session::read('user.id');
        UserMailer::verification_mail(User::setUserToken($userid));
        return true;
    }

    /**
     *  Метод выхода, удаляем сессию и делаем редирект на главную страницу
     *
     *
     * */
    public function logout() {
        setcookie("autologindata", "", time() - 3600000, '/');
        Auth::clear('user');
        if (!is_null($_SERVER['HTTP_REFERER'])) {
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }
        return $this->redirect('/');
    }

    /**
     * Метод подтверждения почтового адреса пользователя
     *
     * @return array|\lithium\action\Returns|object
     */
    public function confirm() {
        if (isset($this->request->query['token'])) {
            $user = User::first(array('conditions' => array('token' => $this->request->query['token'])));
            if ($user) {
                $user->activateUser();
                UserMailer::hi_mail($user);
                Auth::clear('user');
                Auth::set('user', $user->data());
                Session::write('user.confirmed_email', 1);
                return $this->redirect(array('Users::office', '?' => array('success' => 'true')));
            } else {
                return $this->redirect(array('Users::registration', '?' => array('success' => 'false')));
            }
        } else {
            return $this->redirect(array('Users::registration', '?' => array('success' => 'false')));
        }
    }

    /**
     * Метод проверки существования имейла
     *
     * @TODO Cooldown timer
     */
    public function checkform() {
        if (isset($this->request->data['email'])) {
            if ($user = User::find('first', array('conditions' => array('email' => $this->request->data['email'])))) {
                return array('data' => true);
            }
        }
        return array('data' => false);
    }

    public function setnewpassword() {
        $token = $this->request->query['token'];
        if (isset($token)) {
            $user = User::first(array('conditions' => array('token' => $token)));
            if ($user) {
                Auth::set('user', $user->data());
                if ($this->request->data) {
                    $errors = array();
                    if (empty($this->request->data['password'])) {
                        $errors['password'] = 'Пароль пустой';
                    }
                    if (($this->request->data['password']) != ($this->request->data['confirm_password'])) {
                        $errors['password'] = 'Пароли не совпадают';
                    }
                    if (empty($errors)) {
                        $user->password = String::hash($this->request->data['password']);
                        $user->token = '';
                        $user->success = true;
                        $user->save(null, array('validate' => false));
                    }
                }
                return compact('token', 'user', 'errors');
            } else {
                return $this->redirect('Users::registration');
            }
        } else {
            return $this->redirect('Users::registration');
        }
    }

    public function second() {
        
    }

    public function recover() {
        if (Session::read('user.id')) {
            return $this->redirect('/users/profile');
        }
        $errors = array();
        $success = false;
        if ($this->request->data) {
            if ($user = User::findByEmail($this->request->data['email'])) {
                $user->token = User::generateToken();
                $user->save(null, array('validate' => false));
                UserMailer::forgotpassword_mail($user);
                $success = true;
            } else {
                $errors[] = 'Пользователь с таким Email не найден.';
            }
        }
        return compact('errors', 'success');
    }

    public function profile() {
        $user = User::first(Session::read('user.id'));
        if($user->id == '21376') {
            $this->redirect('/news');
        }
        $winnersData = Solution::all(array('conditions' => array('Solution.awarded' => 1, 'Pitch.private' => 0), 'order' => array('Solution.created' => 'desc'), 'limit' => 50, 'with' => array('Pitch')));
        $winners = array();
        foreach ($winnersData as $winner) {
            if ($winner->pitch->category_id != 7) {
                $winners[] = $winner;
            }
        }

        $passwordInfo = false;
        $emailInfo = false;
        if ($this->request->data) {

            $user->userdata = serialize(array(
                'birthdate' => $this->request->data['birthdate'],
                'city' => $this->request->data['city'],
                'profession' => $this->request->data['profession'],
                'about' => $this->request->data['about'],
            ));
            $user->isClient = $this->request->data['isClient'];
            $user->isDesigner = $this->request->data['isDesigner'];
            $user->isCopy = $this->request->data['isCopy'];
            $user->is_company = $this->request->data['is_company'];

            $user->first_name = $this->request->data['first_name'];
            $user->last_name = $this->request->data['last_name'];
            $user->gender = $this->request->data['gender'];

            $user->save(null, array('validate' => false));
        }
        return compact('user', 'winners', 'passwordInfo', 'emailInfo');
    }

    public function update() {
        $user = User::first(Session::read('user.id'));
        $currentEmail = $user->email;
        $result = false;
        if ($this->request->data) {
            $shortUpdate = false;
            if(isset($this->request->data['short_company_name'])) {
                $shortUpdate = true;
                $user->short_company_name = $this->request->data['short_company_name'];
                $user->companydata = serialize(array(
                    'company_name' => $this->request->data['company_name'],
                    'inn' => $this->request->data['inn'],
                    'kpp' => $this->request->data['kpp'],
                    'address' => $this->request->data['address'],
                ));
            }
            if(isset($this->request->data['first_name'])) {
                $shortUpdate = true;
                $user->first_name = $this->request->data['first_name'];
            }
            if(isset($this->request->data['last_name'])) {
                $shortUpdate = true;
                $user->last_name = $this->request->data['last_name'];
            }
            if(isset($this->request->data['gender'])) {
                $shortUpdate = true;
                $user->gender = $this->request->data['gender'];
            }
            if(isset($this->request->data['isClient'])) {
                $shortUpdate = true;
                $user->isClient = $this->request->data['isClient'];
            }
            if(isset($this->request->data['isDesigner'])) {
                $shortUpdate = true;
                $user->isDesigner = $this->request->data['isDesigner'];
            }
            if(isset($this->request->data['isCopy'])) {
                $shortUpdate = true;
                $user->isCopy = $this->request->data['isCopy'];
            }
            if(isset($this->request->data['is_company'])) {
                $shortUpdate = true;
                $user->is_company = $this->request->data['is_company'];
            }
            if(isset($this->request->data['birthdate'])) {
                $shortUpdate = true;
                $unserialized = unserialize($user->userdata);
                $user->userdata = serialize(array(
                    'birthdate' => $this->request->data['birthdate'],
                    'city' => $unserialized['city'],
                    'profession' => $unserialized['profession'],
                    'about' => $unserialized['about'],
                ));
            }
            if(isset($this->request->data['city'])) {
                $shortUpdate = true;
                $unserialized = unserialize($user->userdata);
                $user->userdata = serialize(array(
                    'birthdate' => $unserialized['birthdate'],
                    'city' => $this->request->data['city'],
                    'profession' => $unserialized['profession'],
                    'about' => $unserialized['about'],
                ));
            }
            if($shortUpdate) {
                $result = $user->save(null, array('validate' => false));
                if ($this->request->is('json')) {
                    return compact('result');
                }else {
                    return $this->redirect('/users/profile');
                }
            }

            if(isset($this->request->data['removephone'])) {
                $user->phone = '';
                $user->phone_valid = 0;
                $user->phone_code = '';
                $user->phone_operator = '';
                $user->save(null, array('validate' => false));
                return json_encode(true);
            }

            if(isset($this->request->data['resendcode'])) {
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

                $respond = SmsFeedback::send($user->phone, $user->phone_code . ' - код для проверки');
                $phone = $user->phone;
                $phone_valid = $user->phone_valid;
                return json_encode(compact('respond', 'phone', 'phone_valid'));
            }

            if(isset($this->request->data['phone'])) {
                if (!preg_match("/^[0-9]{11,12}+$/", $this->request->data['phone'])) {
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

                return json_encode(User::phoneValidationStart($user->id, $this->request->data['phone']));

            }

            if(isset($this->request->data['code'])) {
                return json_encode(User::phoneValidationFinish($user->id, (int) $this->request->data['code']));
            }

            if(isset($this->request->data['email'])) {
                if ($userWithEmail = User::first(array(
                    'conditions' => array(
                        'email' => $this->request->data['email'],
                        'id' => array(
                            '!=' => $user->id,
                        ),
                    )))) {
                    $emailInfo = 'Пользователь с таким адресом электронной почты уже существует!';
                    $result = false;
                } else {
                    $user->email = $this->request->data['email'];
                    if ($currentEmail != $this->request->data['email']) {
                        $emailInfo = 'Адрес электронной почты изменён, вам необходимо подтвердить его!';
                        $user->confirmed_email = 0;
                        $user->token = User::generateToken();
                        Session::write('user.email', $user->email);
                        UserMailer::verification_mail($user);
                        $result = $user->save(null, array('validate' => false));
                    }
                }
                return compact('result', 'emailInfo');
            }elseif(isset($this->request->data['newpassword'])) {
                $result = false;
                $passwordInfo = 'Пароль не введён!';
                if (($this->request->data['newpassword'] != '') && ($this->request->data['confirmpassword'] != '')) {
                    $hashedCurrentPassword = String::hash($this->request->data['currentpassword']);

                    if ($hashedCurrentPassword != $user->password) {
                        $passwordInfo = 'Старый пароль не верен!';
                    } elseif (($this->request->data['newpassword']) != ($this->request->data['confirmpassword'])) {
                        $passwordInfo = 'Пароли не совпадают!';
                    } else {
                        $user->password = String::hash($this->request->data['newpassword']);
                        $result = $user->save(null, array('validate' => false));
                        $passwordInfo = 'Пароль изменен!';
                    }
                }
                return compact('result', 'passwordInfo');
            }else {
                if (isset($this->request->data['email_newpitch'])) {
                    $user->email_newpitch = 1;
                } else {
                    $user->email_newpitch = 0;
                }
                if (isset($this->request->data['email_newcomments'])) {
                    $user->email_newcomments = 1;
                } else {
                    $user->email_newcomments = 0;
                }
                if (isset($this->request->data['email_newpitchonce'])) {
                    $user->email_newpitchonce = 1;
                } else {
                    $user->email_newpitchonce = 0;
                }
                if (isset($this->request->data['email_newsolonce'])) {
                    $user->email_newsolonce = 1;
                } else {
                    $user->email_newsolonce = 0;
                }
                if (isset($this->request->data['email_newsol'])) {
                    $user->email_newsol = 1;
                } else {
                    $user->email_newsol = 0;
                }
                if (isset($this->request->data['email_digest'])) {
                    $user->email_digest = 1;
                } else {
                    $user->email_digest = 0;
                }
                if (isset($this->request->data['email_onlycopy'])) {
                    $user->email_onlycopy = 1;
                } else {
                    $user->email_onlycopy = 0;
                }
            }
            $result = $user->save(null, array('validate' => false));
        }
        if ($this->request->is('json')) {
            return compact('result');
        }else {
            return $this->redirect('/users/profile');
        }
    }

    public function preview() {
        if($this->request->id != Session::read('user.id')) {
            $this->redirect('/users/view/' . $this->request->id);
        }
        if ($user = User::first(Session::read('user.id'))) {
            $pitchCount = User::getPitchCount(Session::read('user.id'));
            $averageGrade = User::getAverageGrade(Session::read('user.id'));
            if (!$averageGrade) {
                $averageGrade = 0;
            }
            $totalViews = (int) User::getTotalViews(Session::read('user.id'));
            $totalLikes = (int) User::getTotalLikes(Session::read('user.id'));
            $totalFavoriteMe = Favourite::getCountFavoriteMe($user->id);
            $totalUserFavorite = Favourite::getCountFavoriteUser($user->id);
            $awardedSolutionNum = (int) User::getAwardedSolutionNum(Session::read('user.id'));
            $totalSolutionNum = (int) User::getTotalSolutionNum(Session::read('user.id'));
            if (User::checkRole('admin')) {
                $selectedSolutions = Solution::getUsersSolutions($this->request->id);
            } else {
                $selectedSolutions = Solution::getUsersSolutions($this->request->id, true);
            }
            foreach($selectedSolutions as $solution) {
                $solution->tags = Solution::getTagsArrayForSolution($solution);
            }
            $isClient = false;
            $userPitches = Pitch::all(array('conditions' => array('user_id' => $user->id, 'billed' => 1)));
            if (count($userPitches) > 0) {
                $isClient = true;
            }
            return compact('user', 'pitchCount', 'averageGrade', 'totalUserFavorite', 'totalFavoriteMe', 'totalViews', 'totalLikes', 'awardedSolutionNum', 'totalSolutionNum', 'selectedSolutions', 'isClient');
        }
    }

    public function view() {
        if ($user = User::first($this->request->id)) {
            if (($user->active == 0) && !User::checkRole('admin')):
                return $this->redirect('/');
            endif;

            $pitchCount = User::getPitchCount($this->request->id);
            $averageGrade = User::getAverageGrade($this->request->id);
            if (!$averageGrade) {
                $averageGrade = 0;
            }
            $totalViews = (int) User::getTotalViews($this->request->id);
            $totalLikes = (int) User::getTotalLikes($this->request->id);
            $awardedSolutionNum = (int) User::getAwardedSolutionNum($this->request->id);
            $totalSolutionNum = (int) User::getTotalSolutionNum($this->request->id);
            $totalFavoriteMe = Favourite::getCountFavoriteMe($user->id);
            $totalUserFavorite = Favourite::getCountFavoriteUser($user->id);
            $isFav = Favourite::first(array('conditions' => array('user_id' => Session::read('user.id'), 'fav_user_id' => $user->id)));
            if (User::checkRole('admin')) {
                $selectedSolutions = Solution::getUsersSolutions($this->request->id);
            } else {
                $selectedSolutions = Solution::getUsersSolutions($this->request->id, true);
            }
            $moderations = null;
            if (User::checkRole('admin') || (Session::read('user.isAdmin') == 1)) {
                $moderations = Moderation::all(array('conditions' => array('model_user' => $user->id)));
            }
            $isClient = false;
            $userPitches = Pitch::all(array('conditions' => array('billed' => 1, 'user_id' => $user->id)));
            if (count($userPitches) > 0) {
                $isClient = true;
                $ids = array();
                foreach ($userPitches as $pitch) {
                    $ids[] = $pitch->id;
                }
                $selectedSolutions = Solution::all(array('conditions' => array(
                                'pitch_id' => $ids,
                                'OR' => array(array('rating = 4'), array('rating = 5'), array('Solution.awarded = 1'), array('Solution.nominated = 1'))
                            ),
                            'with' => array('Pitch')
                ));
            }
            return compact('user', 'pitchCount', 'totalUserFavorite', 'isFav', 'totalFavoriteMe', 'averageGrade', 'totalViews', 'totalLikes', 'awardedSolutionNum', 'totalSolutionNum', 'selectedSolutions', 'isClient', 'moderations');
        }
        throw new Exception('Public:Такого пользователя не существует.', 404);
    }

    public function savePaymentData() {
        $user = User::first(Session::read('user.id'));
        $user->paymentOptions = serialize(array($this->request->data));
        $user->save(null, array('validate' => false));
        return $user->paymentOptions;
    }

    public function loginasadmin() {
        $token = $this->request->query['token'];
        $redirect = false;
        if (isset($this->request->query['redirect'])) {
            $redirect = $this->request->query['redirect'];
        }
        if (isset($token)) {
            $user = User::first(array('conditions' => array('token' => $token, 'isAdmin' => 1)));
            if ($user) {
                Auth::clear('user');
                Auth::set('user', $user->data());
                if (!$redirect) {
                    return $this->redirect('/');
                } else {
                    return $this->redirect($redirect);
                }
            } else {
                return $this->redirect('Users::registration');
            }
        } else {
            return $this->redirect('Users::registration');
        }
    }

    public function updatetwitter() {
        $api = new TwitterAPI();
        $api->search('godesigner.ru', function($object) {
            $data = json_decode($object->response['response'], true);
            $censoredTweets = array();
            $censoredTweets['statuses'] = array();
            $minTimestamp = 1893355200;
            foreach ($data['statuses'] as $key => &$tweet) {
                echo '<pre>';
                $delete = false;
                if (isset($tweet['entities']) and isset($tweet['entities']['urls'])) {
                    foreach ($tweet['entities']['urls'] as $url) {
                        if ($matches = preg_match('*godesigners.ru/\?ref\=*', $url['expanded_url'])) {
                            $delete = true;
                        }
                    }
                }
                if ($delete == false) {
                    $tweet['timestamp'] = strtotime($tweet['created_at']);
                    $minTimestamp = ($tweet['timestamp'] < $minTimestamp) ? $tweet['timestamp'] : $minTimestamp;
                    if (isset($tweet['entities']) and isset($tweet['entities']['media'])) {
                        $tweet['thumbnail'] = $tweet['entities']['media'][0]['media_url_https'];
                    }
                    $censoredTweets['statuses'][$key] = $tweet;
                }
            }

            if (($tutPosts = Wp_post::getPostsForStream($minTimestamp)) && (count($tutPosts) > 0)) {
                foreach ($tutPosts as $post) {
                    $censoredTweets['statuses'][] = array(
                        'type' => 'tutdesign',
                        'text' => $post->post_title,
                        'timestamp' => strtotime($post->post_modified),
                        'created_at' => $post->post_modified,
                        'slug' => $post->post_name,
                        'category' => $post->category,
                        'id' => $post->ID,
                        'thumbnail' => $post->thumbnail,
                    );
                }
            }

            uasort($censoredTweets['statuses'], function($a, $b) {
                return ($a['timestamp'] > $b['timestamp']) ? -1 : 1;
            });

            $res = Rcache::write('twitterstream', $censoredTweets);
            echo '<pre>';
            var_dump($censoredTweets['statuses']);
            die();
        });
    }

    public function updatetwitterfeed() {
        $api = new TwitterAPI();
        $hashTags = array('работадлядизайнеров');
        $x = 0;
        $url = '';
        $countTags = count($hashTags);
        foreach ($hashTags as $tag) {
            ++$x;
            $url .= $countTags > $x ? '%23' . $tag . '+' : '%23' . $tag;
        }
        $api->search('работадлядизайнеров', function($object) {
            $data = json_decode($object->response['response'], true);
            $censoredTweets = array();
            $censoredTweets['statuses'] = array();
            $minTimestamp = 1893355200;
            $listOfUsedIds = array();
            foreach ($data['statuses'] as $key => &$tweet) {
                $delete = false;
                if (isset($tweet['entities']) and isset($tweet['entities']['urls'])) {
                    foreach ($tweet['entities']['urls'] as $url) {
                        if ($matches = preg_match('*godesigners.ru/\?ref\=*', $url['expanded_url'])) {
                            $delete = true;
                        }
                    }
                }
                if (in_array($tweet['id_str'], $listOfUsedIds)) {
                    $delete = true;
                }
                if ($delete == false) {
                    $content = '';
                    $listOfUsedIds[] = $tweet['id_str'];
                    $tweet['timestamp'] = strtotime($tweet['created_at']);
                    $minTimestamp = ($tweet['timestamp'] < $minTimestamp) ? $tweet['timestamp'] : $minTimestamp;
                    $censoredTweets['statuses'][$key] = $tweet;
                    $text = $tweet['text'];
                    if (!isset($tweet['type']) && $tweet['type'] !== 'tutdesign') {
                        foreach ($tweet['entities']['hashtags'] as $hashtag) {
                            $text = str_replace('#' . $hashtag['text'], '<a style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/search/%23' . $hashtag['text'] . '">' . '#' . $hashtag['text'] . '</a>', $text);
                        }
                        foreach ($tweet['entities']['urls'] as $url) {

                            $text = str_replace($url['url'], '<a class="url-twitter" style="display:inline;color:#ff585d" target="_blank" href="' . $url['url'] . '">' . $url['display_url'] . '</a>', $text);
                        }
                        foreach ($tweet['entities']['user_mentions'] as $user) {

                            $text = str_replace('@' . $user['screen_name'], '<a style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/' . $user['screen_name'] . '">' . '@' . $user['screen_name'] . '</a>', $text);
                        }
                        $user = '<a style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/' . $tweet['user']['screen_name'] . '">@' . $tweet['user']['screen_name'] . '</a>';
                        $content .= $user . ' ' . $text;
                        $content = preg_replace("/<img[^>]+\>/i", '', $content);
                    }
                }
            }

            uasort($censoredTweets['statuses'], function($a, $b) {
                return ($a['timestamp'] > $b['timestamp']) ? -1 : 1;
            });
            $res = Rcache::write('twitterstreamFeed', $censoredTweets);
            echo '<pre>';
            var_dump($censoredTweets['statuses']);
        });
        die();
    }

    public function details() {
        return $this->redirect('/users/profile');
        /*$user = User::first(Session::read('user.id'));
        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('user');
        } else {
            return $this->render(array('layout' => false, 'data' => compact('user')));
        }*/
    }

    public function ban() {
        if (($user = User::first($this->request->data['id'])) && (Session::read('user.isAdmin') == 1 || (in_array(Session::read('user.id'), User::$admins)))) {
            $term = $this->request->data['term'] * DAY;
            $user->silenceUntil = date('Y-m-d H:i:s', time() + $term);
            $user->silenceCount += 1;
            $user->save(null, array('validate' => false));
            UserMailer::ban(array('user' => $user->data(), 'term' => $this->request->data['term']));
        }

        return $user->data();
    }

    public function unban() {
        if (($user = User::first($this->request->data['id'])) && (Session::read('user.isAdmin') == 1 || (in_array(Session::read('user.id'), User::$admins)))) {
            $user->silenceUntil = date('Y-m-d H:i:s');
            $user->save(null, array('validate' => false));
        }
        return $user->data();
    }

    public function unblock() {
        if (($user = User::first($this->request->data['id'])) && (Session::read('user.isAdmin') == 1 || (in_array(Session::read('user.id'), User::$admins)))) {
            $user->banned = 0;
            $user->save(null, array('validate' => false));
            UserMailer::block(array('user' => $user->data()));
            return $this->request->data;
        }
    }

    public function block() {
        if (($user = User::first($this->request->data['id'])) && (Session::read('user.isAdmin') == 1 || (in_array(Session::read('user.id'), User::$admins)))) {
            $user->block();
            UserMailer::block(array('user' => $user->data()));
            return $this->request->data;
        }
    }

    public function loginasuser() {
        if (in_array(Session::read('user.id'), User::$admins)) {
            if ($user = User::first($this->request->id)) {
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
        if (($this->request->data) && ($this->request->data['case'] == 'fu27fwkospf')) {
            if ($this->request->data['target'] != 0) {
                $emails = array(
                    1 => 'devochkina@godesigner.ru',
                    2 => 'va@godesigner.ru',
                    3 => 'nyudmitriy@godesigner.ru',
                    4 => 'fedchenko@godesigner.ru'
                );
                $this->request->data['target'] = $emails[$this->request->data['target']];
            } else {
                $this->request->data['target'] = 'team@godesigner.ru';
            }
            $this->request->data['subject'] = 'Сообщение с сайта GoDesigner.ru: ' . $this->request->data['email'];
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
        if (isset($_GET['code'])) {
            var_dump($this->request->data);
            var_dump($this->request->params);
            $client_id = '2950889'; // ID приложения
            $client_secret = 'j1bFzKfXP4lIa0wA7vaV'; // Защищённый ключ
            // получаем access_token
            $resp = file_get_contents('https://api.vk.com/oauth/access_token?client_id=' . $client_id . '&code=' . $_REQUEST['code'] . '&client_secret=' . $client_secret);
            $data = json_decode($resp, true);


            if ($data['access_token']) {

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
                header('Location: ' . PATH . 'index.php');
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

    public function click() {
        $news = News::first($this->request->query['id']);
        if ($news) {
            $news->views += 1;
            $news->save();
        }
        return $this->redirect($this->request->query['link']);
    }

    public function gender() {
        $user = User::first($this->request->id);
        if ($user) {
            $gender = 0;
            if ($this->request->data['gender'] == 'male') {
                $gender = 1;
            } elseif ($this->request->data['gender'] == 'female') {
                $gender = 2;
            }
            $user->gender = $gender;
            Session::write('user.gender', $gender);
            return json_encode($user->save(null, array('validate' => false)));
        }
    }

    public function sale() {
        if (isset($this->request->query['id']) && strlen($this->request->query['id']) > 0) {
            $cache = Rcache::read('SpamDsicountWeek');
            $email_hash = $this->request->query['id'];
            if (array_key_exists($email_hash, $cache)) {
                $data = $cache[$email_hash];
                $hash = sha1($data['user_id'] . 'spmWeek');
                $complete_hash = sha1($hash . $data['pitch_id'] . $data['email']);
                if ($complete_hash === $email_hash) {
                    $user = User::first($data['user_id']);
                    Auth::set('user', $user->data());
                    unset($cache[$complete_hash]);
                    Rcache::write('SpamDsicountWeek', $cache);
                    return $this->redirect(array('controller' => 'pitches', 'action' => 'view', 'id' => $data['pitch_id']));
                }
            }
        }
        return $this->redirect('/');
    }

    /**
     *  Метод для вывода страницы абонентского кабинета
     */
    public function subscriber() {

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

        if ($realSize != $this->getSize()) {
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
        if (isset($_SERVER["CONTENT_LENGTH"])) {
            return (int) $_SERVER["CONTENT_LENGTH"];
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
        if (!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)) {
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

    function __construct(array $allowedExtensions = array(), $sizeLimit = 104857600) {
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

    private function checkServerSettings() {
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($str) {
        $val = trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = true) {
        if (!is_writable($uploadDirectory)) {
            return array('error' => "Server error. Upload directory isn't writable.");
        }

        if (!$this->file) {
            return array('error' => 'No files were uploaded.');
        }



        $pathinfo = pathinfo($this->file->getName());
        $originalname = $pathinfo['filename'];
        $filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of ' . $these . '.');
        }

        if (!$replaceOldFile) {
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }

        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)) {
            return array('success' => true, 'name' => $originalname . '.' . $ext, 'tmpname' => $uploadDirectory . $filename . '.' . $ext);
        } else {
            return array('error' => 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
    }

}