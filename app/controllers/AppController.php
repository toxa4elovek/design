<?php

namespace app\controllers;

use \app\models\Answer;
use \app\models\Solution;
use \app\models\Pitch;
use \app\models\User;
use \app\models\Event;
use \app\models\Post;
use \app\models\Favourite;
use \lithium\storage\Session;
use \lithium\storage\session\adapter\Cookie;
use \lithium\security\Auth;


class AppController extends \lithium\action\Controller {

    public function _init() {
        parent::_init();
        if(Session::read('user.id')) {
            Session::write('user.attentionpitch', null);
            Session::write('user.attentionsolution', null);
            Session::write('user.timeoutpitch', null);
            if($user = User::find(Session::read('user.id'))) {
                // Проверяем, ни забанен ли пользователь
                if($user->banned) {
                    Auth::clear('user');
                    return $this->redirect('/users/banned');
                }
                // Проверяем, не удалил ли себя пользователь
                if($user->email == '') {
                    Auth::clear('user');
                    return $this->redirect('/');
                }
                /*if(($user->confirmed_email == 0) && (!in_array($this->request->params['action'], $this->publicActions) && ($this->request->params['action']!= 'viewmail') && ($this->request->params['action']!= 'resend'))){
                    return $this->redirect('/users/need_activation');
                }*/
            }

            // updates avatars
            Session::write('user.images', $user->images);

            $topPanel = Pitch::all(array(
                'with' => array('Category'),
                'conditions' => array('Pitch.user_id' => Session::read('user.id'), 'Pitch.status' => array('<' => 2)),

            ));
            foreach($topPanel as $pitch):
                if($pitch->awarded != 0):
                    $pitch->winner = Solution::first($pitch->awarded);
                endif;
            endforeach;

            Session::write('user.currentpitches', $topPanel);
            /** ** **/
            //array('conditions' => array('awarded' => array('!=' => 0), 'status' => 1))
            $topPanelDesigner = array();
            $pitchesToCheck = Pitch::all(array('with' => array('Category'), 'conditions' => array('awarded' => array('!=' => 0), 'status' => 1)));
            foreach($pitchesToCheck as $pitch) {
                $solution = Solution::first($pitch->awarded);
                if($solution->user_id == Session::read('user.id')) {
                    $topPanelDesigner[] = $pitch;
                }
            }

            foreach($topPanelDesigner as $pitch):
                if($pitch->awarded != 0):
                    $pitch->winner = Solution::first($pitch->awarded);
                endif;
            endforeach;

            Session::write('user.currentdesignpitches', $topPanelDesigner);
            /** faves */
            $faves = Favourite::all(array('conditions' => array('user_id' => Session::read('user.id'))));
            $favesPitchIds = array();
            foreach($faves as $fave) {
                $favesPitchIds[] = $fave->pitch_id;
            }
            Session::write('user.faves', $favesPitchIds);
            if((Session::read('user.blogpost') == null) || (Session::read('user.blogpost.count') == 0)) {
                    $lastPost = Post::first(array('conditions' => array('published' => 1), 'order' => array('created' => 'desc')));
                    $date = date('Y-m-d H:i:s', strtotime($lastPost->created));

                    if(isset($_COOKIE['counterdata'])) {
                        $counterData = unserialize($_COOKIE['counterdata']);
                        if(isset($counterData[Session::read('user.id')])) {
                            $date = $counterData[Session::read('user.id')]['date'];
                        }
                    }
                    $count = Post::count(array('conditions' => array('created' => array('>' => $date), 'published' => 1)));
                    Session::write('user.blogpost.count', $count);

                    $counterData = array(Session::read('user.id') => array('date' => $date));
                    setcookie('counterdata', serialize($counterData), time() + strtotime('+1 month'), '/');
                }


                if((Session::read('user.events') == null) || (Session::read('user.events.count') == 0)) {
                    $date = date('Y-m-d H:i:s');
                    if(Session::read('user.events.date') != null) {
                        $date = Session::read('user.events.date');
                    }
                    Session::write('user.events.date', $date);
                    if($updates = Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), 1, $date)) {

                        Session::write('user.events.count', count($updates));
                    }else {
                        Session::write('user.events.count', 0);
                    }
                }
            $user->setLastActionTime();
        }else {
            if(isset($_COOKIE['autologindata'])) {

                $exploded = explode('&', $_COOKIE['autologindata']);
                $id = (explode('=', $exploded[0]));
                $id = $id[1];
                $token = (explode('=', $exploded[1]));
                $token = $token[1];
                if(($user = User::first($id)) && (sha1($user->token) == $token)) {
                    if($user->banned) {
                        Auth::clear('user');
                        return $this->redirect('/users/banned');
                    }
                    if($user->email == '') {
                        Auth::clear('user');
                        return $this->redirect('/');
                    }
                    $user->lastTimeOnline = date('Y-m-d H:i:s');
                    $user->token = User::generateToken();
                    setcookie('autologindata', 'id=' . $user->id . '&token=' . sha1($user->token), time() + strtotime('+1 month') );
                    $user->save(null, array('validate' => false));
                    Auth::set('user', $user->data());
                }
            }
        }

        // Get Promocode Length
        $r = new \ReflectionMethod('\app\models\Promocode', 'generateToken');
        $params = $r->getParameters();
        foreach ($params as $param) {
            if ($param->getName() == 'length' && $param->isOptional() == 1) {
                $promocodeLength = $param->getDefaultValue();
                break;
            }
        }

        if (isset($_GET['promocode']) && !empty($_GET['promocode']) && ((mb_strlen($_GET['promocode'], 'UTF-8') == 3) || (mb_strlen($_GET['promocode'], 'UTF-8') == 4))) {
            Session::write('promocode', $_GET['promocode']);
        }

        if (!empty($this->request->query['ref'])) {
            User::setReferalCookie($this->request->query['ref']);
        }
    }

    protected function popularQuestions() {
        return Answer::all(array('limit' => 5, 'order' => array('hits' => 'desc')));
    }
}
