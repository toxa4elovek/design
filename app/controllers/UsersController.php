<?php

namespace app\controllers;

use app\extensions\helper\Brief;
use app\extensions\helper\MoneyFormatter;
use app\extensions\helper\NumInflector;
use app\extensions\mailers\NotificationsMailer;
use app\extensions\smsfeedback\SmsFeedback;
use app\extensions\smsfeedback\SmsUslugi;
use app\extensions\social\TwitterAPI;
use app\models\Addon;
use app\models\Bill;
use app\models\Lead;
use app\models\Logreferal;
use app\models\Manager;
use app\models\Paymaster;
use app\models\Payment;
use app\models\SubscriptionPlan;
use app\models\TextMessage;
use app\models\Url;
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
use app\models\Receipt;
use \app\models\Tweet;
use \app\extensions\mailers\UserMailer;
use \app\extensions\mailers\SpamMailer;
use \app\extensions\mailers\ContactMailer;
use lithium\action\Response;
use \lithium\storage\Session;
use \lithium\security\Auth;
use \lithium\util\String;
use \lithium\analysis\Logger;
use app\extensions\storage\Rcache;
use \tmhOAuth\tmhOAuth;
use \Exception;
use \app\extensions\helper\Avatar as AvatarHelper;
use app\extensions\helper\NameInflector;

class UsersController extends \app\controllers\AppController
{

    /**
     * Методы, доступные без аутентификации
     *
     * @var array
     */
    public $publicActions = [
        'eventtest', 'vklogin', 'unsubscribe', 'registration', 'login', 'sale', /* 'info', 'sendmail', */ 'confirm', 'checkform', 'recover', 'setnewpassword', 'loginasadmin', 'view', 'updatetwitter', 'updatetwitterfeed', 'banned', 'activation', 'need_activation', 'requesthelp', 'testemail', 'feed', 'test'
    ];

    public $nominatedCount = false;

    public function _init()
    {
        parent::_init();
        $withMenu = [
            'office',
            'solutions',
            'awarded',
            'nominated',
            'step1',
            'step2',
            'step3',
            'step4',
            'mypitches'
        ];
        if (in_array($this->request->action, $withMenu)) {
            $myPitches = Pitch::all([
                        'conditions' => ['user_id' => Session::read('user.id')],
            ]);
            $pitchIds = [];
            foreach ($myPitches as $pitch) {
                $pitchIds[] = $pitch->id;
            }
            $solutionsFromMyPitches = [];
            if (!empty($pitchIds)) {
                $solutionsFromMyPitches = Solution::all(['conditions' => ['pitch_id' => $pitchIds, 'nominated' => '1']]);
            }

            $conditions = ['Solution.user_id' => Session::read('user.id'), 'nominated' => '1', 'status' => ['<' => 2]];
            $solutions = Solution::all(['conditions' => $conditions, 'with' => ['Pitch']]);

            if (count($solutionsFromMyPitches) > 0) {
                $solutions = array_merge($solutions->data(), $solutionsFromMyPitches->data());
            } else {
                $solutions = $solutions->data();
            }
            $this->nominatedCount = count($solutions);
        }
    }

    /**
     * Метод для загрузки нового аватара
     *
     * @return array
     */
    public function avatar()
    {
        $allowedExtensions = ['png', 'gif', 'jpeg', 'jpg'];
        $sizeLimit = 10 * 1024 * 1024;
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload(LITHIUM_APP_PATH . '/webroot/avatars/');
        if ($result['success']) {
            Avatar::removeAllAvatarsOfUser($this->userHelper->getId());
            $user = User::first($this->userHelper->getId());
            $user->set(['avatar' => ['name' => $result['name'], 'tmp_name' => $result['tmpname'], 'error' => 0]]);
            $user->save();
            $user = User::first($this->userHelper->getId());
            $cacheKey = 'avatars_' . $user->id;
            $cached = Rcache::read($cacheKey);
            unlink($result['tmpname']);
            return ['result' => 'true', 'data' => $user->data(), 'cached' => $cached, 'images' => $user->data()['images']];
        }
    }

