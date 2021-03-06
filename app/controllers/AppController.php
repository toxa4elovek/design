<?php

namespace app\controllers;

use app\extensions\helper\Debug;
use app\extensions\helper\MoneyFormatter as Money;
use app\extensions\helper\User as UserHelper;
use app\models\Addon;
use app\models\Answer;
use app\models\Category;
use app\models\Favourite;
use app\models\Manager;
use app\models\Pitch;
use app\models\Post;
use app\models\Solution;
use app\models\User;
use lithium\action\Controller;
use lithium\security\Auth;

class AppController extends Controller
{

    /**
     * @var \app\extensions\helper\User
     */
    public $userHelper = null;

    /**
     * @var null хелпер Money
     */
    public $money = null;

    /**
     * @var null хелпер Debug
     */
    public $debug = null;

    /**
     * @var null
     */
    public $userRecord = null;

    /**
     * @var int текущий размер скидки для абонентного плана
     */
    public $discountForSubscriberReferal = 0;

    /**
     * @return object инициализация каждого запроса
     */
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
            $this->userHelper->write('user.attentionpitch', null);
            $this->userHelper->write('user.attentionsolution', null);
            $this->userHelper->write('user.timeoutpitch', null);
            if ($userRecord = User::first($this->userHelper->getId())) {
                $this->userHelper->write('user.confirmed_email', $userRecord->confirmed_email);
                // Проверяем, ни забанен ли пользователь
                if ($userRecord->banned) {
                    Auth::clear('user');
                    return $this->redirect('/users/banned');
                }
                if ($userRecord->banned_until && ($userRecord->banned_until !== '0000-00-00 00:00:00') && (time() < strtotime($userRecord->banned_until))) {
                    Auth::clear('user');
                    return $this->redirect('/users/banned');
                }
                // Проверяем, не удалил ли себя пользователь
                if ($userRecord->email === '') {
                    Auth::clear('user');
                    return $this->redirect('/');
                }
                // updates avatars
                $this->userHelper->write('user.images', $userRecord->images);
                $ids = $this->userHelper->getId();
                if ($ids !== 108 && $this->userHelper->isAdmin()) {
                    $ids .= ' OR Pitch.user_id = 108';
                }
                if ($this->userHelper->isSubscriptionActive() && ($records = Manager::all(['conditions' => ['subscriber_id' => $this->userHelper->getId()]]))) {
                    foreach ($records as $record) {
                        $ids .= ' OR Pitch.user_id = ' . $record->manager_id;
                    }
                }
                $topPanel = Pitch::all(
                    [
                        'with' => ['Category'],
                        'conditions' => [
                            ['AND' => [
                                ["Pitch.type != 'fund-balance'"],
                            ]],
                            ['OR' => [
                                ['(Pitch.user_id = ' . $ids . ') AND (Pitch.status < 2 AND Pitch.blank = 0)'],
                                ['(Pitch.user_id = ' . $ids . ') AND (Pitch.status < 2 AND Pitch.billed = 1 AND Pitch.blank = 1)'],
                            ]],
                        ]
                    ]
                );
                foreach ($topPanel as $pitch):
                    if ($pitch->awarded != 0):
                        $pitch->winner = Solution::first($pitch->awarded);
                endif;
                endforeach;

                $this->userHelper->write('user.currentpitches', $topPanel);

                $topPanelDesigner = [];
                $wonProjectsIds = User::getUsersWonProjectsIds($this->userHelper->getId());
                if (!empty($wonProjectsIds)) {
                    $pitchesToCheck = Pitch::all([
                        'with' => ['Category'],
                        'conditions' => ['Pitch.id' => $wonProjectsIds],
                    ]);
                    foreach ($pitchesToCheck as $pitch) {
                        $solution = Solution::first($pitch->awarded);
                        if ($this->userHelper->isSolutionAuthor($solution->user_id) && ((int) $pitch->status !== 2 && !$pitch->hadDesignerLeftRating())) {
                            $pitch->winner = $solution;
                            $pitch->invited = false;
                            $topPanelDesigner[] = $pitch;
                        }
                    }
                }
                $invites = Addon::all([
                    'conditions' => [
                        'Addon.billed' => 1,
                        'Addon.invite_id' => $this->userHelper->getId(),
                        'Addon.invite_status' => 0,
                        'Pitch.status' => 0
                    ],
                    'with' => ['Pitch']
                ]);
                if(count($invites) > 0) {
                    foreach ($invites as $invite) {
                        $invite->pitch->category = Category::first($invite->pitch->category_id);
                        $invite->pitch->invited = true;
                        $topPanelDesigner[] = $invite->pitch;
                    }
                }
                $this->userHelper->write('user.currentdesignpitches', $topPanelDesigner);
                $this->userHelper->write('user.faves', Favourite::getFavouriteProjectsIdsForUser($this->userHelper->getId()));
                if (($this->userHelper->read('user.blogpost') == null) || ((int) $this->userHelper->read('user.blogpost.count') === 0)) {
                    $lastPost = Post::first(['fields' => ['created'], 'conditions' => ['published' => 1], 'order' => ['created' => 'desc']]);
                    $date = date('Y-m-d H:i:s', strtotime($lastPost->created));

                    if (isset($_COOKIE['counterdata'])) {
                        $counterData = unserialize($_COOKIE['counterdata']);
                        if (isset($counterData[$this->userHelper->getId()])) {
                            $date = $counterData[$this->userHelper->getId()]['date'];
                        }
                    }
                    $count = Post::count(['conditions' => ['created' => ['>' => $date], 'published' => 1]]);
                    $this->userHelper->write('user.blogpost.count', $count);

                    $counterData = [$this->userHelper->getId() => ['date' => $date]];
                    setcookie('counterdata', serialize($counterData), time() + strtotime('+1 month'), '/');
                }
                $userRecord->setLastActionTime();
            }
        } else {
            if (isset($_COOKIE['autologindata'])) {
                $exploded = explode('&', $_COOKIE['autologindata']);
                $id = explode('=', $exploded[0]);
                if (count($id) > 0) {
                    $id = $id[1];
                    $token = explode('=', $exploded[1]);
                    $token = $token[1];
                    if (($userRecord = User::first($id)) && (sha1($userRecord->autologin_token) == $token)) {
                        if ($userRecord->banned) {
                            Auth::clear('user');
                            return $this->redirect('/users/banned');
                        }
                        if ($userRecord->email == '') {
                            Auth::clear('user');
                            return $this->redirect('/');
                        }
                        $userRecord->lastTimeOnline = date('Y-m-d H:i:s');
                        $userRecord->autologin_token = $userRecord->generateToken();
                        setcookie('autologindata', 'id=' . $userRecord->id . '&token=' . sha1($userRecord->token), time() + strtotime('+1 month'));
                        $userRecord->save(null, ['validate' => false]);
                        Auth::set('user', $userRecord->data());
                    }
                }
            }
        }

        if (isset($_GET['promocode']) && !empty($_GET['promocode']) && ((mb_strlen($_GET['promocode'], 'UTF-8') == 3) || (mb_strlen($_GET['promocode'], 'UTF-8') == 4))) {
            $this->userHelper->write('promocode', $_GET['promocode']);
        }

        if (!empty($this->request->query['ref'])) {
            User::setReferalCookie($this->request->query['ref']);
        }

        if ((!empty($this->request->query['sref'])) && User::isValidReferalCodeForSubscribers($this->request->query['sref'])) {
            User::setReferalForSubscriberCookie($this->request->query['sref']);
        }
        if ((!empty($this->request->query['sref2'])) && User::isValidReferalCodeForSubscribers($this->request->query['sref2'], 'subscriber2_referal_token')) {
            User::setReferalForSubscriberCookie($this->request->query['sref2'], 'sref2');
        }
        $this->discountForSubscriberReferal = 0;
        if (isset($_COOKIE['sref']) && User::isValidReferalCodeForSubscribers($_COOKIE['sref'])) {
            $this->discountForSubscriberReferal = 20;
        }
        if (isset($_COOKIE['sref2']) && User::isValidReferalCodeForSubscribers($_COOKIE['sref2'], 'subscriber2_referal_token')) {
            $this->discountForSubscriberReferal = 10;
        }
        if (isset($userRecord) && $this->userHelper->isLoggedIn() ) {
            $this->userRecord = $userRecord;
        }
    }

    /**
     * Метод возвращяет популярные ответы из faq
     *
     * @param int $limit
     * @return \lithium\data\collection\RecordSet|null
     */
    protected function popularQuestions($limit = 5)
    {
        return Answer::getPopularQuesions($limit);
    }
}
