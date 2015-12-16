<?php

namespace app\controllers;

use app\extensions\helper\Debug;
use app\extensions\helper\MoneyFormatter as Money;
use app\extensions\helper\User as UserHelper;
use app\models\Answer;
use app\models\Favourite;
use app\models\Pitch;
use app\models\Post;
use app\models\Solution;
use app\models\User;
use lithium\storage\Session;
use lithium\security\Auth;

class AppController extends \lithium\action\Controller
{

    public $userHelper = null;
    public $money = null;
    public $debug = null;

    public function _init()
    {
        parent::_init();
        $this->userHelper = new UserHelper();
        $this->money = new Money();
        $this->debug = new Debug();
        if ($this->userHelper->isLoggedIn()) {
            if (function_exists('newrelic_add_custom_parameter')) {
                newrelic_add_custom_parameter('userId', $this->userHelper->getId());
            }
            Session::write('user.attentionpitch', null);
            Session::write('user.attentionsolution', null);
            Session::write('user.timeoutpitch', null);
            if ($user = User::find($this->userHelper->getId())) {
                // Проверяем, ни забанен ли пользователь
                if ($user->banned) {
                    Auth::clear('user');
                    return $this->redirect('/users/banned');
                }
                // Проверяем, не удалил ли себя пользователь
                if ($user->email == '') {
                    Auth::clear('user');
                    return $this->redirect('/');
                }
                /*if(($user->confirmed_email == 0) && (!in_array($this->request->params['action'], $this->publicActions) && ($this->request->params['action']!= 'viewmail') && ($this->request->params['action']!= 'resend'))){
                    return $this->redirect('/users/need_activation');
                }*/


                // updates avatars
                Session::write('user.images', $user->images);
                $topPanel = Pitch::all(
                    array(
                        'with' => array('Category'),
                        'conditions' => array(
                            array('AND' => array(
                                array("Pitch.type != 'fund-balance'"),
                            )),
                            array('OR' => array(
                                array('Pitch.user_id = ' . $this->userHelper->getId() . ' AND Pitch.status < 2 AND Pitch.blank = 0'),
                                array('Pitch.user_id = ' . $this->userHelper->getId() . ' AND Pitch.status < 2 AND Pitch.billed = 1 AND Pitch.blank = 1'),
                            )),
                        )
                    )
                );
                foreach ($topPanel as $pitch):
                    if ($pitch->awarded != 0):
                        $pitch->winner = Solution::first($pitch->awarded);
                endif;
                endforeach;

                Session::write('user.currentpitches', $topPanel);
                /** ** **/
                $topPanelDesigner = array();
                $wonProjectsIds = User::getUsersWonProjectsIds($this->userHelper->getId());
                if (!empty($wonProjectsIds)) {
                    $pitchesToCheck = Pitch::all(array(
                        'with' => array('Category'),
                        'conditions' => array('Pitch.id' => $wonProjectsIds),
                    ));
                    foreach ($pitchesToCheck as $pitch) {
                        $solution = Solution::first($pitch->awarded);
                        if ($this->userHelper->isSolutionAuthor($solution->user_id)) {
                            if (($pitch->status == 2) and ($pitch->hadDesignerLeftRating())) {
                            } else {
                                $pitch->winner = $solution;
                                $topPanelDesigner[] = $pitch;
                            }
                        }
                    }
                }

                Session::write('user.currentdesignpitches', $topPanelDesigner);
                /** faves */
                Session::write('user.faves', Favourite::getFavouriteProjectsIdsForUser($this->userHelper->getId()));
                if ((Session::read('user.blogpost') == null) || (Session::read('user.blogpost.count') == 0)) {
                    $lastPost = Post::first(array('conditions' => array('published' => 1), 'order' => array('created' => 'desc')));
                    $date = date('Y-m-d H:i:s', strtotime($lastPost->created));

                    if (isset($_COOKIE['counterdata'])) {
                        $counterData = unserialize($_COOKIE['counterdata']);
                        if (isset($counterData[$this->userHelper->getId()])) {
                            $date = $counterData[$this->userHelper->getId()]['date'];
                        }
                    }
                    $count = Post::count(array('conditions' => array('created' => array('>' => $date), 'published' => 1)));
                    Session::write('user.blogpost.count', $count);

                    $counterData = array($this->userHelper->getId() => array('date' => $date));
                    setcookie('counterdata', serialize($counterData), time() + strtotime('+1 month'), '/');
                }

/*
                if((Session::read('user.events') == null) || (Session::read('user.events.count') == 0)) {
                    $date = date('Y-m-d H:i:s');
                    if(Session::read('user.events.date') != null) {
                        $date = Session::read('user.events.date');
                    }
                    Session::write('user.events.date', $date);
                    if($updates = Event::getEvents(User::getSubscribedPitches($this->userHelper->getId()), 1, $date)) {

                        Session::write('user.events.count', count($updates));
                    }else {
                        Session::write('user.events.count', 0);
                    }
                }*/
                $user->setLastActionTime();
            }
        } else {
            if (isset($_COOKIE['autologindata'])) {
                $exploded = explode('&', $_COOKIE['autologindata']);
                $id = (explode('=', $exploded[0]));
                if (count($id) > 0) {
                    $id = $id[1];
                    $token = (explode('=', $exploded[1]));
                    $token = $token[1];
                    if (($user = User::first($id)) && (sha1($user->autologin_token) == $token)) {
                        if ($user->banned) {
                            Auth::clear('user');
                            return $this->redirect('/users/banned');
                        }
                        if ($user->email == '') {
                            Auth::clear('user');
                            return $this->redirect('/');
                        }
                        $user->lastTimeOnline = date('Y-m-d H:i:s');
                        $user->autologin_token = $user->generateToken();
                        setcookie('autologindata', 'id=' . $user->id . '&token=' . sha1($user->token), time() + strtotime('+1 month'));
                        $user->save(null, array('validate' => false));
                        Auth::set('user', $user->data());
                    }
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

    protected function popularQuestions($limit = 5)
    {
        return Answer::getPopularQuesions($limit);
    }
}