    /**
     * Кабинет
     *
     */
    public function office()
    {
        return $this->redirect('Users::feed');
        $date = date('Y-m-d H:i:s');
        if ((Session::read('user.id' > 0)) && (Session::read('user.events') != null)) {
            $date = Session::read('user.events.date');
            Session::delete('user.events');
        }
        $gallery = Solution::getUserSolutionGallery(Session::read('user.id'));
        $winnersData = Solution::all(['conditions' => ['Solution.awarded' => 1, 'Pitch.private' => 0], 'order' => ['Solution.created' => 'desc'], 'limit' => 50, 'with' => ['Pitch']]);
        $winners = [];
        foreach ($winnersData as $winner) {
            if ($winner->pitch->category_id != 7) {
                $winners[] = $winner;
            }
        }
        $winners = [];
        $updates = Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), 1, null);
        $nextUpdates = count(Event::getEvents(User::getSubscribedPitches(Session::read('user.id')), 2, null));
        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('gallery', 'winners', 'date', 'updates', 'nextUpdates');
        } else {
            return $this->render(['layout' => false, 'data' => compact('gallery', 'winners', 'date', 'updates', 'nextUpdates')]);
        }
    }

    public function feed()
    {
        $date = date('Y-m-d H:i:s');
        if (!$this->userHelper->isLoggedIn()) {
            //error_reporting(E_ALL);
            //ini_set('display_errors', 1);
        }
        if ((Session::read('user')) && (Session::read('user.id' > 0)) && (Session::read('user.events') != null)) {
            $date = Session::read('user.events.date');
            Session::delete('user.events');
            $pitchIds = User::getSubscribedPitches(Session::read('user.id'));
        } else {
            $pitchIds = [];
        }
        $pitches = Pitch::all(['conditions' => ['status' => 0, 'published' => 1, 'multiwinner' => 0], 'order' => ['started' => 'desc'], 'limit' => 5]);
        $middlePost = false;
        $shareEvent = null;
        if ((isset($this->request->query['event'])) && (is_numeric($this->request->query['event']))) {
            $shareEvent = Event::first($this->request->query['event']);
        }
        $news = News::getNews();
        $solutions = Event::getEventSolutions(Session::read('user.id'));
        $tag = null;
        if (isset($this->request->query['tag'])) {
            $tag = $this->request->query['tag'];
        }
        $updates = Event::getEvents($pitchIds, 1, null, Session::read('user.id'), $tag);
        $nextUpdates = count(Event::getEvents($pitchIds, 2, null, Session::read('user.id'), $tag));
        $banner = News::getBanner();

        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            $accessToken = Event::getBingAccessToken();
            return compact('date', 'updates', 'nextUpdates', 'news', 'pitches', 'solutions', 'middlePost', 'banner', 'shareEvent', 'accessToken', 'tag');
        } else {
            return $this->render(['layout' => false, 'data' => compact('pitchIds', 'date', 'updates', 'nextUpdates', 'pitches')]);
        }
    }

    public function referal()
    {
        $user = User::first(Session::read('user.id'));
        if (empty($user->referal_token)) {
            $user->referal_token = User::generateReferalToken();
            $user->save(null, ['validate' => false]);
        }
        $refPitches = Pitch::all([
                    'conditions' => [
                        'user_id' => [
                            '!=' => 0,
                        ],
                        'referal' => $user->id
                    ],
                    'with' => ['User'],
        ]);
        $completePaymentCount = Logreferal::getCompletePaymentCount(Session::read('user.id'));
        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('user', 'refPitches', 'completePaymentCount');
        } else {
            return $this->render(['layout' => false, 'data' => compact('user', 'refPitches', 'completePaymentCount')]);
        }
    }

    /**
     * Метод показывает страницу реферальной программы 10000 рублей за абонента,
     * при необходимости, создает реферальный токен и сокращеннуюю ссылку
     */
    public function subscribers_referal()
    {
        if (empty($this->userRecord->subscriber_referal_token)) {
            $this->userRecord->subscriber_referal_token = User::generateSubscriberReferalToken();
            $this->userRecord->save(null, ['validate' => false]);
        }
        $fullUrl = 'https://godesigner.ru/pages/subscribe?sref=' . $this->userRecord->subscriber_referal_token;
        $shortUrl = 'https://godesigner.ru/urls/' . Url::getShortUrlFor($fullUrl);
        return compact('shortUrl');
    }

    public function deletePhone()
    {
        if ($this->request->is('json') && (Session::read('user.id') > 0) && ($user = User::first((int) Session::read('user.id')))) {
            $user->phone = 0;
            $user->phone_operator = 0;
            $user->phone_code = 0;
            $user->phone_valid = 0;
            $user->save(null, ['validate' => false]);
            $result = [
                'code' => true,
                'phone' => 0,
                'phone_valid' => 0,
            ];
            return $result;
        }
        $this->redirect('/');
    }

    /**
     * Метод для отображения страницы портфолио у дизайнера / страницы предложенных решений у заказчика
     *
     * @return array|void
     */
    public function solutions()
    {
        $conditions = [
            'Solution.user_id' => $this->userHelper->getId(),
            'Pitch.blank' => 0
        ];
        $unfilteredSolutions = Solution::all([
            'conditions' => $conditions,
            'with' => ['Pitch', 'Solutiontag'],
            'order' => ['Solution.id' => 'desc']
        ]);
        $nominatedCount = $this->nominatedCount;
        $myPitches = Pitch::all(['conditions' => [
            'user_id' => $this->userHelper->getId(),
            'published' => 1, 'billed' => 1]]);

        if (($this->userRecord->isClient || $this->userRecord->is_company) && ($myPitches->data())) {
            $idList = [];
            foreach ($myPitches as $pitch) {
                $idList[] = $pitch->id;
            }
            $unfilteredSolutions = Solution::all([
                'conditions' => ['pitch_id' => $idList],
                'order' => ['Solution.id' => 'desc'],
                'with' => ['Pitch', 'Solutiontag']
            ]);
        }
        $multiWinnerOriginals = [];
        foreach ($unfilteredSolutions as $solution) {
            if (($solution->multiwinner != 0) && ($solution->awarded != 0)) {
                $multiWinnerOriginals[] = $solution->multiwinner;
            }
        }
        $filteredSolutions = [];
        foreach ($unfilteredSolutions as $solution) {
            if (($solution->pitch->multiwinner != 0) && ($solution->pitch->awarded != $solution->id)) {
                continue;
            }
            if (($solution->pitch->multiwinner != 0) && ($solution->pitch->billed == 0)) {
                continue;
            }
            if (!in_array($solution->id, $multiWinnerOriginals)) {
                $solution->tags = Solution::getTagsArrayForSolution($solution);
                $filteredSolutions[] = $solution;
            }
        }
        $solutions = $filteredSolutions;
        $filterType = 'solutions';
        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('solutions', 'filterType', 'nominatedCount');
        } else {
            return $this->render(['layout' => false, 'data' => compact('solutions', 'filterType', 'nominatedCount')]);
        }
    }

    public function awarded()
    {
        $myPitches = Pitch::all([
                    'conditions' => ['user_id' => Session::read('user.id')],
        ]);
        $pitchIds = [];
        foreach ($myPitches as $pitch) {
            $pitchIds[] = $pitch->id;
        }
        $solutionsFromMyPitches = [];
        if (!empty($pitchIds)) {
            $solutionsFromMyPitches = Solution::all(['conditions' => ['pitch_id' => $pitchIds, 'Solution.awarded' => '1'], 'with' => ['Pitch']]);
        }

        $conditions = ['Solution.user_id' => Session::read('user.id'), 'Solution.awarded' => 1, 'Solution.nominated' => 0];
        $solutions = Solution::all(['conditions' => $conditions, 'with' => ['Pitch']]);

        if (count($solutionsFromMyPitches) > 0) {
            $solutions = array_merge($solutions->data(), $solutionsFromMyPitches->data());
        } else {
            $solutions = $solutions->data();
        }

        $filterType = 'awarded';

        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('solutions', 'filterType');
        } else {
            return $this->render(['layout' => false, 'data' => compact('solutions', 'filterType')]);
        }
    }

    public function nominated()
    {
        $myPitches = Pitch::all([
                    'conditions' => ['user_id' => Session::read('user.id')],
        ]);
        $pitchIds = [];
        foreach ($myPitches as $pitch) {
            $pitchIds[] = $pitch->id;
        }
        $solutionsFromMyPitches = [];
        if (!empty($pitchIds)) {
            $solutionsFromMyPitches = Solution::all(['conditions' => ['pitch_id' => $pitchIds, 'nominated' => '1', 'Solution.awarded' => '0'], 'with' => ['Pitch']]);
        }

        $conditions = ['Solution.user_id' => Session::read('user.id'), 'Solution.nominated' => '1'];
        $solutions = Solution::all(['conditions' => $conditions, 'with' => ['Pitch']]);

        if (count($solutionsFromMyPitches) > 0) {
            $solutions = array_merge($solutions->data(), $solutionsFromMyPitches->data());
        } else {
            $solutions = $solutions->data();
        }
        $filterType = 'nominated';
        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('solutions', 'filterType');
        } else {
            return $this->render(['layout' => false, 'data' => compact('solutions', 'filterType')]);
        }
    }

    public function step1()
    {
        if (($solution = Solution::first(['conditions' => ['Solution.id' => $this->request->id], 'with' => ['Pitch', 'User']])) && ($solution->nominated == 1 || $solution->awarded == 1)) {
            if ((Session::read('user.id') != $solution->user_id) && (Session::read('user.isAdmin') != 1) && (!User::checkRole('admin')) && (Session::read('user.id') != $solution->pitch->user_id)) {
                return $this->redirect('Users::feed');
            }
            if (Session::read('user.id') == $solution->pitch->user_id) {
                return $this->redirect(['controller' => 'users', 'action' => 'step2', 'id' => $solution->id]);
            }
            $solution->pitch->category = Category::first($solution->pitch->category_id);
            if ($solution->user_id == $this->userHelper->getId()) {
                $type = 'designer';
            } else {
                $type = 'client';
            }
            if ((Session::read('user.isAdmin') == 1) || User::checkRole('admin')) {
                $type = 'admin';
            }
            /** @TODO - remove */
            if ($this->userHelper->getId() == 32) {
                $type = 'client';
            }
            $step = 1;
            $user = User::first($this->userHelper->getId());
            return compact('type', 'solution', 'step', 'user');
        }
    }

    public function step2()
    {
        \lithium\net\http\Media::type('json', ['text/html']);
        if (($solution = Solution::first([
            'conditions' => ['Solution.id' => $this->request->id],
                'with' => ['Pitch', 'User']]))
            && ($solution->nominated == 1 || $solution->awarded == 1)) {
            $canManageClosing = false;
            if (((int) $solution->pitch->category_id === 20)
                && (Manager::getTeamLeaderOfManager($this->userHelper->getId()) === (int) $solution->pitch->user_id)
                && (Manager::isManagerAssignedToProject((int) $this->userHelper->getId(), (int) $solution->pitch->id))) {
                $canManageClosing = true;
            }

            if ((!$this->userHelper->isSolutionAuthor($solution->user_id))
                && (!$this->userHelper->isAdmin())
                && (!$this->userHelper->isPitchOwner($solution->pitch->user_id))
                && (!$canManageClosing)) {
                return $this->redirect('Users::feed');
            }
            $solution->pitch->category = Category::first($solution->pitch->category_id);
            if ($this->userHelper->isSolutionAuthor($solution->user_id)) {
                $type = 'designer';
                $designer = User::first($this->userHelper->getId());
                if (($designer->phone_valid != 1) || ($designer->phone == '')) {
                    return $this->redirect(['controller' => 'users', 'action' => 'step1', 'id' => $this->request->id]);
                }
                $messageTo = $client = User::first($solution->pitch->user_id);
            } else {
                $type = 'client';
                $messageTo = $designer = User::first($solution->user_id);
            }
            if ($this->userHelper->isAdmin()) {
                $type = 'admin';
                $messageTo = User::first($solution->pitch->user_id);
            }
            if (!isset($client)) {
                $client = User::first($solution->pitch->user_id);
            }
            if (!isset($designer)) {
                $designer = User::first($solution->user_id);
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
                if ($this->userHelper->isAdmin()) {
                    $userIdForComment = 108;
                } else {
                    $userIdForComment = $this->userHelper->getId();
                }
                $newComment->set($this->request->data);
                $newComment->text = nl2br($this->request->data['text']);
                $newComment->user_id = $userIdForComment;
                $newComment->solution_id = $solution->id;
                $newComment->created = date('Y-m-d H:i:s');
                $newComment->step = 2;
                $newComment->save();
                if ($type === 'designer') {
                    $recipient = User::first($solution->pitch->user_id);
                    User::sendSpamWincomment($newComment, $recipient);
                } elseif ($type === 'client') {
                    $recipient = User::first($solution->user_id);
                    User::sendSpamWincomment($newComment, $recipient);
                } else {
                    $recipient = User::first($solution->pitch->user_id);
                    User::sendSpamWincomment($newComment, $recipient);
                    $recipient = User::first($solution->user_id);
                    User::sendSpamWincomment($newComment, $recipient);
                }
                if (preg_match('/@GoDesigner/', $newComment->text)) {
                    $admin = User::first(5);
                    User::sendSpamWincomment($newComment, $admin, true);
                }
                $user = User::first($this->userHelper->getId());
                $avatarHelper = new AvatarHelper;
                $userAvatar = $avatarHelper->show($user->data(), false, true);
                $comment = Wincomment::first(['conditions' => ['Wincomment.id' => $newComment->id], 'with' => ['User', 'Solution']]);
                $comment = $comment->data();
                $brief = new Brief();
                $comment['text'] = html_entity_decode($brief->deleteHtmlTagsAndInsertHtmlLinkInTextAndMentions($comment['text']));
                $comment['originalText'] = strip_tags($comment['originalText'], '<a>');
                $comment['originalText'] = htmlentities($comment['originalText'], ENT_COMPAT, 'utf-8');
                return json_encode(compact('newComment', 'comment', 'userAvatar'));
            }
            $files = [];
            $commentCount = Wincomment::count(
                [
                    'fields' => ['id'],
                    'conditions' =>
                        ['step' => 2, 'solution_id' => $solution->id],
                    'order' => ['created' => 'desc'],
                    'with' => ['User']]
            );
            if (0 == $commentCount) {
                $timelimit = $solution->pitch->category->default_timelimit;
                if ($solution->pitch->category_id == 20) {
                    $diff = ceil((strtotime($solution->pitch->finishDate) - strtotime($solution->pitch->started)) / DAY);
                    $timelimit = $diff;
                    if ($timelimit < 5) {
                        $timelimit = 5;
                    }
                }
                $text = sprintf('Вся переписка до запроса исходников должна быть проведена в рамках этого кабинета. Мы не допускаем обмена контактами до момента одобрения исходников, т.о. в спорной ситуации мы сможем разрешить конфликт. Мы убедительно просим вас соблюдать правила платформы. Срок завершительного этапа %d дней. Предупреждайте, если правки или комментарии займут более 24 часов.', $timelimit);
                $date = new \DateTime();
                $dateString = $date->format('Y-m-d H:i:s');
                $data = [
                    'user_id' => 108,
                    'text' => $text,
                    'step' => 2,
                    'solution_id' => $solution->id,
                    'created' => $dateString,
                    'touch' => '0000-00-00 00:00:00'
                ];
                $comment = Wincomment::create($data);
                $comment->save();
                User::sendSpamWincomment($comment, $client);
                if (($solution->pitch->isCopyrighting()) && (!User::isSubscriptionActive($client->id, $client))) {
                    $nameInflector = new NameInflector();
                    $ownerFormatted = $nameInflector->renderName($client->first_name, $client->last_name);
                    $text = '<a href="#" class="mention-link" data-comment-to="' . $ownerFormatted . '">@' . $ownerFormatted . ',</a> Нам понравилось работать с вами, и мы хотим продолжить наше партнерство. Сотрудничайте с&nbsp;дизайнерами и&nbsp;копирайтерами без рисков дальше, корректируйте макеты без сервисных сборов, создавайте проекты от 500р. в&nbsp;течение года, став нашим абонентом. В течение недели <a href="/pages/subscribe?utm_source=GDsite&utm_medium=final_stage_comment&utm_campaign=off10percent" target="_blank">мы предлагаем вам скидку 10%</a> на <a href="/pages/subscribe?utm_source=GDsite&utm_medium=final_stage_comment&utm_campaign=off10percent" target="_blank">годовое обслуживание</a>.';
                    $data = [
                        'user_id' => 108,
                        'text' => $text,
                        'step' => 2,
                        'solution_id' => $solution->id,
                        'created' => date('Y-m-d H:i:s'),
                        'touch' => '0000-00-00 00:00:00'
                    ];
                    $comment = Wincomment::create($data);
                    $comment->save();
                    User::sendSpamWincomment($comment, $client);
                    if (!$client->hasActiveSubscriptionDiscountForRecord($client)) {
                        User::setSubscriptionDiscount($client->id, 10, date('Y-m-d H:i:s', time() + (DAY * 7)));
                        Lead::resetLeadForUser($client->id);
                        if (!SubscriptionPlan::hasSubscriptionPlanDraft($client->id)) {
                            $plan = SubscriptionPlan::getPlan(1);
                            $paymentId = SubscriptionPlan::getNextSubscriptionPlanId($this->userHelper->getId());
                            $receipt = [
                                [
                                    'name' => 'Оплата тарифа «' . $plan['title'] . '»',
                                    'value' => $plan['price']
                                ],
                                [
                                    'name' => 'Пополнение счёта',
                                    'value' => 0
                                ]
                            ];
                            $discount = 10;
                            $discountValue = -1 * ($plan['price'] - $this->money->applyDiscount($plan['price'], $discount));
                            $receipt = Receipt::addRow($receipt, "Скидка — $discount%", $discountValue);
                            Receipt::updateOrCreateReceiptForProject($paymentId, $receipt);
                            SubscriptionPlan::setTotalOfPayment($paymentId, Receipt::getTotalForProject($paymentId));
                            SubscriptionPlan::setPlanForPayment($paymentId, $plan['id']);
                            SubscriptionPlan::setFundBalanceForPayment($paymentId, 0);
                        }
                    }
                }
            }

            $comments = Wincomment::all(['conditions' => ['step' => 2, 'solution_id' => $solution->id], 'order' => ['created' => 'desc'], 'with' => ['User']]);
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
            $nofiles = false;
            if (empty($files)) {
                $nofiles = true;
            }
            $nameInflector = new NameInflector();
            $avatarHelper = new AvatarHelper();
            $autosuggestUsers = [];
            $adminGo = User::first(108);
            $autosuggestUsers[] = [
                'id' => $adminGo->id,
                'avatar' => $avatarHelper->show($adminGo->data(), false, true),
                'name' => $nameInflector->renderName($adminGo->first_name, $adminGo->last_name, false),
            ];
            $autosuggestUsers[] = [
                'id' => $designer->id,
                'avatar' => $avatarHelper->show($designer->data(), false, true),
                'name' => $nameInflector->renderName($designer->first_name, $designer->last_name, false),
            ];
            $client->visualName = $this->userHelper->getFormattedName($client->first_name, $client->last_name, false);
            if (($client->is_company == 1) && ($client->short_company_name != '')) {
                $client->visualName = $client->short_company_name;
            }
            $autosuggestUsers[] = [
                'id' => $client->id,
                'avatar' => $avatarHelper->show($client->data(), false, true),
                'name' => $client->visualName
            ];
            return compact('type', 'solution', 'comments', 'step', 'nofiles', 'messageTo', 'autosuggestUsers');
        }
    }

    public function step3()
    {
        \lithium\net\http\Media::type('json', ['text/html']);
        if (($solution = Solution::first(['conditions' => ['Solution.id' => $this->request->id], 'with' => ['Pitch', 'User']])) && ($solution->nominated == 1 || $solution->awarded == 1)) {
            if (($this->request->params['confirm']) && ($this->request->params['confirm'] == 'confirm') &&
                    ($this->userHelper->isAdmin() || ($this->userHelper->isPitchOwner($solution->pitch->user_id))) && ($solution->step < 3)) {
                $user = User::first($solution->user_id);
                $client = User::first($solution->pitch->user_id);
                if (!User::isSubscriptionActive($client->id, $client)) {
                    $nameInflector = new NameInflector();
                    $ownerFormatted = $nameInflector->renderName($client->first_name, $client->last_name);
                    $text = '<a href="#" class="mention-link" data-comment-to="' . $ownerFormatted . '">@' . $ownerFormatted . ',</a> Нам понравилось работать с вами, и мы хотим продолжить наше партнерство. Сотрудничайте с&nbsp;дизайнерами и&nbsp;копирайтерами без рисков дальше, корректируйте макеты без сервисных сборов, создавайте проекты от 500р. в&nbsp;течение года, став нашим абонентом. В течение недели <a href="/pages/subscribe?utm_source=GDsite&utm_medium=final_stage_comment&utm_campaign=off10percent" target="_blank">мы предлагаем вам скидку 10%</a> на <a href="/pages/subscribe?utm_source=GDsite&utm_medium=final_stage_comment&utm_campaign=off10percent" target="_blank">годовое обслуживание</a>.';
                    $data = [
                        'user_id' => 108,
                        'text' => $text,
                        'step' => 3,
                        'solution_id' => $solution->id,
                        'created' => date('Y-m-d H:i:s'),
                        'touch' => '0000-00-00 00:00:00'
                    ];
                    $comment = Wincomment::create($data);
                    $comment->save();
                    User::sendSpamWincomment($comment, $client);
                    if (!$client->hasActiveSubscriptionDiscountForRecord($client)) {
                        User::setSubscriptionDiscount($client->id, 10, date('Y-m-d H:i:s', time() + (DAY * 7)));
                        Lead::resetLeadForUser($client->id);
                        if (!SubscriptionPlan::hasSubscriptionPlanDraft($client->id)) {
                            $plan = SubscriptionPlan::getPlan(1);
                            $paymentId = SubscriptionPlan::getNextSubscriptionPlanId($this->userHelper->getId());
                            $receipt = [
                                [
                                    'name' => 'Оплата тарифа «' . $plan['title'] . '»',
                                    'value' => $plan['price']
                                ],
                                [
                                    'name' => 'Пополнение счёта',
                                    'value' => 0
                                ]
                            ];
                            $discount = 10;
                            $discountValue = -1 * ($plan['price'] - $this->money->applyDiscount($plan['price'], $discount));
                            $receipt = Receipt::addRow($receipt, "Скидка — $discount%", $discountValue);
                            Receipt::updateOrCreateReceiptForProject($paymentId, $receipt);
                            SubscriptionPlan::setTotalOfPayment($paymentId, Receipt::getTotalForProject($paymentId));
                            SubscriptionPlan::setPlanForPayment($paymentId, $plan['id']);
                            SubscriptionPlan::setFundBalanceForPayment($paymentId, 0);
                        }
                    }
                }
                User::sendSpamWinstep($user, $solution, '3');
                $solution->step = 3;
                $solution->save();
                return $this->redirect(['controller' => 'users', 'action' => 'step3', 'id' => $solution->id]);
            }

            $canManageClosing = false;
            if (((int) $solution->pitch->category_id === 20)
                && (Manager::getTeamLeaderOfManager($this->userHelper->getId()) === (int) $solution->pitch->user_id)
                && (Manager::isManagerAssignedToProject((int) $this->userHelper->getId(), (int) $solution->pitch->id))) {
                $canManageClosing = true;
            }

            if ((!$this->userHelper->isSolutionAuthor($solution->user_id))
                && (!$this->userHelper->isAdmin())
                && (!$this->userHelper->isPitchOwner($solution->pitch->user_id))
                && (!$canManageClosing)) {
                return $this->redirect('Users::feed');
            }

            if ($solution->step < 3) {
                return $this->redirect(['controller' => 'users', 'action' => 'step2', 'id' => $this->request->id]);
            }
            if (($solution->pitch->category_id == 7) && ($solution->step == 4)) {
                return $this->redirect('/users/step4/' . $solution->id);
            }
            $solution->pitch->category = Category::first($solution->pitch->category_id);
            if ($solution->user_id == Session::read('user.id')) {
                $type = 'designer';
                $designer = User::first($this->userHelper->getId());
                if (($designer->phone_valid != 1) || ($designer->phone == '')) {
                    return $this->redirect(['controller' => 'users', 'action' => 'step1', 'id' => $this->request->id]);
                }
                $messageTo = $client = User::first($solution->pitch->user_id);
            } else {
                $type = 'client';
                $messageTo = $designer = User::first($solution->user_id);
            }
            if ((Session::read('user.isAdmin') == 1) || User::checkRole('admin')) {
                $type = 'admin';
                $messageTo = User::first($solution->pitch->user_id);
            }
            if (!isset($client)) {
                $client = User::first($solution->pitch->user_id);
            }
            if (!isset($designer)) {
                $designer = User::first($solution->user_id);
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
                if ($this->userHelper->isAdmin()) {
                    $userIdForComment = 108;
                } else {
                    $userIdForComment = $this->userHelper->getId();
                }
                $newComment->set($this->request->data);
                $newComment->text = nl2br($this->request->data['text']);
                $newComment->user_id = $userIdForComment;
                $newComment->solution_id = $solution->id;
                $newComment->created = date('Y-m-d H:i:s');
                $newComment->step = 3;
                $newComment->save();
                if ($type === 'designer') {
                    $recipient = User::first($solution->pitch->user_id);
                    User::sendSpamWincomment($newComment, $recipient);
                } elseif ($type === 'client') {
                    $recipient = User::first($solution->user_id);
                    User::sendSpamWincomment($newComment, $recipient);
                } else {
                    $recipient = User::first($solution->pitch->user_id);
                    User::sendSpamWincomment($newComment, $recipient);
                    $recipient = User::first($solution->user_id);
                    User::sendSpamWincomment($newComment, $recipient);
                }
                if (preg_match('/@GoDesigner/', $newComment->text)) {
                    $admin = User::first(5);
                    User::sendSpamWincomment($newComment, $admin, true);
                }
                $user = User::first(Session::read('user.id'));
                $avatarHelper = new AvatarHelper;
                $userAvatar = $avatarHelper->show($user->data(), false, true);
                $comment = Wincomment::first(['conditions' => ['Wincomment.id' => $newComment->id], 'with' => ['User', 'Solution']]);
                $comment = $comment->data();
                $brief = new Brief();
                $comment['text'] = html_entity_decode($brief->deleteHtmlTagsAndInsertHtmlLinkInTextAndMentions($comment['text']));
                $comment['originalText'] = strip_tags($comment['originalText'], '<a>');
                $comment['originalText'] = htmlentities($comment['originalText'], ENT_COMPAT, 'utf-8');
                return json_encode(compact('newComment', 'comment', 'userAvatar'));
            }
            $comments = Wincomment::all(['conditions' => ['step' => 3, 'solution_id' => $solution->id], 'order' => ['created' => 'desc'], 'with' => ['User']]);
            $files = [];
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
            $nameInflector = new NameInflector();
            $avatarHelper = new AvatarHelper();
            $autosuggestUsers = [];
            $adminGo = User::first(108);
            $autosuggestUsers[] = [
                'id' => $adminGo->id,
                'avatar' => $avatarHelper->show($adminGo->data(), false, true),
                'name' => $nameInflector->renderName($adminGo->first_name, $adminGo->last_name, false),
            ];
            $autosuggestUsers[] = [
                'id' => $designer->id,
                'avatar' => $avatarHelper->show($designer->data(), false, true),
                'name' => $nameInflector->renderName($designer->first_name, $designer->last_name, false),
            ];
            $client->visualName = $this->userHelper->getFormattedName($client->first_name, $client->last_name, false);
            if (($client->is_company == 1) && ($client->short_company_name != '')) {
                $client->visualName = $client->short_company_name;
            }
            $autosuggestUsers[] = [
                'id' => $client->id,
                'avatar' => $avatarHelper->show($client->data(), false, true),
                'name' => $client->visualName,
            ];
            return compact('type', 'solution', 'comments', 'step', 'nofiles', 'messageTo', 'autosuggestUsers');
        }
    }

    public function step4()
    {
        if (($solution = Solution::first(['conditions' => ['Solution.id' => $this->request->id], 'with' => ['Pitch', 'User']]))) {
            if (($this->request->params['confirm']) && ($this->request->params['confirm'] == 'confirm') &&
                    (($this->userHelper->isPitchOwner($solution->pitch->user_id)) || $this->userHelper->isAdmin()) && ($solution->step == 3)) {
                $user = User::first($solution->user_id);
                if ($this->userHelper->isAdmin()) {
                    User::sendSpamWinstepGo($user, $solution, '4');
                } else {
                    User::sendSpamWinstep($user, $solution, '4');
                }
                $solution->step = 4;
                $solution->save();
                NotificationsMailer::sendProjectFinishedNotifications($solution->pitch, 'm.elenevskaya@godesigner.ru');
                NotificationsMailer::sendProjectFinishedNotifications($solution->pitch, 'va@godesigner.ru');
                Pitch::finishPitch($solution->pitch_id);
                return $this->redirect(['controller' => 'users', 'action' => 'step4', 'id' => $solution->id]);
            }

            if (($this->request->params['confirm']) && ($this->request->params['confirm'] == 'confirm') &&
                    ($this->userHelper->isPitchOwner($solution->pitch->user_id) || $this->userHelper->isAdmin()) && ($solution->pitch->category_id == 7)) {
                $user = User::first($solution->user_id);
                if ($this->userHelper->isAdmin()) {
                    User::sendSpamWinstepGo($user, $solution, '4');
                } else {
                    User::sendSpamWinstep($user, $solution, '4');
                }
                $solution->step = 4;
                $solution->save();
                Pitch::finishPitch($solution->pitch_id);
                return $this->redirect(['controller' => 'users', 'action' => 'step4', 'id' => $solution->id]);
            }

            $canManageClosing = false;
            if (((int) $solution->pitch->category_id === 20)
                && (Manager::getTeamLeaderOfManager($this->userHelper->getId()) === (int) $solution->pitch->user_id)
                && (Manager::isManagerAssignedToProject((int) $this->userHelper->getId(), (int) $solution->pitch->id))) {
                $canManageClosing = true;
            }

            if ((!$this->userHelper->isSolutionAuthor($solution->user_id))
                && (!$this->userHelper->isAdmin())
                && (!$this->userHelper->isPitchOwner($solution->pitch->user_id))
                && (!$canManageClosing)) {
                return $this->redirect('Users::feed');
            }

            if ($solution->step < 4) {
                return $this->redirect(['controller' => 'users', 'action' => 'step3', 'id' => $this->request->id]);
            }
            $solution->pitch->category = Category::first($solution->pitch->category_id);
            if ($this->userHelper->isSolutionAuthor($solution->user_id)) {
                $type = 'designer';
                $gradeByOtherParty = Grade::first(['conditions' => ['user_id' => $solution->pitch->user_id, 'pitch_id' => $solution->pitch->id]]);
            } else {
                $type = 'client';
                $gradeByOtherParty = Grade::first(['conditions' => ['user_id' => $solution->user_id, 'pitch_id' => $solution->pitch->id]]);
            }
            if ($this->userHelper->isAdmin()) {
                $type = 'admin';
            }
            $grade = Grade::first(['conditions' => ['user_id' => $this->userHelper->getId(), 'pitch_id' => $solution->pitch->id, 'type' => $type]]);
            if ($this->request->data) {
                $grade = Grade::create();
                $grade->set($this->request->data);
                $grade->pitch_id = $solution->pitch->id;
                $grade->user_id = $this->userHelper->getId();
                $grade->type = $type;
                $grade->save();
                if ($gradeByOtherParty) {
                    $solution->nominated = 0;
                    $solution->awarded = 1;
                    $solution->save();
                }
                if($type === 'client') {
                    $client = User::first($solution->pitch->user_id);
                    UserMailer::sendEmailAfterGrade($grade, $client);
                }
            }

            $step = 4;
            return compact('type', 'solution', 'comments', 'step', 'grade');
        }
    }

    public function mypitches()
    {
        if (!is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return $this->render(['layout' => false]);
        } else {
            $categories = Category::all();
            if (isset($this->request->query['category'])) {
                $selectedCategory = $this->request->query['category'];
            }
            return compact('categories', 'selectedCategory');
        }
    }

    public function viewmail()
    {
        $mail = Sendemail::findByHash($this->request->id);
        if (Session::read('user.email') == $mail->email) {
            echo $mail->text;
            die();
        } else {
            return $this->redirect('Users::feed');
        }
    }

    public function unsubscribe()
    {
        if (($this->request->query) && (isset($this->request->query['token'])) && (isset($this->request->query['from']))) {
            $email = base64_decode($this->request->query['from']);
            if ($user = User::first(['conditions' => ['email' => $email]])) {
                if (sha1($user->id . $user->created) == base64_decode($this->request->query['token'])) {
                    $user->email_newpitch = 0;
                    $user->email_newcomments = 0;
                    $user->email_newpitchonce = 0;
                    $user->email_newsolonce = 0;
                    $user->email_newsol = 0;
                    $user->email_digest = 0;
                    $user->save(null, ['validate' => false]);
                    Auth::set('user', $user->data());
                    return $this->redirect('/users/profile');
                }
            }
        }
        return $this->render(['layout' => 'default', 'data' => false]);
    }

    /**
     * Метод регистрации
     *
     *
     * @return array|\lithium\action\Returns|object
     */
    public function registration()
    {
        //error_reporting(E_ALL);
        //ini_set('display_errors', 1);
        if ($this->userHelper->isLoggedIn()) {
            return $this->redirect('/');
        }
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
                    $userToLog = User::first(['conditions' => ['email' => $this->request->data['email']]]);
                    if (isset($this->request->data['service'])) {
                        $userToLog->vkontakte_uid = $this->request->data['uid'];
                    } else {
                        $userToLog->facebook_uid = $this->request->data['id'];
                        if (!Avatar::count(['conditions' => ['model_id' => $userToLog->id]])) {
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
                                $userToLog = User::first(['conditions' => ['facebook_uid' => $this->request->data['facebook_uid']]]);
                            } else {
                                $userToLog = User::first(['conditions' => ['vkontakte_uid' => $this->request->data['uid']]]);
                            }
                            $userToLog->setLastActionTime();
                            if ($fb) {
                                $userToLog->getFbAvatar();
                            } else {
                                $userToLog->vk_image_link = Session::read('vk_data.image_link');
                                Session::delete('vk_data');
                                $userToLog->getVkAvatar();
                            }
                            UserMailer::hi_mail($userToLog);
                            $newuser = true;
                            if (isset($_COOKIE['fastpitch'])) {
                                $fastId = unserialize($_COOKIE['fastpitch']);
                                $fastPitches = Pitch::all(['conditions' => ['id' => $fastId]]);
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
                            $userToLog = User::first(['conditions' => ['facebook_uid' => $this->request->data['facebook_uid']]]);
                        } else {
                            $userToLog = User::first(['conditions' => ['vkontakte_uid' => $this->request->data['uid']]]);
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
                            $fastPitches = Pitch::all(['conditions' => ['id' => $fastId]]);
                            foreach ($fastPitches as $fastPitch) {
                                $fastPitch->user_id = $userToLog->id;
                            }
                            $fastPitches->save();
                        }
                    }
                    if (isset($vk) && ($vk)) {
                        if (($userToLog->isUserRecordMemberOfVKGroup()) && (!User::isSubscriptionActive($userToLog->id, $userToLog)) && (!User::hasActiveSubscriptionDiscount($userToLog->id))) {
                            if (!SubscriptionPlan::hasSubscriptionPlanDraft($userToLog->id)) {
                                $plan = SubscriptionPlan::getPlan(1);
                                $paymentId = SubscriptionPlan::getNextSubscriptionPlanId($userToLog->id);
                                $receipt = [
                                    [
                                        'name' => 'Оплата тарифа «' . $plan['title'] . '»',
                                        'value' => $plan['price']
                                    ],
                                    [
                                        'name' => 'Пополнение счёта',
                                        'value' => 0
                                    ]
                                ];
                                $discount = 10;
                                $discountValue = -1 * ($plan['price'] - $this->money->applyDiscount($plan['price'], $discount));
                                $receipt = Receipt::addRow($receipt, "Скидка — $discount%", $discountValue);
                                Receipt::updateOrCreateReceiptForProject($paymentId, $receipt);
                                SubscriptionPlan::setTotalOfPayment($paymentId, Receipt::getTotalForProject($paymentId));
                                SubscriptionPlan::setPlanForPayment($paymentId, $plan['id']);
                                SubscriptionPlan::setFundBalanceForPayment($paymentId, 0);
                            }
                            User::setSubscriptionDiscount($userToLog->id, 10, date('Y-m-d H:i:s', time() + (MONTH)));
                            Lead::resetLeadForUser($userToLog->id);
                            $userToLog->subscription_discount = 10;
                            $userToLog->subscription_discount_end_date = User::getSubscriptionDiscountEndTime($userToLog->id);
                        }
                    }
                }
                if ($userToLog->banned) {
                    Auth::clear('user');
                    return ['data' => true, 'redirect' => '/users/banned'];
                }
                if ($userToLog->active == 0) {
                    Auth::clear('user');
                    return ['data' => true, 'redirect' => '/users/login'];
                }
                $userToLog->autologin_token = $userToLog->generateToken();
                if (isset($this->request->data['accessToken'])) {
                    $userToLog->accessToken = $this->request->data['accessToken'];
                }
                setcookie('autologindata', 'id=' . $userToLog->id . '&token=' . sha1($userToLog->autologin_token), time() + strtotime('+1 month'), '/');
                $userToLog->lastTimeOnline = date('Y-m-d H:i:s');
                $userToLog->save(null, ['validate' => false]);
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
                    if (!$redirect) {
                        $redirect = '/users/feed';
                    }
                    $this->redirect($redirect);
                }
                return ['data' => true, 'redirect' => $redirect, 'newuser' => $newuser];
            } else {
                // обычная регистрация
                if (!isset($this->request->data['case']) || $this->request->data['case'] != 'fu27fwkospf' || !$this->request->is('json')) { // Check for bots
                    return $this->redirect('/');
                }
                $user->token = $user->generateToken();
                $user->created = date('Y-m-d H:i:s');

                if ((!is_null(Session::read('redirect'))) && (Session::read('redirect') != '/users/logout')) {
                    $redirect = Session::read('redirect');
                } else {
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
                    $userToLog = User::first(['conditions' => ['id' => $user->id]]);
                    $userToLog->lastTimeOnline = date('Y-m-d H:i:s');
                    $userToLog->setLastActionTime();
                    // Отправляем письмо для верификации почты
                    if ($user->isClient) {
                        $posts = Post::all(['order' => ['id' => 'desc'], 'limit' => 2]);
                        UserMailer::verification_mail_client($userToLog, $posts);
                    } else {
                        UserMailer::verification_mail($userToLog);
                    }
                    // производим аутентификацию
                    Auth::set('user', $userToLog->data());

                    $pitchId = Session::read('temppitch');

                    if (isset($_COOKIE['fastpitch'])) {
                        $fastId = unserialize($_COOKIE['fastpitch']);
                        $fastPitches = Pitch::all(['conditions' => ['id' => $fastId]]);
                        foreach ($fastPitches as $fastPitch) {
                            $fastPitch->user_id = $user->id;
                        }
                        $fastPitches->save();
                        setcookie('fastpitch', null, -1, '/');
                    }

                    if (!is_null($pitchId)) {
                        if ($pitch = Pitch::first($pitchId)) {
                            $pitch->user_id = $userToLog->id;
                            $pitch->save();
                        }
                        Session::delete('temppitch');
                        return ['redirect' => '/pitches/edit/' . $pitchId . '#step3', 'who_am_i' => 'client'];
                    }
                    $data = $userToLog->data();
                    $session = Session::read();
                    $who_am_i = $this->request->data['who_am_i'];
                    return compact('data', 'session', 'redirect', 'who_am_i');
                } else {
                    $error = 'email_taken';
                    return compact('error');
                }
            }
        }
        $url = 'http://oauth.vk.com/authorize';

        $client_id = '2950889'; // ID приложения
        $client_secret = 'j1bFzKfXP4lIa0wA7vaV'; // Защищённый ключ
        $redirect_uri = 'https://www.godesigner.ru/users/vklogin'; // Адрес сайта


        $params = [
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'scope' => 'friends',
            'response_type' => 'code'
        ];
        $freePitch = Pitch::getFreePitch();
        return compact('user', 'invite', 'params', 'url', 'freePitch');
    }

    public function setStatus()
    {
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
            $user->save(null, ['validate' => false]);
            return ['result' => true, 'redirect' => $redirect, 'status' => $status];
        }

        return ['result' => false, 'error' => 'no user', 'redirect' => '/'];
    }

    /**
     *  Метод входа, устанавлививет сессию и делает редирект в рабочий кабинет
     *
     *
     * @return \lithium\action\Returns|object*
     */
    public function login()
    {
        $user = User::create();
        if ($this->request->data) {
            $this->request->data['password'] = String::hash($this->request->data['password']);
            if (Auth::check('user', $this->request, ['checkSession' => true])) {
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
                    return ['data' => true, 'redirect' => '/users/login'];
                }
                $userToLog->lastTimeOnline = date('Y-m-d H:i:s');
                if ((isset($this->request->data['remember'])) && ($this->request->data['remember'] == 'on')) {
                    $userToLog->autologin_token = $userToLog->generateToken();
                    setcookie('autologindata', 'id=' . $userToLog->id . '&token=' . sha1($userToLog->autologin_token), time() + strtotime('+1 month'), '/');
                }
                $userToLog->save(null, ['validate' => false]);
                /// FastPitches
                if (isset($_COOKIE['fastpitch'])) {
                    $fastId = unserialize($_COOKIE['fastpitch']);
                    $fastPitches = Pitch::all(['conditions' => ['id' => $fastId]]);
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

    public function banned()
    {
        $shortTerm = false;
        if ($this->request->query['temp']) {
            $shortTerm = true;
        }
        return compact('shortTerm');
    }

    public function need_activation()
    {
    }

    public function resend()
    {
        $userid = Session::read('user.id');
        UserMailer::verification_mail(User::setUserToken($userid));
        return true;
    }

    /**
     *  Метод выхода, удаляем сессию и делаем редирект на главную страницу
     *
     *
     * */
    public function logout()
    {
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
    public function confirm()
    {
        if (isset($this->request->query['token'])) {
            $user = User::first(['conditions' => ['token' => $this->request->query['token']]]);
            if ($user) {
                $user->activateUser();
                UserMailer::hi_mail($user);
                Auth::clear('user');
                Auth::set('user', $user->data());
                Session::write('user.confirmed_email', 1);
                return $this->redirect(['Users::office', '?' => ['success' => 'true']]);
            } else {
                return $this->redirect(['Users::registration', '?' => ['success' => 'false']]);
            }
        } else {
            return $this->redirect(['Users::registration', '?' => ['success' => 'false']]);
        }
    }

    /**
     * Метод проверки существования имейла
     *
     * @TODO Cooldown timer
     */
    public function checkform()
    {
        if (isset($this->request->data['email'])) {
            if ($user = User::find('first', ['conditions' => ['email' => $this->request->data['email']]])) {
                return ['data' => true];
            }
        }
        return ['data' => false];
    }

    public function setnewpassword()
    {
        $token = $this->request->query['token'];
        if (isset($token)) {
            $user = User::first(['conditions' => ['token' => $token]]);
            if ($user) {
                Auth::set('user', $user->data());
                if ($this->request->data) {
                    $errors = [];
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
                        $user->save(null, ['validate' => false]);
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

    public function second()
    {
    }

    public function recover()
    {
        if (Session::read('user.id')) {
            return $this->redirect('/users/profile');
        }
        $errors = [];
        $success = false;
        if ($this->request->data) {
            if ($user = User::findByEmail($this->request->data['email'])) {
                $user->token = $user->generateToken();
                $user->save(null, ['validate' => false]);
                UserMailer::forgotpassword_mail($user);
                $success = true;
            } else {
                $errors[] = 'Пользователь с таким Email не найден.';
            }
        }
        return compact('errors', 'success');
    }

    public function profile()
    {
        $user = User::first(Session::read('user.id'));
        if ($user->id == '21376') {
            $this->redirect('/news');
        }
        $winnersData = Solution::all(['conditions' => ['Solution.awarded' => 1, 'Pitch.private' => 0], 'order' => ['Solution.created' => 'desc'], 'limit' => 50, 'with' => ['Pitch']]);
        $winners = [];
        foreach ($winnersData as $winner) {
            if ($winner->pitch->category_id != 7) {
                $winners[] = $winner;
            }
        }

        $passwordInfo = false;
        $emailInfo = false;
        if ($this->request->data) {
            $user->userdata = serialize([
                'birthdate' => $this->request->data['birthdate'],
                'city' => $this->request->data['city'],
                'profession' => $this->request->data['profession'],
                'about' => $this->request->data['about'],
            ]);
            $user->isClient = $this->request->data['isClient'];
            $user->isDesigner = $this->request->data['isDesigner'];
            $user->isCopy = $this->request->data['isCopy'];
            $user->is_company = $this->request->data['is_company'];

            $user->first_name = $this->request->data['first_name'];
            $user->last_name = $this->request->data['last_name'];
            $user->gender = $this->request->data['gender'];

            $user->save(null, ['validate' => false]);
        }
        return compact('user', 'winners', 'passwordInfo', 'emailInfo');
    }

    /**
     * Метод для смены данных пользователя на страницы настроек
     *
     */
    public function update()
    {
        $user = User::first(Session::read('user.id'));
        $currentEmail = $user->email;
        $result = false;
        if ($this->request->data) {
            $shortUpdate = false;
            if (isset($this->request->data['short_company_name'])) {
                $shortUpdate = true;
                $user->short_company_name = $this->request->data['short_company_name'];
                $user->companydata = serialize([
                    'company_name' => $this->request->data['company_name'],
                    'inn' => $this->request->data['inn'],
                    'kpp' => $this->request->data['kpp'],
                    'address' => $this->request->data['address'],
                ]);
            }
            if (isset($this->request->data['first_name'])) {
                $shortUpdate = true;
                $user->first_name = $this->request->data['first_name'];
            }
            if (isset($this->request->data['last_name'])) {
                $shortUpdate = true;
                $user->last_name = $this->request->data['last_name'];
            }
            if (isset($this->request->data['gender'])) {
                $shortUpdate = true;
                $user->gender = $this->request->data['gender'];
            }
            if (isset($this->request->data['isClient'])) {
                $shortUpdate = true;
                $user->isDesigner = 0;
                $user->isCopy = 0;
                $user->isClient = $this->request->data['isClient'];
                $user->is_company = 0;
            }
            if (isset($this->request->data['isDesigner'])) {
                $shortUpdate = true;
                $user->isDesigner = $this->request->data['isDesigner'];
                $user->isCopy = 0;
                $user->isClient = 0;
                $user->is_company = 0;
            }
            if (isset($this->request->data['isCopy'])) {
                $shortUpdate = true;
                $user->isDesigner = 1;
                $user->isCopy = $this->request->data['isCopy'];
                $user->isClient = 0;
                $user->is_company = 0;
            }
            if (isset($this->request->data['is_company'])) {
                $shortUpdate = true;
                $user->isDesigner = 0;
                $user->isCopy = 0;
                $user->isClient = 0;
                $user->is_company = $this->request->data['is_company'];
            }
            if (isset($this->request->data['birthdate'])) {
                $shortUpdate = true;
                $unserialized = unserialize($user->userdata);
                $user->userdata = serialize([
                    'birthdate' => $this->request->data['birthdate'],
                    'city' => $unserialized['city'],
                    'profession' => $unserialized['profession'],
                    'about' => $unserialized['about'],
                    'accept_sms' => $unserialized['accept_sms']
                ]);
            }
            if (isset($this->request->data['accept_sms'])) {
                $shortUpdate = true;
                $unserialized = unserialize($user->userdata);
                $smsValue = (int) $this->request->data['accept_sms'];
                $smsValue = (bool) $smsValue;
                $user->userdata = serialize([
                    'birthdate' => $unserialized['birthdate'],
                    'city' => $unserialized['city'],
                    'profession' => $unserialized['profession'],
                    'about' => $unserialized['about'],
                    'accept_sms' => $smsValue
                ]);
            }
            if (isset($this->request->data['city'])) {
                $shortUpdate = true;
                $unserialized = unserialize($user->userdata);
                $user->userdata = serialize([
                    'birthdate' => $unserialized['birthdate'],
                    'city' => $this->request->data['city'],
                    'profession' => $unserialized['profession'],
                    'about' => $unserialized['about'],
                    'accept_sms' => $unserialized['accept_sms']
                ]);
            }
            if ($shortUpdate) {
                $result = $user->save(null, ['validate' => false]);
                $data = $this->request->data;
                if ($this->request->is('json')) {
                    return compact('result', 'data');
                } else {
                    return $this->redirect('/users/profile');
                }
            }

            if (isset($this->request->data['removephone'])) {
                $user->phone = '';
                $user->phone_valid = 0;
                $user->phone_code = '';
                $user->phone_operator = '';
                $user->save(null, ['validate' => false]);
                return json_encode(true);
            }

            if (isset($this->request->data['resendcode'])) {
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
                        $smsCount = [time()];
                    }
                } else {
                    $smsCount = [time()];
                }
                Session::write('user.smsCount', $smsCount);

                $textMessage = $user->phone_code . ' - код для проверки';
                $params = [
                    "text" => $textMessage
                ];
                $phones = [$user->phone];
                $smsService = new SmsUslugi();
                $respond = $smsService->send($params, $phones);
                if (!isset($respond['smsid'])) {
                    $smsId = 0;
                } else {
                    $smsId = $respond['smsid'];
                }
                $data = [
                    'user_id' => $user->id,
                    'created' => date('Y-m-d H:i:s'),
                    'phone' => $user->phone,
                    'text' => $textMessage,
                    //'status' => $smsStatus,
                    'status' => $respond['descr'],
                    'text_id' => $smsId
                ];
                TextMessage::create($data)->save();
                $phone = $user->phone;
                $phone_valid = $user->phone_valid;
                return json_encode(compact('respond', 'phone', 'phone_valid'));
            }

            if (isset($this->request->data['phone'])) {
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
                        $smsCount = [time()];
                    }
                } else {
                    $smsCount = [time()];
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

            if (isset($this->request->data['code'])) {
                return json_encode(User::phoneValidationFinish($user->id, (int) $this->request->data['code']));
            }

            if (isset($this->request->data['email'])) {
                if ($userWithEmail = User::first([
                    'conditions' => [
                        'email' => $this->request->data['email'],
                        'id' => [
                            '!=' => $user->id,
                        ],
                    ]])) {
                    $emailInfo = 'Пользователь с таким адресом электронной почты уже существует!';
                    $result = false;
                } else {
                    $user->email = $this->request->data['email'];
                    if ($currentEmail != $this->request->data['email']) {
                        $emailInfo = 'Адрес электронной почты изменён, вам необходимо подтвердить его!';
                        $user->confirmed_email = 0;
                        $user->token = $user->generateToken();
                        Session::write('user.email', $user->email);
                        UserMailer::verification_mail($user);
                        $result = $user->save(null, ['validate' => false]);
                    }
                }
                return compact('result', 'emailInfo');
            } elseif (isset($this->request->data['newpassword'])) {
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
                        $result = $user->save(null, ['validate' => false]);
                        $passwordInfo = 'Пароль изменен!';
                    }
                }
                return compact('result', 'passwordInfo');
            } else {
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
            $result = $user->save(null, ['validate' => false]);
        }
        if ($this->request->is('json')) {
            return compact('result');
        } else {
            return $this->redirect('/users/profile');
        }
    }

    public function preview()
    {
        if ($this->request->id != Session::read('user.id')) {
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
            $totalFavoriteMe = Favourite::getNumberOfTimesAddedToFavourite($user->id);
            $totalUserFavorite = Favourite::getCountFavoriteUsersForUser($user->id);
            $awardedSolutionNum = (int) User::getAwardedSolutionNum(Session::read('user.id'));
            $totalSolutionNum = (int) User::getTotalSolutionNum(Session::read('user.id'));
            if (User::checkRole('admin')) {
                $selectedSolutions = Solution::getUsersSolutions($this->request->id);
            } else {
                $selectedSolutions = Solution::getUsersSolutions($this->request->id, true);
            }
            foreach ($selectedSolutions as $solution) {
                $solution->tags = Solution::getTagsArrayForSolution($solution);
            }
            $isClient = false;
            $userPitches = Pitch::all(['order' => ['started' => 'desc'],  'with' => ['Category'], 'conditions' => ['OR' => [['type' => 'company_project'], ['type' => '']], 'billed' => 1, 'published' => 1, 'user_id' => $user->id, 'blank' => 0, 'multiwinner' => 0]]);
            if (($user->isClient || $user->is_company) && (count($userPitches) > 0)) {
                $isClient = true;
                $ids = [];
                foreach ($userPitches as $pitch) {
                    $ids[] = $pitch->id;
                }
                $selectedSolutions = Solution::all(['conditions' => [
                    'pitch_id' => $ids,
                    'OR' => [['rating = 4'], ['rating = 5'], ['Solution.awarded = 1'], ['Solution.nominated = 1']]
                ],
                    'with' => ['Pitch']
                ]);
            }
            $userPitches = $userPitches->data();
            return compact('user', 'pitchCount', 'averageGrade', 'totalUserFavorite', 'totalFavoriteMe', 'totalViews', 'totalLikes', 'awardedSolutionNum', 'totalSolutionNum', 'selectedSolutions', 'isClient', 'userPitches');
        }
    }

    public function view()
    {
        if ((isset($this->request->id)) && ($user = User::first((int) $this->request->id))) {
            if (($user->active == 0) && !User::checkRole('admin')):
                return $this->redirect('/');
            endif;

            $pitchCount = User::getPitchCount((int) $user->id);

            $averageGrade = User::getAverageGrade($this->request->id);
            if (!$averageGrade) {
                $averageGrade = 0;
            }
            $totalViews = (int) User::getTotalViews($this->request->id);
            $totalLikes = (int) User::getTotalLikes($this->request->id);
            $awardedSolutionNum = (int) User::getAwardedSolutionNum($this->request->id);
            $totalSolutionNum = (int) User::getTotalSolutionNum($this->request->id);
            $totalFavoriteMe = Favourite::getNumberOfTimesAddedToFavourite($user->id);
            $totalUserFavorite = Favourite::getCountFavoriteUsersForUser($user->id);
            $isFav = Favourite::first(['conditions' => ['user_id' => Session::read('user.id'), 'fav_user_id' => $user->id]]);

            if (User::checkRole('admin')) {
                $selectedSolutions = Solution::getUsersSolutions($this->request->id);
            } else {
                $selectedSolutions = Solution::getUsersSolutions($this->request->id, true);
            }

            $moderations = null;
            if (User::checkRole('admin') || (Session::read('user.isAdmin') == 1)) {
                $moderations = Moderation::all(['conditions' => ['model_user' => $user->id]]);
            }
            $isClient = false;
            $userPitches = Pitch::all(['order' => ['started' => 'desc'],  'with' => ['Category'], 'conditions' => ['OR' => [['type' => 'company_project'], ['type' => '']], 'billed' => 1, 'published' => 1, 'user_id' => $user->id, 'blank' => 0, 'multiwinner' => 0]]);
            if (($user->isClient || $user->is_company) && (count($userPitches) > 0)) {
                $isClient = true;
                $ids = [];
                foreach ($userPitches as $pitch) {
                    $ids[] = $pitch->id;
                }
                $selectedSolutions = Solution::all(['conditions' => [
                                'pitch_id' => $ids,
                                'OR' => [['rating = 4'], ['rating = 5'], ['Solution.awarded = 1'], ['Solution.nominated = 1']]
                            ],
                            'with' => ['Pitch']
                ]);
            }
            $userPitches = $userPitches->data();
            return compact('user', 'pitchCount', 'totalUserFavorite', 'isFav', 'totalFavoriteMe', 'averageGrade', 'totalViews', 'totalLikes', 'awardedSolutionNum', 'totalSolutionNum', 'selectedSolutions', 'isClient', 'moderations', 'userPitches');
        }
        throw new Exception('Public:Такого пользователя не существует.', 404);
    }

    public function savePaymentData()
    {
        $user = User::first(Session::read('user.id'));
        $user->paymentOptions = serialize([$this->request->data]);
        $user->save(null, ['validate' => false]);
        return $user->paymentOptions;
    }

    public function loginasadmin()
    {
        $token = $this->request->query['token'];
        $redirect = false;
        if (isset($this->request->query['redirect'])) {
            $redirect = $this->request->query['redirect'];
        }
        if (isset($token)) {
            $user = User::first(['conditions' => ['token' => $token, 'isAdmin' => 1]]);
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

    public function details()
    {
        return $this->redirect('/users/profile');
        /*$user = User::first(Session::read('user.id'));
        if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('user');
        } else {
            return $this->render(array('layout' => false, 'data' => compact('user')));
        }*/
    }

    public function ban()
    {
        if (($user = User::first($this->request->data['id'])) && (Session::read('user.isAdmin') == 1 || (in_array(Session::read('user.id'), User::$admins)))) {
            $term = $this->request->data['term'] * DAY;
            $user->silenceUntil = date('Y-m-d H:i:s', time() + $term);
            $user->silenceCount += 1;
            $user->save(null, ['validate' => false]);
            UserMailer::ban(['user' => $user->data(), 'term' => $this->request->data['term']]);
        }

        return $user->data();
    }

    public function unban()
    {
        if (($user = User::first($this->request->data['id'])) && (Session::read('user.isAdmin') == 1 || (in_array(Session::read('user.id'), User::$admins)))) {
            $user->silenceUntil = date('Y-m-d H:i:s');
            $user->save(null, ['validate' => false]);
        }
        return $user->data();
    }

    public function unblock()
    {
        if (($user = User::first($this->request->data['id'])) && ($this->userHelper->isAdmin())) {
            $user->banned = 0;
            $user->banned_until = '0000-00-00 00:00:00';
            $user->save(null, ['validate' => false]);
            return $this->request->data;
        }
    }

    public function block()
    {
        if (($user = User::first($this->request->data['id'])) && (Session::read('user.isAdmin') == 1 || (in_array(Session::read('user.id'), User::$admins)))) {
            $user->block();
            UserMailer::block(['user' => $user->data()]);
            return $this->request->data;
        }
    }

    public function loginasuser()
    {
        if (in_array(Session::read('user.id'), User::$admins)) {
            if ($user = User::first($this->request->id)) {
                Auth::clear('user');
                Auth::set('user', $user->data());
                return $this->redirect('/');
            }
        }
    }

    public function stats()
    {
        $count = User::count();
        return ['data' => ['count' => $count]];
    }

    public function deleteaccount()
    {
        $user = User::find(Session::read('user.id'));
        $user->active = 0;
        $user->oldemail = $user->email;
        $user->email = '';
        $user->save(null, ['validate' => false]);
        Auth::clear('user');
        session_destroy();
        unset($_SESSION);

        setcookie("PHPSESSID", null);
        die();
    }

    public function requesthelp()
    {
        $success = false;
        if (($this->request->data) && (isset($this->request->data['case'])) && ($this->request->data['case'] == 'fu27fwkospf')) {
            if ($this->request->data['target'] != 0) {
                $emails = [
                    1 => 'devochkina@godesigner.ru',
                    2 => 'va@godesigner.ru',
                    3 => 'nyudmitriy@godesigner.ru',
                    4 => 'fedchenko@godesigner.ru',
                    5 => 'm.elenevskaya@godesigner.ru'
                ];
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
            $success = true;
        }
        return compact('success');
    }

    public function vklogin()
    {
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

    public function addSocial()
    {
        if ($this->request->is('json') && (($this->request->id == 1 || $this->request->id == 2)) && (Session::read('user.id') > 0) && ($user = User::first((int) Session::read('user.id')))) {
            $user->social = (int) $this->request->id;
            Session::write('user.social', $user->social);
            return $user->save(null, ['validate' => false]);
        }
        $this->redirect('/');
    }

    public function checkPhone()
    {
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
                    $smsCount = [time()];
                }
            } else {
                $smsCount = [time()];
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

    public function validateCode()
    {
        if ($this->request->is('json') && (Session::read('user.id') > 0) && ($user = User::first((int) Session::read('user.id'))) && $this->request->data['verifyCode']) {
            return json_encode(User::phoneValidationFinish($user->id, (int) $this->request->data['verifyCode']));
        }
        $this->redirect('/');
    }

    /**
     * Метод происходит при клике на ссылку в ленте новостей, он увеличивает счётчик кликов для
     * записи новости
     *
     * @return Response
     */
    public function click()
    {
        $link = '/';
        if ((isset($this->request->query['id'])) && (isset($this->request->query['link']))) {
            if ($news = News::first($this->request->query['id'])) {
                $news->views += 1;
                $news->save();
            }
            $link = $this->request->query['link'];
        }
        return $this->redirect($link);
    }

    public function gender()
    {
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
            return json_encode($user->save(null, ['validate' => false]));
        }
    }

    public function sale()
    {
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
                    return $this->redirect(['controller' => 'pitches', 'action' => 'view', 'id' => $data['pitch_id']]);
                }
            }
        }
        return $this->redirect('/');
    }

    /**
     * Метод для вывода страницы абонентского кабинета
     * если json, выводит актуальную информацию пользователя
     * html|json
     */
    public function subscriber()
    {
        $conditions = [
            'billed' => 1,
            'user_id' => $this->userHelper->getId(),
            'OR' => [
                ['type' => 'fund-balance'],
                ['type' => 'plan-payment'],
                ['type' => 'company_project'],
                ['type' => 'multiwinner', 'category_id' => 20]
            ]
        ];
        if ((isset($this->request->query['query']))
            && ($this->request->query['query'] != 'найдите свой  проект по ключевому слову или типу')
            && (!empty($this->request->query['query']))) {
            $query = urldecode(filter_var($this->request->query['query'], FILTER_SANITIZE_STRING));
            $firstLetter = mb_substr($query, 0, 1, 'utf-8');
            $firstUpper = (mb_strtoupper($firstLetter, 'utf-8'));
            $firstLower = (mb_strtolower($firstLetter, 'utf-8'));
            $string = $firstLower . mb_substr($query, 1, mb_strlen($query, 'utf-8'), 'utf-8') . '|' . $firstUpper . mb_substr($query, 1, mb_strlen($query, 'utf-8'), 'utf-8') . '|' . mb_strtoupper($query, 'utf-8') . '|' . str_replace('ё', 'е', $query);
            $conditions += ['title' => ['REGEXP' => $string]];
        }
        $paymentsObj = Pitch::all([
            'conditions' => $conditions,
            'order' => ['started' => 'desc']
        ]);
        $payments = [];
        $defaultFinishDate = date('Y-m-d H:i:s', time() + (10 * DAY));
        $moneyFormatter = new MoneyFormatter();
        $client = User::first($this->userHelper->getId());
        $plan = User::getCurrentPlanData($client->id);
        $idsForAddons = [];
        foreach ($paymentsObj as $row) {
            $idsForAddons[] = $row->id;
            $data = $row->data();
            $data['hasBill'] = false;
            $data['timestamp'] = strtotime($data['billed_date']);
            if ($row->type == 'plan-payment') {
                $amount = SubscriptionPlan::extractFundBalanceAmount($row->id);
                if ($amount > 0) {
                    $data['type'] = 'fund-balance';
                    $data['title'] = 'Пополнение счёта';
                    $data['total'] = $amount;
                }
            }
            $data['extraFunds'] = 0;
            if (($data['type'] === 'company_project') && ($addons = Addon::all(['conditions' => [
                'pitch_id' => $row->id,
                'billed' => 1
            ]]))) {
                $reducePriceAmount = 0;
                $addonTotal = 0;
                $instantOptions = 0;
                foreach ($addons as $addon) {
                    $addonTotal += (int) $addon->total;
                    if ($addon->prolong == 1) {
                        $reducePriceAmount += $addon->{'prolong-days'} * 1000;
                    }
                }
                if ((!in_array('pinproject', $plan['free'])) && ($data['pinned'])) {
                    $instantOptions += 1000;
                }
                $data['addonTotal'] = $addonTotal;
                $data['finalPrice'] = (int) $data['price'] + $addonTotal + $instantOptions - $reducePriceAmount;
                $data['price'] -= $reducePriceAmount;
            }
            if ($data['expert'] == 1) {
                $reducedPriceAmount = 0;
                $receipt = Receipt::exportToArray($data['id']);
                foreach ($receipt as $receiptRow) {
                    if (($receiptRow['name'] === 'Экспертное мнение') || ($receiptRow['name'] === 'экспертное мнение')) {
                        $reducedPriceAmount = $receiptRow['value'];
                    }
                }
                $data['price'] += $reducedPriceAmount;
                $data['extraFunds'] += $reducedPriceAmount;
            }
            if (($data['pinned'] == 1) && (!in_array('pinproject', $plan['free']))) {
                $reducedPriceAmount = 0;
                $receipt = Receipt::exportToArray($data['id']);
                foreach ($receipt as $receiptRow) {
                    if ($receiptRow['name'] === '«Прокачать» проект') {
                        $reducedPriceAmount = $receiptRow['value'];
                    }
                }
                $data['price'] += $reducedPriceAmount;
                $data['extraFunds'] += $reducedPriceAmount;
            }
            if ($data['type'] != 'fund-balance') {
                if ($data['type'] != 'plan-payment') {
                    $data['formattedMoney'] = '- ' . $moneyFormatter->formatMoney($data['price'], ['suffix' => '']);
                } else {
                    $data['formattedMoney'] = '- ' . $moneyFormatter->formatMoney($data['price'], ['suffix' => '']);
                }
            }
            if ($data['type'] == 'fund-balance') {
                $data['formattedDate'] = date('d.m.Y', strtotime($row->billed_date));
                $data['formattedMoney'] = '+ ' . $moneyFormatter->formatMoney($data['total'], ['suffix' => '']);
            }
            $payments[] = $data;
            if (($data['type'] === 'company_project') && ($data['status'] == 2) && ($data['awarded'] == 0)) {
                $formattedRefund = $moneyFormatter->formatMoney((int) $data['finalPrice'] - (int) $data['extraFunds'], ['suffix' => '']);
                $refundedObject = [
                    "id" => $data['id'],
                    "type" => "refund",
                    "total" => $data['finalPrice'] - (int) $data['extraFunds'],
                    "formattedMoney" => "+ $formattedRefund",
                    "formattedDate" => date('d.m.Y', strtotime($data['finishDate'])),
                    "timestamp" => strtotime($data['finishDate']),
                    "projectTitle" => $data['title']
                ];
                $payments[] = $refundedObject;
            }
        }
        $addons = Addon::all(['conditions' => [
            'Addon.pitch_id' => $idsForAddons,
            'Addon.billed' => 1
        ], 'with' => ['Pitch']]);
        $numInflector = new NumInflector();
        foreach ($addons as $addon) {
            if ($cardData = Paymaster::first(['conditions' => ['LMI_PAYMENT_NO' => $addon->id]])) {
                continue;
            }
            if ($cardData = Payment::first(['conditions' => ['OrderId' => $addon->payture_id, 'Success' => 'True']])) {
                continue;
            }
            $data = $addon->data();
            $data['type'] = 'addon';
            $data['timestamp'] = strtotime($addon->created);
            $data['formattedDate'] = date('d.m.Y', strtotime($addon->created));
            $data['formattedMoney'] = '- ' . $moneyFormatter->formatMoney($data['total'], ['suffix' => '']);
            $data['title'] = 'Оплата доп. опции';
            $title = [];
            if ($data['experts']) {
                $title[] = 'экспертное мнение';
            }
            if ($data['prolong']) {
                $days = $numInflector->formatString($data['prolong-days'], ['string' => ['first' => 'день', 'second' => 'дня', 'third' => 'дней']]);
                $title[] = 'продление ' . $data['prolong-days'] . ' ' . $days  ;
            }
            if ($data['brief']) {
                $title[] = 'заполнение брифа';
            }
            if ($data['guaranteed']) {
                $title[] = 'гарантированный проект';
            }
            if ($data['pinned']) {
                $title[] = 'прокачать бриф';
            }
            if ($data['private']) {
                $title[] = 'скрыть проект';
            }
            $data['title'] .= " \n\r(" . implode(', ', $title) . ') в проекте «' . $data['pitch']['title'] . '»';
            $payments[] = $data;
        }
        usort($payments, function ($a, $b) {
            $firstDate = $a['timestamp'];
            $secondDate = $b['timestamp'];
            return ($firstDate > $secondDate) ? -1 : 1;
        });
        if ($this->request->is('json')) {
            $data = [
                'balance' => $this->userHelper->getBalance(),
                'companyName' => $this->userHelper->getShortCompanyName(),
                'fullCompanyName' => $this->userHelper->getFullCompanyName(),
                'expirationDate' => $this->userHelper->getSubscriptionExpireDate('d/m/Y'),
                'isSubscriptionActive' =>(int) $this->userHelper->isSubscriptionActive(),
                'plan' => $this->userHelper->getCurrentPlanData(),
                'payload' => $payments,
                'conditions' => $conditions
            ];
            return $data;
        }
        return compact('payments', 'defaultFinishDate');
    }

    public function fill_balance()
    {
        $amount = $this->request->data['amount'];
        User::fillBalance($this->userHelper->getId(), $amount);
        return compact('amount');
    }
}

class qqUploadedFileXhr
{

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    public function save($path)
    {
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

    public function getName()
    {
        return $_GET['qqfile'];
    }

    public function getSize()
    {
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
class qqUploadedFileForm
{

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    public function save($path)
    {
        if (!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)) {
            return false;
        }
        return true;
    }

    public function getName()
    {
        return $_FILES['qqfile']['name'];
    }

    public function getSize()
    {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader
{

    private $allowedExtensions = [];
    private $sizeLimit = 104857600;
    private $file;

    public function __construct(array $allowedExtensions = [], $sizeLimit = 104857600)
    {
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

    private function checkServerSettings()
    {
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($str)
    {
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
    public function handleUpload($uploadDirectory, $replaceOldFile = true)
    {
        if (!is_writable($uploadDirectory)) {
            return ['error' => "Server error. Upload directory isn't writable."];
        }

        if (!$this->file) {
            return ['error' => 'No files were uploaded.'];
        }



        $pathinfo = pathinfo($this->file->getName());
        $originalname = $pathinfo['filename'];
        $filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
            $these = implode(', ', $this->allowedExtensions);
            return ['error' => 'File has an invalid extension, it should be one of ' . $these . '.'];
        }

        if (!$replaceOldFile) {
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }

        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)) {
            return ['success' => true, 'name' => $originalname . '.' . $ext, 'tmpname' => $uploadDirectory . $filename . '.' . $ext];
        } else {
            return ['error' => 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered'];
        }
    }
}
