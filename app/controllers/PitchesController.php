<?php

namespace app\controllers;

use app\extensions\helper\Brief;
use app\extensions\helper\NameInflector;
use app\models\Bill;
use app\models\Lead;
use app\models\Manager;
use app\models\Moderation;
use app\models\Pitch;
use app\models\Pitchrating;
use app\models\Pitchfile;
use app\models\Category;
use app\models\Addon;
use app\models\Solution;
use app\models\Comment;
use app\models\User;
use app\models\Transaction;
use app\models\Receipt;
use app\models\Request;
use app\models\Expert;
use app\models\Promocode;
use app\models\Paymaster;
use app\models\Payanyway;
use app\models\SubscriptionPlan;
use app\models\Url;
use app\models\Like;
use app\models\Tag;
use app\models\Uploadnonce;
use app\models\Note;
use app\extensions\paymentgateways\Webgate;
use lithium\storage\Session;
use lithium\analysis\Logger;
use app\extensions\helper\MoneyFormatter;
use app\extensions\helper\PitchTitleFormatter;
use app\extensions\helper\PdfGetter;
use app\extensions\helper\Avatar as AvatarHelper;
use app\extensions\helper\User as UserHelper;
use Exception;
use app\extensions\storage\Rcache;

class PitchesController extends AppController
{
    /**
     * Методы, доступные без аутентификации.
     *
     * @var array
     */
    public $publicActions = [
        'crowdsourcing', 'promocode', 'index', 'printpitch', 'robots', 'fillbrief', 'add', 'create',
        'brief', 'activate', 'view', 'details', 'paymaster', 'callback', 'payanyway', 'viewsolution', 'getlatestsolution', 'getpitchdata', 'designers', 'getcommentsnew', 'apipitchdata', 'addfastpitch', 'fastpitch',
    ];

    /**
     * Метод выводит список питчей (html,json).
     *
     * @return array
     */
    public function index()
    {
        $categories = Category::all();
        $hasOwnHiddenPitches = false;
        if ($this->userHelper->getId()) {
            $usersPitches = Pitch::all(['conditions' => [
                            'type' => '',
                            'user_id' => Session::read('user.id'),
                            'published' => 0,
                            'status' => 0,
                            'blank' => 0,
                        ], 'with' => ['Category']]);
            if ($usersPitches) {
                $hasOwnHiddenPitches = true;
            }
        }

        $limit = 70;
        $page = Pitch::getQueryPageNum($this->request->query['page']);
        if ((is_string($this->request->query['searchTerm']) && !empty($this->request->query['searchTerm'])) && $this->request->query['searchTerm'] != 'НАЙТИ ПРОЕКТ ПО КЛЮЧЕВОМУ СЛОВУ ИЛИ ТИПУ') {
            require_once LITHIUM_APP_PATH . '/libraries/sphinxapi.php';
            $client = new \SphinxClient();
            $client->open();
            error_reporting(0);
            $client->SetFieldWeights([
                'title' => 1000,
                'description' => 1]);
            $client->SetMatchMode( SPH_MATCH_EXTENDED2  );
            $client->SetSortMode(SPH_SORT_RELEVANCE);
            $searchQuery = $client->Query(urldecode(filter_var($this->request->query['searchTerm'], FILTER_SANITIZE_STRING)), 'projects');
            $pitchesIds = array_keys($searchQuery['matches']);
            $weightMap = [];
            $k = 0;
            foreach($searchQuery['matches'] as $id => $attrs) {
                $weightMap[$id] = ['weight' => $attrs['weight'], 'index' => $k];
                $k++;
            }
            $priceFilter = Pitch::getQueryPriceFilter($this->request->query['priceFilter']);
            $order = Pitch::getQueryOrder($this->request->query['order'], $this->request->query['type']);
            $timeleftFilter = Pitch::getQueryTimeframe($this->request->query['timeframe']);
            $type = Pitch::getQueryType($this->request->query['type']);
            $category = Pitch::getQueryCategory($this->request->query['category']);
            $conditions = array('published' => 1, 'multiwinner' => 0, 'blank' => 0);

            $conditions += $type;
            $conditions += $category;
            $conditions += $priceFilter;
            $conditions += $timeleftFilter;
            $conditions += ['Pitch.id' => $pitchesIds];
        }else {
            $priceFilter = Pitch::getQueryPriceFilter($this->request->query['priceFilter']);
            $order = Pitch::getQueryOrder($this->request->query['order'], $this->request->query['type']);
            $timeleftFilter = Pitch::getQueryTimeframe($this->request->query['timeframe']);
            $type = Pitch::getQueryType($this->request->query['type']);
            $category = Pitch::getQueryCategory($this->request->query['category']);
            $conditions = array('published' => 1, 'multiwinner' => 0, 'blank' => 0);
            $search = Pitch::getQuerySearchTerm($this->request->query['searchTerm']);

            $conditions += $type;
            $conditions += $category;
            $conditions += $priceFilter;
            $conditions += $timeleftFilter;
            $conditions += $search;
        }
        /*         * **** */
        $total = ceil(Pitch::count(array(
                'with' => 'Category',
                'conditions' => $conditions,
                'order' => $order,
            )) / $limit);
        $pitches = Pitch::all(array(
            'with' => 'Category',
            'conditions' => $conditions,
            'order' => $order,
            'limit' => $limit,
            'page' => $page,
        ));

        $i = 1;
        $tempPitchList = array();
        if ($pitches) {
            if (($hasOwnHiddenPitches) && ($page == 1)) {
                foreach ($usersPitches as $pitch) {
                    $tempPitchList[] = $pitch->data();
                }
            }
            foreach ($pitches as $pitch) {
                $tempPitchList[] = $pitch->data();
            }
        }
        $pitchList = array();
        $pitchTitleHelper = new PitchTitleFormatter();
        foreach ($tempPitchList as &$pitch) {
            $pitch['sort'] = $i;
            $pitch['title'] = $pitchTitleHelper->renderTitle($pitch['title'], 80);
            $pitch['multiple'] = Pitch::getMultiple($pitch['category_id'], $pitch['specifics']);
            if((isset($weightMap)) && (isset($weightMap[$pitch['id']]))) {
                $pitch['weight'] = $weightMap[$pitch['id']]['weight'];
                $pitch['index'] = $weightMap[$pitch['id']]['index'];
            }
            $pitchList[] = $pitch;
            ++$i;

        }
        if(isset($weightMap)) {
            usort($pitchList, function($a, $b) {
                if($a['weight'] < $b['weight']) return 1;
                if($a['weight'] > $b['weight']) return -1;
                if($a['weight'] == $b['weight']) {
                    if($a['index'] < $b['index']) return 1;
                    if($a['index'] > $b['index']) return -1;
                }
                return 0;
            });
            $i = 1;
            foreach($pitchList as &$pitch) {
                $pitch['sort'] = $i;
                ++$i;
            }
        }
        $data = array(
            'pitches' => $pitchList,
            'info' => array(
                'page' => $page,
                'total' => $total,
            ),
        );
        $query = $this->request->query;
        $debug = $this->debug->dumpDebugInfo();
        $totalCount = Pitch::getNumOfCompletedProjects();

        return compact('data', 'categories', 'query', 'selectedCategory', 'debug', 'totalCount');
    }

    /**
     * Метод выводит соглашение, если закрытый питч.
     *
     * @return object|void
     */
    public function agreement()
    {
        if (isset($this->request->params['id'])) {
            $pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->params['id']), 'with' => array('User')));
            if ($pitch->private == 1) {
                return $this->render(array('layout' => false, 'data' => compact('pitch')));
            } else {
                return $this->redirect('/pitches/details/'.$this->request->params['id']);
            }
        }
        die();
    }

    /**
     * Метод выводит проекты для страницы "Мои проекты".
     *
     * @return array
     */
    public function participate()
    {
        $categories = Category::all();
        if ($this->request->query['type'] == 'favourites') {
            $pitchesId = User::getFavouritePitches(Session::read('user.id'));
        } elseif (($this->request->query['type'] == 'awarded') || ($this->request->query['type'] == 'completion-stage')) {
            $pitchesId = User::getUserRelatedPitches(Session::read('user.id'), true);
        } else {
            $pitchesId = User::getUserRelatedPitches(Session::read('user.id'));
        }
        $data = array(
            'pitches' => array(),
            'info' => array(
                'page' => 1,
                'total' => 0,
            ),
        );
        if (!empty($pitchesId)) {
            $pitchesId = array_keys($pitchesId);
            $allowedCategories = array();
            foreach ($categories as $catI) {
                $allowedCategories[] = $catI->id;
            }
            $limit = 50;
            $page = 1;
            $priceFilter = Pitch::getQueryPriceFilter($this->request->query['priceFilter']);
            $order = Pitch::getQueryOrder($this->request->query['order']);
            $category = Pitch::getQueryCategory($this->request->query['category']);
            $search = Pitch::getQuerySearchTerm($this->request->query['searchTerm']);
            $conditions = array('Pitch.id' => $pitchesId, 'Pitch.type' => array('!=' => 'penalty'));
            if ($this->request->query['type'] != 'favourites') {
                $type = Pitch::getQueryType($this->request->query['type']);
                $conditions += $type;
            }
            $conditions += $category;
            $conditions += $priceFilter;
            $conditions += $search;

            if (isset($this->request->query['page'])) {
                $page = abs(intval($this->request->query['page']));
            }

            /*******/
            $total = ceil(Pitch::count(array(
                        'conditions' => $conditions,
                    )) / $limit);
            $pitches = Pitch::all(array(
                        'with' => 'Category',
                        'conditions' => $conditions,
                        'order' => $order,
            ));
            foreach ($pitches as $pitch) {
                $pitch->winlink = false;
                if ($pitch->status > 0) {
                    if ($pitch->nominated > 0) {
                        $winnerSolution = Solution::first($pitch->nominated);
                    } elseif ($pitch->awarded > 0) {
                        $winnerSolution = Solution::first($pitch->awarded);
                    }
                    if ($winnerSolution->user_id == Session::read('user.id')) {
                        $pitch->winlink = true;
                    }
                }
                $pitch->winnerSolution = $winnerSolution;
                if ($pitch->user_id == Session::read('user.id')) {
                    $pitch->winlink = true;
                }
                if (($pitch->status > 0) && ($note = Note::first(array('conditions' => array('pitch_id' => $pitch->id, 'status' => 2))))) {
                    $pitch->winlink = false;
                }

                $pitch->hasBill = false;
                if (($pitch->status == 2) && ($pitch->user_id == $this->userHelper->getId())) {
                    $client = User::first($pitch->user_id);
                    if (($bill = Bill::first($pitch->id)) || ($data = $client->getUnserializedCompanyData())) {
                        if ($bill) {
                            $pitch->hasBill = ($bill->individual == 1) ? 'fiz' : 'yur';
                        } elseif ($data) {
                            $pitch->hasBill = ($client->isEntrepreneur()) ? 'fiz': 'yur';
                        }
                    }
                }
            }
            $i = 1;
            $tempPitchList = $pitches->data();
            $tempPitchList = array_slice($tempPitchList, ($page - 1) * $limit, $limit, true);

            $pitchList = array();
            $pitchTitleHelper = new PitchTitleFormatter();
            foreach ($tempPitchList as &$pitch) {
                $pitch['sort'] = $i;
                $pitch['title'] = $pitchTitleHelper->renderTitle($pitch['title'], 80);
                $pitch['multiple'] = Pitch::getMultiple($pitch['category_id'], $pitch['specifics']);
                $pitchList[] = $pitch;
                ++$i;
            }
            $data = array(
                'pitches' => $pitchList,
                'info' => array(
                    'page' => $page,
                    'total' => $total,
                ),
            );
        }
        $query = $this->request->query;

        return compact('data', 'categories', 'query', 'selectedCategory', 'conditions');
    }

    public function updatefiles()
    {
        if ($pitch = Pitch::first($this->request->data['id'])) {
            $existingArray = array();
            //$existingArray = unserialize($pitch->filesId);
            if (isset($this->request->data['fileids'])) {
                foreach ($this->request->data['fileids'] as $key => $item) {
                    //                if(!in_array($item, $existingArray)) {
                    $existingArray[] = $item;
                    //              }
                }
            }
            $fileIds = serialize($existingArray);
            $pitch->filesId = $fileIds;
            $res = $pitch->save();

            return $fileIds;
        }

        return 'false';
    }

    public function delete()
    {
        if ($pitch = Pitch::first($this->request->id)) {
            if (($pitch->user_id == Session::read('user.id')) && ($pitch->published == 0) && ($pitch->billed == 0) && ($pitch->ideas_count == 0)) {
                $pitch->delete();
            }
            if (!$this->request->is('json')) {
                return $this->redirect('/pitches');
            }

            return $pitch->data();
        }
    }

    public function callback()
    {
        $secretword = 'ge6biTwUghs78g73sY6'; //секретное слово
        //$secretword = 'mO74WC9rnOJu'; //секретное слово
        //проверяем что пришли правильные данные
        if ((isset($this->request->data['SIGN_CALLBACK'])) && (trim($this->request->data['SIGN_CALLBACK']) == md5($this->request->data['TERMINAL'].$this->request->data['TIMESTAMP'].$this->request->data['ORDER'].$this->request->data['AMOUNT'].$this->request->data['RESULT'].$this->request->data['RC'].$this->request->data['RRN'].$this->request->data['INT_REF'].$this->request->data['TRTYPE'].$this->request->data['AUTHCODE'].$secretword))) {
            //проверяем что операция прошла успешно
            Logger::write('info', serialize($this->request->data), array('name' => 'masterbank'));
            if (0 == $this->request->data['RESULT']) {
                switch ($this->request->data['TRTYPE']) {
                    case 0:
                        //Авторизован (пользователь оплатил. данные на его карте заблокированы, но не списаны)
                        /*
                          если требуется моментальное закрытие (автозакрытие),
                          то отсюда же шлём метобом POST (через socket, curl или file_get_contents или....)
                          пришедшие данные на закрытие на ссылу из документации https://pay.masterbank.ru/acquiring/close
                         */
                        if ($pitch = Pitch::first($this->request->data['ORDER'])) {
                            if ($pitch->category_id == 10) {
                                $pitch->moderated = 1;
                                $pitch->save();
                                User::sendAdminModeratedPitch($pitch);
                            } else {
                                $webgate = new Webgate();
                                $result = $webgate->close($this->request->data);
                            }
                        } elseif ($addon = Addon::first($this->request->data['ORDER'])) {
                            $webgate = new Webgate();
                            $result = $webgate->close($this->request->data);
                        }

                        break;
                    case 21: //Оплачен
                        if ($pitch = Pitch::first($this->request->data['ORDER'])) {
                            Pitch::activate($this->request->data['ORDER']);
                        } elseif ($addon = Addon::first($this->request->data['ORDER'])) {
                            Addon::activate($addon);
                        }
                        break;
                    case 24: //Отменен
                        break;
                }
                //обновляем статус заказа
            }
        }
        //echo serialize($this->request->data);
        if (!empty($this->request->data)) {
            $transaction = Transaction::create();
            $transaction->set($this->request->data);
            $transaction->save();
        }
        header('HTTP/1.0 200 OK');
        die();
    }

    public function paymaster()
    {
        Logger::write('info', serialize($this->request->data), array('name' => 'paymaster'));
        if (!empty($this->request->data) && !empty($this->request->data['LMI_MERCHANT_ID']) && !empty($this->request->data['LMI_PAYMENT_SYSTEM']) && !empty($this->request->data['LMI_CURRENCY']) && !empty($this->request->data['LMI_PAYMENT_AMOUNT']) && !empty($this->request->data['LMI_PAYMENT_NO']) && !empty($this->request->data['LMI_SYS_PAYMENT_DATE']) && !empty($this->request->data['LMI_SYS_PAYMENT_ID']) && !empty($this->request->data['LMI_PAID_AMOUNT']) && !empty($this->request->data['LMI_HASH'])) {
            $transaction = Paymaster::create();
            $transaction->set($this->request->data);
            $transaction->save();
            if ($pitch = Pitch::first($this->request->data['LMI_PAYMENT_NO'])) {
                if (($pitch->type == 'plan-payment') || ($pitch->type == 'fund-balance')) {
                    SubscriptionPlan::activatePlanPayment($pitch->id);
                } elseif ($pitch->type == 'penalty') {
                    Pitch::activatePenalty($pitch->id);
                } else {
                    if ($pitch->multiwinner != 0) {
                        Pitch::activateNewWinner($this->request->data['LMI_PAYMENT_NO']);
                    } else {
                        Pitch::activate($this->request->data['LMI_PAYMENT_NO']);
                    }
                }
            } elseif ($addon = Addon::first($this->request->data['LMI_PAYMENT_NO'])) {
                Addon::activate($addon);
            }
        }
        header('HTTP/1.0 200 OK');
        die();
    }

    public function payanyway()
    {
        Logger::write('info', serialize($this->request->data), array('name' => 'payanyway'));
        if (!empty($this->request->data) && !empty($this->request->data['MNT_ID']) && !empty($this->request->data['MNT_TRANSACTION_ID']) && !empty($this->request->data['MNT_AMOUNT'])
        ) {
            $transaction = Payanyway::create();
            $transaction->set($this->request->data);
            $transaction->save();
            if (($pitch = Pitch::first($this->request->data['MNT_TRANSACTION_ID'])) && ($pitch->total == $this->request->data['MNT_AMOUNT'])) {
                if ($pitch->blank == 1) {
                    Pitch::activateLogoSalePitch($this->request->data['MNT_TRANSACTION_ID']);
                } else {
                    if ($pitch->multiwinner != 0) {
                        Pitch::activateNewWinner($this->request->data['MNT_TRANSACTION_ID']);
                    } else {
                        Pitch::activate($this->request->data['MNT_TRANSACTION_ID']);
                    }
                }
            } elseif ($addon = Addon::first($this->request->data['MNT_TRANSACTION_ID'])) {
                Addon::activate($addon);
            }
            echo 'SUCCESS';
        } else {
            echo 'FAIL';
        }
        header('HTTP/1.0 200 OK');
        die();
    }

    /**
     * Метод отображения страницы выбора категорий
     * Для абонентов делаем редирект на создание абонентского проекта.
     *
     * @return array
     */
    public function create()
    {
        if ($this->userHelper->isSubscriptionActive()) {
            return $this->redirect('/pitches/brief/20');
        }
        $categories = Category::all();
        $categoriesList = array();
        foreach ($categories as $category) {
            $categoriesList[$category->id] = $category;
        }

        return compact('categories');
    }

    public function getpitchdata()
    {
        if (isset($this->request->query['pitch_id'])) {
            $id = $this->request->query['pitch_id'];
        } else {
            $id = $this->request->data['pitch_id'];
        }
        if (!empty($id) && ($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $id), 'with' => array('Category'))))) {
            $res = $pitch->pitchData();
            $needRatingPopup = false;
            if($pitch->category_id != 20) {
                $needRatingPopup = $pitch->ratingPopup($res['avgArray']);
            }
            $res['needRatingPopup'] = $needRatingPopup;
            $res['needWinnerPopup'] = $pitch->winnerPopup();
            $res['type'] = $pitch->type;
            return $res;
        }

        return false;
    }

    public function apiPitchData()
    {
        if (!empty($this->request->query['pitch_id']) && ($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->query['pitch_id']), 'with' => array('Category'))))) {
            return $_GET['callback'].'('.json_encode($pitch->pitchData()).')';
        }

        return false;
    }

    public function fillbrief()
    {
        Session::write('fillbrief', true);

        return $this->redirect('/pitches/create');
    }

    /**
     * Метод для оотображение страницы редактирования неоплаченного проекта.
     *
     * @return array|object
     */
    public function brief()
    {
        $referal = 0;
        $referalId = 0;
        if (isset($_COOKIE['ref']) && ($_COOKIE['ref'] != '')) {
            $referal = REFERAL_DISCOUNT;
            $referalId = $_COOKIE['ref'];
        }
        if (!$this->request->category) {
            return $this->redirect('Pitches::create');
        }
        if ($category = Category::first($this->request->category)) {
            $experts = Expert::all(array('order' => array('id' => 'asc')));
            $promocode = Session::read('promocode');
            //$gatracking = new \Racecore\GATracking\GATracking('UA-9235854-5');
            //$event = $gatracking->createTracking('Event');
            //$event->setEventCategory('Создание проекта');
            //$event->setEventAction('Пользователь выбрал категорию «' . $category->title . '»');
            //$gatracking->sendTracking($event);
            if ($category->id != 20) {
                return compact('category', 'experts', 'referal', 'referalId', 'promocode');
            } else {
                $value = 9000;
                if (isset($this->request->query['reward'])) {
                    $value = (int) $this->request->query['reward'];
                }
                $receipt = array(
                    array(
                        'name' => 'Награда Дизайнеру',
                        'value' => $value,
                    ),
                );
                $defaultTitle = '';
                if (isset($this->request->query['title'])) {
                    $defaultTitle = $this->request->query['title'];
                }
                $defaultFinishDate = date('Y-m-d H:i:s', time() + (10 * DAY));
                if ((isset($this->request->query['date'])) && (!empty($this->request->query['date']))) {
                    $defaultFinishDate = $this->request->query['date'];
                }
                $defaultChooseWinnerFinishDate = date('Y-m-d H:i:s', strtotime($defaultFinishDate) + (4 * DAY));
                $plan = $this->userHelper->getCurrentPlanData();
                $balance = $this->userHelper->getBalance();
                $expirationDate = $this->userHelper->getSubscriptionExpireDate('d.m.Y');

                return $this->render(array(
                    'template' => '../pitches/subscribed_project',
                    'data' => compact('expirationDate', 'balance', 'plan', 'category', 'experts', 'referal', 'referalId', 'promocode', 'receipt', 'defaultTitle', 'defaultFinishDate', 'defaultChooseWinnerFinishDate'), ));
            }
        }

        return $this->redirect('Pitches::create');
    }

    public function add_subscribed()
    {
        $result = array('error' => 'no data provided');
        if ($this->request->data) {
            $userId = $this->userHelper->getId();
            $actionType = $this->request->data['actionType'];
            $this->request->data['commonPitchData']['user_id'] = $userId;
            $gatracking = new \Racecore\GATracking\GATracking('UA-9235854-5');
            $gaId = $gatracking->getClientId();
            $this->request->data['commonPitchData']['ga_id'] = $gaId;
            $result = Pitch::saveDraft($this->request->data);
            if (is_null($result)) {
                $result = array('error' => 'save error');
            } else {
                $projectId = $result;
                $result = array('success' => $result);
                Receipt::updateOrCreateReceiptForProject($projectId, $this->request->data['receipt']);
                if ($actionType === 'pay') {
                    $total = Receipt::getTotalForProject($projectId);
                    $paymentResult = User::reduceBalance($userId, (int) $total);
                    if (!$paymentResult) {
                        $result = array(
                            'error' => 'need to fill balance',
                            'needToFillAmount' => (int) ($total - User::getBalance($userId)),
                        );
                    } else {
                        $operationResult = Pitch::activate($projectId);
                        $result['operationResult'] = $operationResult;
                        $result['redirect'] = '/pitches/details/'.$projectId;
                    }
                }
            }
        }

        return $result;
    }

    public function add()
    {
        if ($this->request->data) {
            $featuresData = $this->request->data['features'];
            $commonPitchData = $this->request->data['commonPitchData'];
            if ((int) $featuresData['award'] == 0) {
                return 'noaward';
            }
            if((int) $featuresData['award'] < Pitch::getMinimalAwardForCategoryForDate($commonPitchData['category_id'], new \DateTime())) {
                return 'lowaward';
            }
            if (!isset($commonPitchData['id'])) {
                $commonPitchData['id'] = 0;
            }
            if (!isset($commonPitchData['materials'])) {
                $commonPitchData['materials'] = 0;
                $commonPitchData['materials-limit'] = '';
            }
            if (!isset($commonPitchData['fileFormats'])) {
                $commonPitchData['fileFormats'] = array();
            }
            $specificPitchData = $this->request->data['specificPitchData'];
            $pinned = $private = $social = $email = $brief = $timelimit = 0;
            $freePinned = false;
            $promocode = '';
            $codes = array();
            if ((isset($commonPitchData['promocode'])) && (!empty($commonPitchData['promocode']))) {
                foreach ($commonPitchData['promocode'] as $promocode) {
                    if ($code = Promocode::first(array('conditions' => array('code' => $promocode)))) {
                        if ($code->type == 'pinned') {
                            $freePinned = true;
                            $promocode = '';
                        }
                        if ($code->type == 'misha') {
                            $freePinned = true;
                        }
                        $codes[] = $code;
                    }
                }
            }

            if (($featuresData['pinned'] > 0) || ($freePinned == true)) {
                $pinned = 1;
            }
            if ($featuresData['private'] > 0) {
                $private = 1;
            }
            if ($featuresData['social'] > 0) {
                $social = 1;
            }
            if ($featuresData['email'] > 0) {
                $email = 1;
            }
            if ($featuresData['brief'] > 0) {
                $brief = 1;
            }
            if (!empty($commonPitchData['phone-brief'])) {
                //$brief = 1;
            }
            if ((isset($featuresData['guaranteed'])) && ($featuresData['guaranteed'] > 0)) {
                $guaranteed = 1;
            } else {
                $guaranteed = 0;
            }
            if ($featuresData['timelimitOption'] > 0) {
                $timelimit = abs(intval($featuresData['timelimitOption']));
                $validValues = array(0, 1, 2, 3, 4);
                if (in_array($timelimit, $validValues)) {
                    $timelimit = $timelimit;
                } else {
                    $timelimit = 0;
                }
            }
            if (!isset($featuresData['experts'])) {
                $expert = 0;
                $expertId = serialize(array());
            } else {
                $expert = 1;
                $expertId = serialize($featuresData['experts']);
            }
            if (!isset($commonPitchData['filesId'])) {
                $commonPitchData['filesId'] = array();
            }
            $redirect = false;
            $edit = false;

            if ($commonPitchData['id']) {
                $edit = true;
                $pitch = Pitch::first($commonPitchData['id']);
                if ($pitch->billed == 1) {
                    if($pitch->category_id == 20) {
                        if($pitch->isSubscriberProjectForCopyrighting()) {
                            $specificPitchData['isCopyrighting'] = 'true';
                        }else {
                            $specificPitchData['isCopyrighting'] = 'false';
                        }
                    }
                    $data = array(
                        'title' => $commonPitchData['title'],
                        'industry' => serialize($commonPitchData['jobTypes']),
                        'description' => $commonPitchData['description'],
                        'materials' => $commonPitchData['materials'],
                        'materials-limit' => $commonPitchData['materials-limit'],
                        'fileFormats' => serialize($commonPitchData['fileFormats']),
                        'fileFormatDesc' => $commonPitchData['fileFormatDesc'],
                        'filesId' => serialize($commonPitchData['filesId']),
                        'specifics' => serialize($specificPitchData),
                    );
                } else {
                    $data = array(
                        'title' => $commonPitchData['title'],
                        'industry' => serialize($commonPitchData['jobTypes']),
                        'description' => $commonPitchData['description'],
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
                        'promocode' => $promocode,
                    );
                }
            } else {
                $userId = Session::read('user.id');

                if (is_null($userId)) {
                    $userId = 0;
                    $redirect = true;
                    $referalCheck = true;
                } else {
                    $referalCheck = (User::isReferalAllowed($userId) === 0) ? true : false; // === is important!
                }

                $referalSum = 0;
                if (isset($commonPitchData['referalDiscount']) && !empty($commonPitchData['referalDiscount']) && $referalCheck) {
                    $referalSum = REFERAL_DISCOUNT;
                }

                $referalId = 0;
                if (isset($commonPitchData['referalId']) &&
                        !empty($commonPitchData['referalId']) &&
                        !empty($referalSum) &&
                        ($referalUser = User::first(array(
                            'conditions' => array(
                                'id' => array(
                                    '!=' => $userId,
                                ),
                                'referal_token' => $commonPitchData['referalId'],
                    ), )))) {
                    $referalId = $referalUser->id;
                    setcookie('ref', '', time() - 3600, '/');
                } else {
                    $referalSum = 0;
                }

                $gatracking = new \Racecore\GATracking\GATracking('UA-9235854-5');
                $gaId = $gatracking->getClientId();

                $data = array(
                    'user_id' => $userId,
                    'category_id' => $commonPitchData['category_id'],
                    'title' => $commonPitchData['title'],
                    'industry' => serialize($commonPitchData['jobTypes']),
                    'started' => date('Y-m-d H:i:s'),
                    'ideas_count' => 0,
                    'price' => $featuresData['award'],
                    'description' => $commonPitchData['description'],
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
                    'promocode' => $promocode,
                    'referal' => $referalId,
                    'referal_sum' => $referalSum,
                    'ga_id' => $gaId
                );
            }
            if (!$pitch = Pitch::first(array('conditions' => array('id' => $commonPitchData['id'])))) {
                $pitch = Pitch::create();
                Lead::resetLeadForUser($userId);
            }
            $pitch->set($data);
            if ($pitch->save()) {
                if (($edit == true) && ($pitch->published == 1) && ($pitch->status != 2)) {
                    $message = 'Друзья, в брифе возникли изменения, и мы убедительно просим вас с ними ознакомиться.';
                    $admin = $admin = User::getAdmin();
                    $data = array('pitch_id' => $pitch->id, 'reply_to' => 0, 'user_id' => $admin, 'text' => $message, 'public' => 1);
                    Comment::createComment($data);
                }

                if (!empty($codes)) {
                    foreach ($codes as $code) {
                        $code->pitch_id = $pitch->id;
                        $code->save();
                    }
                    Session::delete('promocode');
                }
            }

            $this->request->data['commonPitchData']['id'] = $pitch->id;
            // Receipt here
            if ($pitch->billed == 0) {
                if ($pitch->promocode != '') {
                    $this->request->data['commonPitchData']['promocode'] = $pitch->promocode;
                }
                if ($pitch->referal_sum > 0) {
                    $this->request->data['commonPitchData']['referalDiscount'] = $pitch->referal_sum;
                }
                Receipt::createReceipt($this->request->data);
                $total = Receipt::getTotalForProject($pitch->id);
                $pitch->total = $total;
            }
            $pitch->save();
            Logger::write('debug', serialize($pitch->data()));
            if ($redirect == true) {
                Session::write('temppitch', $pitch->id);

                return 'redirect';
            }
            Session::write('unpublished.pitch', $pitch->id);

            return $pitch->id;
        }
    }

    public function edit()
    {
        if (isset($this->request->id) && (is_numeric($this->request->id)) && ($pitch = Pitch::first($this->request->id)) && (($this->userHelper->isPitchOwner($pitch->user_id)) || ($this->userHelper->isAdmin()))) {
            $category = Category::first($pitch->category_id);
            $files = array();
            if (count(unserialize($pitch->filesId)) > 0) {
                $files = Pitchfile::all(array('conditions' => array('id' => unserialize($pitch->filesId))));
            }
            $codes = Promocode::all(array('conditions' => array('pitch_id' => $pitch->id)));
            $experts = Expert::all(array('order' => array('id' => 'asc')));
            // Referal correction
            if (!empty($pitch->referal)) {
                if ((User::isReferalAllowed($pitch->user_id) != 1) || (false == Pitch::isReferalAllowed($pitch))) {
                    $receiptComission = Receipt::first(array('conditions' => array('pitch_id' => $pitch->id, 'name' => array('LIKE' => '%Сбор%'))));
                    $receiptComission->value += $pitch->referal_sum;
                    $receiptComission->save();
                    $pitch->referal = 0;
                    $pitch->referal_sum = 0;
                    $pitch->total = Receipt::getTotalForProject($pitch->id);
                    $pitch->save();
                }
            }
            if (($category->id != 20) || ($category->id == 20 && $pitch->billed == 1)) {
                return compact('pitch', 'category', 'files', 'experts', 'codes');
            } else {
                $receipt = Receipt::exportToArray($pitch->id);
                $defaultTitle = $pitch->title;
                $defaultFinishDate = $pitch->finishDate;
                $defaultChooseWinnerFinishDate = $pitch->chooseWinnerFinishDate;
                $plan = $this->userHelper->getCurrentPlanData();
                $balance = $this->userHelper->getBalance();
                $expirationDate = $this->userHelper->getSubscriptionExpireDate('d.m.Y');

                return $this->render(array(
                    'template' => '../pitches/subscribed_project',
                    'data' => compact('pitch', 'files', 'expirationDate', 'balance', 'plan', 'category', 'experts', 'referal', 'referalId', 'promocode', 'receipt', 'defaultTitle', 'defaultFinishDate', 'defaultChooseWinnerFinishDate'), ));
            }
        }
        return $this->redirect('/pitches');
    }

    public function printpitch()
    {
        if ($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) {
            $currentUser = Session::read('user.id');
            if (($pitch->published == 0) && (($currentUser != $pitch->user_id) && ($currentUser['isAdmin'] != 1) && (!in_array($currentUser['id'], User::$admins)))) {
                return $this->redirect('/pitches');
            }
            if ($pitch->private == 1) {
                if (($pitch->user_id != Session::read('user.id')) && (!in_array(Session::read('user.id'), User::$admins)) && (!$isExists = Request::first(array('conditions' => array('user_id' => Session::read('user.id'), 'pitch_id' => $pitch->id))))) {
                    return $this->redirect('/requests/sign/'.$pitch->id);
                }
            }
            $pitch->views += 1;
            $pitch->save();

            $fileIds = unserialize($pitch->filesId);
            $files = array();
            if (!empty($fileIds)) {
                $files = Pitchfile::all(array('conditions' => array('id' => $fileIds)));
            }

            return $this->render(array('layout' => 'print', 'data' => compact('pitch', 'files')));
        }
    }

    public function view()
    {
        if ($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) {
            /*if(($pitch->status == 1) && ($pitch->awarded != 0) && ($this->userHelper->isPitchOwner($pitch->user_id))) {
                $winningSolution = Solution::first($pitch->awarded);
                if($winningSolution->step > 2) {
                    $closingStep = $winningSolution->step;
                }else{
                    $closingStep = 2;
                }
                return $this->redirect("/users/step$closingStep/$winningSolution->id");
            }*/
            $nameInflector = new NameInflector();
            $avatarHelper = new AvatarHelper();
            $autosuggestUsers = [];
            $client = User::first($pitch->user_id);
            $client->visualName = $this->userHelper->getFormattedName($client->first_name, $client->last_name, false);
            if (($client->is_company == 1) && ($client->short_company_name != '')) {
                $client->visualName = $client->short_company_name;
            }
            $autosuggestUsers[] = [
                'id' => $client->id,
                'avatar' => $avatarHelper->show($client->data(), false, true),
                'name' => $client->visualName,
            ];
            $adminGo = User::first(108);
            $autosuggestUsers[] = [
                'id' => $adminGo->id,
                'avatar' => $avatarHelper->show($adminGo->data(), false, true),
                'name' => $nameInflector->renderName($adminGo->first_name, $adminGo->last_name, false),
            ];
            $limit = $limitSolutions = 36; // Set this to limit solutions per page
            $offset = 0;
            if (isset($this->request->query['count'])) {
                $offset = (int) $this->request->query['count'];
                $limit = (isset($this->request->query['rest'])) ? 9999 : $limitSolutions;
            }
            $currentUser = Session::read('user');
            if (($pitch->published == 0) && (($currentUser['id'] != $pitch->user_id) && ($currentUser['isAdmin'] != 1) && (!in_array($currentUser['id'], User::$admins)))) {
                return $this->redirect('/pitches');
            }
            if ($pitch->private == 1) {
                if (($pitch->user_id != $currentUser['id']) && (!in_array($currentUser['id'], User::$admins)) && (!$isExists = Request::first(array('conditions' => array('user_id' => $currentUser['id'], 'pitch_id' => $pitch->id))))) {
                    return $this->redirect('/requests/sign/'.$pitch->id);
                }
            }

            $canViewPrivate = false;
            if (User::getAwardedSolutionNum($currentUser['id']) >= WINS_FOR_VIEW) {
                $canViewPrivate = true;
            }
            $sort = $pitch->getSolutionsSortName($this->request->query);
            $order = $pitch->getSolutionsSortingOrder($this->request->query);

            $solutions = Solution::all(array('conditions' => array('pitch_id' => $this->request->id), 'with' => array('User'), 'order' => $order, 'limit' => $limit, 'offset' => $offset));

            if ($solutions) {
                foreach ($solutions as $solution) {
                    $autosuggestUsers[] = array(
                        'id' => $solution->user->id,
                        'avatar' => $avatarHelper->show($solution->user->data(), false, true),
                        'name' => $nameInflector->renderName($solution->user->first_name, $solution->user->last_name, false),
                    );
                }
            }
            $solutionsCount = Solution::find('count', array('conditions' => array('pitch_id' => $this->request->id)));
            $pitch->applicantsCount = Solution::find('count', array('conditions' => array('pitch_id' => $this->request->id), 'fields' => array('distinct(user_id)')));
            $selectedsolution = false;
            $nominatedSolutionsOfThisPitch = Solution::all(array(
                        'conditions' => array('OR' => array('awarded' => 1, 'nominated' => 1), 'pitch_id' => $pitch->id),
            ));
            $winnersUserIds = array();
            if (($nominatedSolutionsOfThisPitch) && (count($nominatedSolutionsOfThisPitch) > 0)) {
                $selectedsolution = true;
                foreach($nominatedSolutionsOfThisPitch as $nominatedSolutionOfThisPitch) {
                    if (!in_array($nominatedSolutionOfThisPitch->user_id, $winnersUserIds)) {
                        $winnersUserIds[] = $nominatedSolutionOfThisPitch->user_id;
                    }
                }
            }

            if ((!$currentUser['id'] && ($pitch->blank == 1)) || (($pitch->blank == 1) && ($currentUser['id'] != $pitch->user_id && !in_array($currentUser['id'], $winnersUserIds)))) {
                return $this->redirect('/pitches');
            }

            if (Pitch::isReadyForLogosale($pitch)) {
                $userHelper = new UserHelper(array());
                if ($userHelper->isLoggedIn()) {
                    $data = Solution::addBlankPitchForLogosale($userHelper->getId(), 0);
                }
            }

            $disableUpload = false;
            if(($this->userHelper->isLoggedIn()) && ($moderation = Moderation::first(['conditions' => [
                'model_user' => $this->userHelper->getId(),
                'pitch_id' => $pitch->id
            ]]))) {
                $disableUpload = true;
            }
            $pitch->canManageRating = false;
            if (((int) $pitch->category_id === 20)
                && (Manager::getTeamLeaderOfManager($this->userHelper->getId()) === (int) $pitch->user_id)
                && (Manager::isManagerAssignedToProject((int) $this->userHelper->getId(), (int) $pitch->id))) {
                $pitch->canManageRating = true;
            }
            $experts = Expert::all(array('conditions' => array('Expert.user_id' => array('>' => 0))));
            $pitchesCount = Pitch::getCountBilledMultiwinner($pitch->id);
            if (is_null($this->request->env('HTTP_X_REQUESTED_WITH')) || isset($this->request->query['fromTab'])) {
                $freePitch = Pitch::getFreePitch();
                return compact('pitch', 'solutions', 'selectedsolution', 'sort', 'order', 'experts', 'canViewPrivate', 'solutionsCount', 'limitSolutions', 'freePitch', 'pitchesCount', 'winnersUserIds', 'data', 'autosuggestUsers', 'disableUpload');
            } else {
                if (isset($this->request->query['count'])) {
                    return $this->render(array('layout' => false, 'template' => '../elements/gallery', 'data' => compact('pitch', 'solutions', 'selectedsolution', 'sort', 'experts', 'canViewPrivate', 'solutionsCount', 'winnersUserIds', 'autosuggestUsers', 'disableUpload')));
                }

                return $this->render(array('layout' => false), compact('pitch', 'solutions', 'selectedsolution', 'sort', 'experts', 'canViewPrivate', 'solutionsCount', 'winnersUserIds', 'autosuggestUsers', 'disableUpload'));
            }
        }
        throw new Exception('Public:Такого проекта не существует.', 404);
    }

    public function getCommentsNew()
    {
        if (!$this->request->is('json')) {
            return $this->redirect('/pitches');
        }
        if ($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id)))) {
            $currentUser = Session::read('user');
            $isUserClient = ($currentUser['id'] == $pitch->user_id) ? true : false;
            $isUserAdmin = (($currentUser['isAdmin'] == 1) || User::checkRole('admin')) ? true : false;
            if (($pitch->published == 0) && (false == $isUserClient) && (false == $isUserAdmin)) {
                return false;
            }
            $pitch->manager_id = 0;
            if(Manager::isManagerAssignedToProject($this->userHelper->getId(), $pitch->id)) {
                $pitch->manager_id = $this->userHelper->getId();
            }
            $experts = Expert::getExpertUserIds();

            // Fetch Top Level Comments
            //$cacheKey = 'commentsraw_'.$pitch->id;
            //if (!$commentsRaw = Rcache::read($cacheKey)) {
                $commentsRaw = Comment::all(array(
                            'conditions' => array(
                                'pitch_id' => $pitch->id,
                                'question_id' => 0,
                            ),
                            'order' => array('Comment.created' => 'desc'),
                            'with' => array('User'), ));
                //Rcache::write($cacheKey, $commentsRaw, array(), '+4 hours');
            //}
            $comments = Comment::filterCommentsTree($commentsRaw, $pitch->user_id);
            foreach ($comments as $comment) {
                if(in_array($comment->user_id, $experts)) {
                    $expert = Expert::first(['conditions' => ['user_id' => $comment->user_id]]);
                    switch($expert->id):
                        case 1: $image = '/img/experts/fedchenko.jpg'; break;
                        case 3: $image = '/img/experts/kojara.jpg'; break;
                        case 2: $image = '/img/experts/pavlov.jpg'; break;
                        case 4: $image = '/img/experts/chern.jpg'; break;
                        case 5: $image = '/img/experts/nesterenko218.jpg'; break;
                        case 6: $image = '/img/experts/efremov218.jpg'; break;
                        case 7: $image = '/img/experts/percia_218.png'; break;
                        case 8: $image = '/img/experts/makarov_dmitry.png'; break;
                    endswitch;
                    $comment->expertAvatar = $image;
                }
                $comment->user = User::removeExtraFields($comment->user);
            }

            return compact('comments', 'experts', 'pitch');
        } else {
            return false;
        }
    }

    public function robots()
    {
        $pitches = Pitch::all(array('conditions' => array('private' => 1)));
        $text = 'User-agent: *';
        foreach ($pitches->data() as $pitch):
            $text .= '
Disallow: /pitches/view/'.$pitch['id'].'
Disallow: /pitches/details/'.$pitch['id'].'
Disallow: /pitches/designers/'.$pitch['id'].'
Disallow: /pitches/printpitch/'.$pitch['id'].'
Disallow: /pitches/upload/'.$pitch['id'];
        endforeach;
        file_put_contents(LITHIUM_APP_PATH.'/webroot/robots.txt', $text);
        die();
    }

    public function details()
    {
        if ($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) {
            $allpitches = Pitch::all(array('conditions' => array('status' => 0, 'published' => 1), 'order' => array(
                            'price' => 'desc',
                            'started' => 'desc',
            )));
            $first = null;
            $flag = false;
            $i = 0;
            $count = count($allpitches);
            foreach ($allpitches as $cpitch) {
                if ($i == 0) {
                    $first = $cpitch;
                }

                if ($flag == true) {
                    $prevpitch = $cpitch;
                    break;
                }
                if ($cpitch->id == $pitch->id) {
                    $flag = true;
                    if (($count - 1) == $i) {
                        $prevpitch = $first;
                    }
                }
                ++$i;
            }
            $nameInflector = new NameInflector();
            $avatarHelper = new AvatarHelper();
            $autosuggestUsers = [];
            $client = User::first($pitch->user_id);
            $client->visualName = $this->userHelper->getFormattedName($client->first_name, $client->last_name, false);
            if (($client->is_company == 1) && ($client->short_company_name != '')) {
                $client->visualName = $client->short_company_name;
            }
            $autosuggestUsers[] = [
                'id' => $client->id,
                'avatar' => $avatarHelper->show($client->data(), false, true),
                'name' => $client->visualName,
            ];
            $adminGo = User::first(108);
            $autosuggestUsers[] = [
                'id' => $adminGo->id,
                'avatar' => $avatarHelper->show($adminGo->data(), false, true),
                'name' => $nameInflector->renderName($adminGo->first_name, $adminGo->last_name, false),
            ];

            $currentUser = Session::read('user.id');
            if (($pitch->published == 0) && ((!$this->userHelper->isPitchOwner($pitch->user_id)) && (!$this->userHelper->isAdmin()))) {
                return $this->redirect('/pitches');
            }
            if ($pitch->private == 1) {
                if (($pitch->user_id != Session::read('user.id')) && (!in_array(Session::read('user.id'), User::$admins)) && (!$isExists = Request::first(array('conditions' => array('user_id' => Session::read('user.id'), 'pitch_id' => $pitch->id))))) {
                    return $this->redirect('/requests/sign/'.$pitch->id);
                }
            }
            $nominatedSolutionOfThisPitch = Solution::first(array(
                'conditions' => array('OR' => array('awarded' => 1, 'nominated' => 1), 'pitch_id' => $pitch->id),
            ));
            $winnersUserIds = array();
            if ($nominatedSolutionOfThisPitch) {
                if (!in_array($nominatedSolutionOfThisPitch->user_id, $winnersUserIds)) {
                    $winnersUserIds[] = $nominatedSolutionOfThisPitch->user_id;
                }
            }
            if ((!$this->userHelper->isLoggedIn() && ($pitch->blank == 1)) || (($pitch->blank == 1) && ($this->userHelper->getId() != $pitch->user_id && $this->userHelper->getId() != $nominatedSolutionOfThisPitch->user_id))) {
                return $this->redirect('/pitches');
            }
            $pitch->views += 1;
            $pitch->save();

            $pitch->applicantsCount = Solution::find('count', array('conditions' => array('pitch_id' => $this->request->id), 'fields' => array('distinct(user_id)')));
            $fileIds = unserialize($pitch->filesId);
            $files = array();
            $comments = Comment::all(array('conditions' => array('pitch_id' => $this->request->id), 'order' => array('Comment.created' => 'desc'), 'with' => array('User')));
            if (!empty($fileIds)) {
                $files = Pitchfile::all(array('conditions' => array('id' => $fileIds)));
            }
            $rating = Pitchrating::getRating($currentUser, $pitch->id);
            $solutions = Solution::all(array('conditions' => array('pitch_id' => $this->request->id), 'with' => array('User')));
            if ($solutions) {
                foreach ($solutions as $solution) {
                    $autosuggestUsers[] = array(
                        'id' => $solution->user->id,
                        'avatar' => $avatarHelper->show($solution->user->data(), false, true),
                        'name' => $nameInflector->renderName($solution->user->first_name, $solution->user->last_name, false),
                    );
                }
            }
            if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
                return compact('pitch', 'files', 'comments', 'prevpitch', 'solutions', 'experts', 'rating', 'winnersUserIds', 'autosuggestUsers');
            } else {
                return $this->render(array('layout' => false, 'data' => compact('pitch', 'files', 'comments', 'prevpitch', 'rating', 'winnersUserIds', 'autosuggestUsers')));
            }
        }
        throw new Exception('Public:Такого проекта не существует.', 404);
    }

    public function designers()
    {
        if ($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) {
            $limit = $limitDesigners = 6; // Set this to limit of designers per page
            $offset = 0;
            $search = '';
            if (isset($this->request->query['count'])) {
                $offset = (int) $this->request->query['count'];
                $limit = (isset($this->request->query['rest'])) ? 9999 : $limitDesigners;
            }

            $currentUser = Session::read('user.id');
            if (($pitch->published == 0) && (($currentUser != $pitch->user_id) && ($currentUser['isAdmin'] != 1) && (!in_array($currentUser['id'], User::$admins)))) {
                return $this->redirect('/pitches');
            }
            if ($pitch->private == 1) {
                if (($pitch->user_id != Session::read('user.id')) && (!in_array(Session::read('user.id'), User::$admins)) && (!$isExists = Request::first(array('conditions' => array('user_id' => Session::read('user.id'), 'pitch_id' => $pitch->id))))) {
                    return $this->redirect('/requests/sign/'.$pitch->id);
                }
            }
            $nominatedSolutionOfThisPitch = Solution::first(array(
                'conditions' => array('OR' => array('awarded' => 1, 'nominated' => 1), 'pitch_id' => $pitch->id),
            ));
            $winnersUserIds = array();
            if ($nominatedSolutionOfThisPitch) {
                if (!in_array($nominatedSolutionOfThisPitch->user_id, $winnersUserIds)) {
                    $winnersUserIds[] = $nominatedSolutionOfThisPitch->user_id;
                }
            }
            if ((!$this->userHelper->getId() && ($pitch->blank == 1)) || (($pitch->blank == 1) && ($this->userHelper->getId() != $pitch->user_id && $this->userHelper->getId() != $nominatedSolutionOfThisPitch->user_id))) {
                return $this->redirect('/pitches');
            }
            $canViewPrivate = false;
            if (($this->userHelper->getId()) && (User::getAwardedSolutionNum($this->userHelper->getId()) >= WINS_FOR_VIEW)) {
                $canViewPrivate = true;
            }

            $fromDesignersTab = true;

            $pitch->applicantsCount = Solution::find('count', array('conditions' => array('pitch_id' => $this->request->id), 'fields' => array('distinct(user_id)')));

            $designersCount = $pitch->applicantsCount;

            $sort = $pitch->getSolutionsSortName($this->request->query);
            $order = $pitch->getDesignersSortingOrder($this->request->query);

            $query = array(
                'conditions' => array(
                    'pitch_id' => $this->request->id,
                ),
                'fields' => array('user_id', 'COUNT(user_id) as Num'),
                'group' => array('user_id'),
                'order' => $order,
                'with' => array('User'),
            );

            if (isset($this->request->query['search'])) {
                $search = urldecode(filter_var($this->request->query['search'], FILTER_SANITIZE_STRING));
                $words = explode(' ', $search);
                foreach ($words as $index => &$searchWord) {
                    if ($searchWord == '') {
                        unset($words[$index]);
                        continue;
                    }
                    $searchWord = mb_eregi_replace('[^A-Za-z0-9а-яА-Я]', '', $searchWord);
                    $searchWord = trim($searchWord);
                }
                if (count($words) == 1) {
                    $query['conditions']['User.first_name'] = array('LIKE' => '%'.$words[0].'%');
                } else {
                    $query['conditions']['User.first_name'] = array('LIKE' => '%'.$words[0].'%');
                    $query['conditions']['User.last_name'] = array('LIKE' => mb_substr($words[1], 0, 1, 'UTF-8').'%');
                }
                $distincts = Solution::all($query);
                $designersCount = count($distincts);
            } else {
                $distincts = Solution::all($query);
            }

            $designers = new \lithium\util\Collection();
            $o = 0;
            $l = 0;
            foreach ($distincts as $item) {
                ++$o;
                if ($o <= $offset) {
                    continue;
                }
                $item->user = User::first($item->{'user_id'});
                $item->solutions = Solution::all(array('conditions' => array('user_id' => $item->user->id, 'pitch_id' => $this->request->id), 'order' => array('created' => 'desc')));
                $designers->append($item);
                ++$l;
                if ($l == $limit) {
                    break;
                }
            }

            $comments = '';
            //$comments = Comment::all(array('conditions' => array('pitch_id' => $this->request->id), 'order' => array('Comment.created' => 'desc'), 'with' => array('User')));
            $nameInflector = new NameInflector();
            $avatarHelper = new AvatarHelper();
            $autosuggestUsers = [];
            $client = User::first($pitch->user_id);
            $client->visualName = $this->userHelper->getFormattedName($client->first_name, $client->last_name, false);
            if (($client->is_company == 1) && ($client->short_company_name != '')) {
                $client->visualName = $client->short_company_name;
            }
            $autosuggestUsers[] = [
                'id' => $client->id,
                'avatar' => $avatarHelper->show($client->data(), false, true),
                'name' => $client->visualName,
            ];
            $adminGo = User::first(108);
            $autosuggestUsers[] = [
                'id' => $adminGo->id,
                'avatar' => $avatarHelper->show($adminGo->data(), false, true),
                'name' => $nameInflector->renderName($adminGo->first_name, $adminGo->last_name, false),
            ];
            $solutions = Solution::all(array('conditions' => array('pitch_id' => $this->request->id), 'with' => array('User'), 'order' => $order, 'limit' => $limit, 'offset' => $offset));

            if ($solutions) {
                foreach ($solutions as $solution) {
                    $autosuggestUsers[] = array(
                        'id' => $solution->user->id,
                        'avatar' => $avatarHelper->show($solution->user->data(), false, true),
                        'name' => $nameInflector->renderName($solution->user->first_name, $solution->user->last_name, false),
                    );
                }
            }
            if (is_null($this->request->env('HTTP_X_REQUESTED_WITH')) || isset($this->request->query['fromTab'])) {
                return compact('pitch', 'comments', 'sort', 'canViewPrivate', 'limitDesigners', 'designers', 'designersCount', 'fromDesignersTab', 'search', 'winnersUserIds', 'autosuggestUsers');
            } else {
                if (isset($this->request->query['count']) || isset($this->request->query['search'])) {
                    return $this->render(array('layout' => false, 'template' => '../elements/designers', 'data' => compact('pitch', 'comments', 'sort', 'canViewPrivate', 'designers', 'designersCount', 'fromDesignersTab', 'search', 'winnersUserIds', 'autosuggestUsers')));
                }

                return $this->render(array('layout' => false, 'data' => compact('pitch', 'comments', 'sort', 'canViewPrivate', 'designers', 'designersCount', 'fromDesignersTab', 'search', 'winnersUserIds', 'autosuggestUsers')));
            }
        }
        throw new Exception('Public:Такого проекта не существует.', 404);
    }

    public function crowdsourcing()
    {
        $pitches = Pitch::all(array('conditions' => array('status' => array('<' => 1), 'Pitch.awarded' => 0, 'published' => 1), 'order' => array('started' => 'desc'), 'with' => array('User')));
        foreach ($pitches as $pitch) {
            $solution = Solution::first(array('conditions' => array(
                            'pitch_id' => $pitch->id,
                        ), 'order' => array('created' => 'desc')));
            $pitch->solution = $solution;
        }

        return compact('pitches');
    }

    /**
     * Метод отображения страницы решения html|json.
     *
     * @return array|object
     *
     * @throws \Exception
     */
    public function viewsolution()
    {
        if (($this->request->id) && ($solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('User', 'Pitch', 'Solutiontag'))))) {
            $pitch = Pitch::first(array('conditions' => array('Pitch.id' => $solution->pitch_id), 'with' => array('User')));
            if ($this->request->env('HTTP_X_REQUESTED_WITH')) {
                $solution->views = Solution::increaseView($this->request->id);
            }
            $data = null;
            if (Pitch::isReadyForLogosale($pitch) || (isset($this->request->query['exp'])) && ($this->request->query['exp'] == 1)) {
                $userHelper = new UserHelper(array());
                if ($userHelper->isLoggedIn()) {
                    $data = Solution::addBlankPitchForLogosale($userHelper->getId(), $solution->id);
                }
            }
            $pitch->user = User::removeExtraFields($pitch->user);
            $solution->user = User::removeExtraFields($solution->user);
            $solution->tags = Solution::getTagsArrayForSolution($solution);
            $sort = $pitch->getSolutionsSortName($this->request->query);
            $order = $pitch->getSolutionsSortingOrder($this->request->query);
            $briefHelper = new Brief();
            $solution->description = nl2br(trim($briefHelper->stripUrl($solution->description)));

            function getArrayNeighborsByKey($array, $findKey)
            {
                if (!array_key_exists($findKey, $array)) {
                    return false;
                }

                $select = $prevous = $next = null;

                foreach ($array as $key => $value) {
                    $thisValue = array($key => $value);
                    if ($key === $findKey) {
                        $select = key($thisValue);
                        continue;
                    }
                    if ($select !== null) {
                        $next = key($thisValue);
                        break;
                    }
                    $previous = key($thisValue);
                }
                $keys = array_keys($array);
                if (!isset($previous)) {
                    $previous = array_pop($keys);
                }
                if (!isset($next)) {
                    $next = array_shift($keys);
                }

                return array(
                    'prev' => $previous,
                    'current' => $select,
                    'next' => $next,
                );
            }

            $cacheKey = md5(serialize($order)).'_'.$solution->pitch_id.'_'.$solution->id;
            if (!$results = Rcache::read($cacheKey)) {
                $solutions = Solution::all(array(
                    'conditions' => array(
                        'pitch_id' => $solution->pitch_id,
                    ),
                    'order' => $order, )
                );
                $results = getArrayNeighborsByKey($solutions->data(), (int) $solution->id);
                Rcache::write($cacheKey, $results, array(), '+2 hours');
            }
            if ($this->request->is('json')) {
                $solutions = array();
            }
            $next = $results['next'];
            $prev = $results['prev'];
            $current = $results['current'];
            $comments = Comment::all(array('conditions' => array('pitch_id' => $solution->pitch->id, 'question_id' => 0), 'order' => array('Comment.id' => 'desc'), 'with' => array('User', 'Pitch')));
            $comments = Comment::filterComments($solution->num, $comments);
            $comments = Comment::filterCommentsTree($comments, $pitch->user_id);
            foreach ($comments as $comment) {
                $comment->user = User::removeExtraFields($comment->user);
            }
            $expertsIds = Expert::getExpertUserIds();
            if (isset($this->request->query['exp'])) {
                $comments = $comments->data();
                foreach ($comments as $k => $v) {
                    if (!in_array($v['user_id'], $expertsIds)) {
                        unset($comments[$k]);
                    }
                }
            }
            // Forbid Copywrited
            if ($pitch->isCopyrighting()) {
                if ((Session::read('user') == null) || ($solution->user_id != Session::read('user.id')) && (!in_array(Session::read('user.id'), $expertsIds)) && (!in_array(Session::read('user.id'), User::$admins)) && ($pitch->user_id != Session::read('user.id'))) {
                    return $this->redirect('/pitches/view/'.$pitch->id);
                }
            }
            $pitch->isCopywriting = $pitch->isCopyrighting();
            // Forbid Private
            if ($pitch->private == 1) {
                $canViewPrivate = false;
                $currentUser = Session::read('user');
                if (!empty($currentUser) && (User::getAwardedSolutionNum($currentUser['id']) >= WINS_FOR_VIEW)) {
                    $canViewPrivate = true;
                }
                if ((Session::read('user') == null) || ($solution->user_id != Session::read('user.id')) && (!in_array(Session::read('user.id'), $expertsIds)) && (!in_array(Session::read('user.id'), User::$admins)) && ($pitch->user_id != Session::read('user.id')) && !$canViewPrivate) {
                    return $this->redirect('/pitches/view/'.$pitch->id);
                }
            }
            $selectedsolution = false;
            $nominatedSolutionOfThisPitch = Solution::first(array(
                        'conditions' => array('OR' => array('awarded' => 1, 'nominated' => 1), 'pitch_id' => $pitch->id),
            ));
            if ($nominatedSolutionOfThisPitch) {
                $selectedsolution = true;
            }
            $userData = unserialize($solution->user->{'userdata'});
            $copyrightedInfo = unserialize($solution->copyrightedInfo);
            foreach ($copyrightedInfo['source'] as $key => $value) {
                $copyrightedInfo['source'][$key] = Url::getShortUrlFor($value);
            }
            $pitchesCount = Pitch::getCountBilledMultiwinner($pitch->id);
            $avatarHelper = new AvatarHelper();
            $userAvatar = $avatarHelper->show($solution->user->data(), false, true);
            $likes = false;
            if (Session::read('user')) {
                $like = Like::find('first', array('conditions' => array('solution_id' => $solution->id, 'user_id' => Session::read('user.id'))));
                if ($like) {
                    $likes = true;
                }
            } else {
                if (isset($_COOKIE['bmx_'.$solution->id]) && ($_COOKIE['bmx_'.$solution->id] == 'true')) {
                    $likes = true;
                }
            }
            $pitch->applicantsCount = Solution::find('count', array('conditions' => array('pitch_id' => $pitch->id), 'fields' => array('distinct(user_id)')));
            $experts = Expert::getExpertUserIds();

            $formatter = new MoneyFormatter();
            if (($pitch->status == 1) && ($pitch->awarded != 0) || ($pitch->status == 2)) {
                $pitchHelper = new \app\extensions\helper\Pitch();
                $description = $pitchHelper->getOpenGraphDescription($pitch);
                //var_dump($description);
                //die();
            } else {
                $description = mb_substr($pitch->description, 0, 150, 'UTF-8').((mb_strlen($pitch->description) > 150) ? '... ' : '. ').'Награда: '.$formatter->formatMoney($pitch->price, array('suffix' => ' рублей')).(($pitch->guaranteed == 1) ? ', гарантированы' : '');
            }
            $date = Solution::getCreatedDate($solution->id);
            $isSolutionReady = Solution::isReadyForLogosale($solution, $pitch);

            $nameInflector = new NameInflector();
            $avatarHelper = new AvatarHelper();
            $autosuggestUsers = [];
            $client = User::first($pitch->user_id);
            $client->visualName = $this->userHelper->getFormattedName($client->first_name, $client->last_name, false);
            if (($client->is_company == 1) && ($client->short_company_name != '')) {
                $client->visualName = $client->short_company_name;
            }
            $autosuggestUsers[] = [
                'id' => $client->id,
                'avatar' => $avatarHelper->show($client->data(), false, true),
                'name' => $client->visualName,
            ];
            $adminGo = User::first(108);
            $autosuggestUsers[] = [
                'id' => $adminGo->id,
                'avatar' => $avatarHelper->show($adminGo->data(), false, true),
                'name' => $nameInflector->renderName($adminGo->first_name, $adminGo->last_name, false),
            ];
            $autosuggestUsers[] = [
                'id' => $solution->user->id,
                'avatar' => $avatarHelper->show($solution->user->data(), false, true),
                'name' => $nameInflector->renderName($solution->user->first_name, $solution->user->last_name, false),
            ];
            $canViewFullImage = false;
            if(
                ($this->userHelper->isPitchOwner($pitch->user_id)) &&
                ($this->userHelper->isSubscriptionActive()) &&
                ($pitch->category_id == 20) &&
                (in_array($this->userHelper->read('user.subscription_status'), [2, 3]))
            ) {
                $canViewFullImage = true;
            }
            $pitch->canManageRating = false;
            if (((int) $pitch->category_id === 20)
                && (Manager::getTeamLeaderOfManager($this->userHelper->getId()) === (int) $solution->pitch->user_id)
                && (Manager::isManagerAssignedToProject((int) $this->userHelper->getId(), (int) $solution->pitch->id))) {
                $pitch->canManageRating = true;
            }
            return compact('pitch', 'solution', 'solutions', 'comments', 'prev', 'next', 'current', 'sort', 'selectedsolution', 'experts', 'userData', 'userAvatar', 'copyrightedInfo', 'likes', 'description', 'date', 'pitchesCount', 'data', 'isSolutionReady', 'experts', 'autosuggestUsers', 'canViewFullImage');
        } else {
            if($moderation = Moderation::first(['conditions' => [
                'model_id' => $this->request->id,
                'model' => '\app\models\Solution'
            ]])) {
                return $this->redirect('/users/view/' . $moderation->model_user);
            }else {
                throw new Exception('Public:Решение было удалено автором', 404);
            }
        }
    }

    /**
     * Метод загрузки нового решения html|json.
     *
     * @return array|object|string|void
     *
     * @throws Exception
     */
    public function upload()
    {
        \lithium\net\http\Media::type('json', array('text/html'));
        if (($this->request->id > 0) && ($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) && ($pitch->status == 0)) {
            if (($pitch->status != 0) || ($pitch->published != 1)) {
                $this->redirect(array('Pitches::view', 'id' => $pitch->id));
            }
            $currentUser = Session::read('user.id');
            if (($pitch->published == 0) && (($currentUser != $pitch->user_id) && (!in_array($currentUser, User::$admins)))) {
                return $this->redirect('/pitches');
            }

            if(($this->userHelper->isLoggedIn()) && ($moderation = Moderation::first(['conditions' => [
                    'model_user' => $this->userHelper->getId(),
                    'pitch_id' => $pitch->id
                ]]))) {
                $this->redirect(array('Pitches::view', 'id' => $pitch->id));
            }

            $userHelper = new UserHelper(array());
            if (($userHelper->designerTimeRemain($pitch)) or (Session::read('user.confirmed_email') == '0')) {
                return $this->redirect(array('Pitches::view', 'id' => $pitch->id));
            }

            if (($this->request->data)) {
                if ((isset($this->request->data['solution'])) && (is_array($this->request->data['solution'])) && ((isset($this->request->data['solution'][0]) || ($this->request->data['solution']['error'] == 0)))) {
                    $this->request->data['pitch_id'] = $this->request->id;
                    $this->request->data['user_id'] = Session::read('user.id');
                    $result = Solution::uploadSolution($this->request->data);
                    if ($result) {
                        return $this->render(array('data' => array('json' => $result->data())));
                    } else {
                        return false;
                    }
                } else {
                    return 'nofile';
                }
            }
            $pitch->applicantsCount = Solution::find('count', array('conditions' => array('pitch_id' => $this->request->id), 'fields' => array('distinct(user_id)')));
            if (!$pitch->isCopyrighting()) {
                $uploadnonce = Uploadnonce::getNonce();

                return compact('pitch', 'uploadnonce');
            } else {
                return $this->render(array('template' => '/upload-copy', 'data' => compact('pitch')));
            }
        }
        throw new Exception('Public:Такого проекта не существует.', 404);
    }

    public function uploadfile()
    {
        if (($this->request->id > 0) && ($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) && ($pitch->status == 0)) {
            $currentUser = Session::read('user.id');
            if (($pitch->published == 0) && (($currentUser != $pitch->user_id) && (!in_array($currentUser, User::$admins)))) {
                return $this->redirect('/pitches');
            }

            if (($this->request->data)) {
                if ((isset($this->request->data['solution']) && is_array($this->request->data['solution'])) && !empty($this->request->data['solution'])) {
                    $result = Uploadnonce::uploadFile($this->request->data);
                    if ($result) {
                        return json_encode('true'); //$this->render(array('data' => array('json' => $result->data())));
                    }

                    return json_encode('false');
                }

                return json_encode('nofile');
            }
        }
        throw new Exception('Public:Такого проекта не существует.', 404);
    }

    public function uploadData()
    {
        if (($this->request->id > 0) && ($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) && ($pitch->status == 0)) {
            $currentUser = Session::read('user.id');
            if (($pitch->published == 0) && (($currentUser != $pitch->user_id) && (!in_array($currentUser, User::$admins)))) {
                return $this->redirect('/pitches');
            }

            if (($this->request->data)) {
                $this->request->data['pitch_id'] = $this->request->id;
                $this->request->data['user_id'] = Session::read('user.id');
                $this->request->data['reSortable'] = explode(',', $this->request->data['reSortable']);
                $this->request->data['tags'] = explode(',', $this->request->data['tags']);
                $result = Solution::uploadSolution($this->request->data);
                if ($result) {
                    return $this->redirect('/pitches/view/'.$pitch->id);
                } else {
                    return false;
                }
            }
        }
        throw new Exception('Public:Такого проекта не существует.', 404);
    }

    /**
     * Метод загрузки решения для копирайтинга html|json.
     *
     * @return array|bool|object|void
     */
    public function uploadcopy()
    {
        if (($this->request->id > 0) && ($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) && ($pitch->status == 0)) {
            if (($pitch->status != 0) || ($pitch->published != 1)) {
                $this->redirect(array('Pitches::view', 'id' => $pitch->id));
            }
            if(($this->userHelper->isLoggedIn()) && ($moderation = Moderation::first(['conditions' => [
                    'model_user' => $this->userHelper->getId(),
                    'pitch_id' => $pitch->id
                ]]))) {
                $this->redirect(array('Pitches::view', 'id' => $pitch->id));
            }
            $currentUser = Session::read('user.id');
            if (($pitch->published == 0) && (($currentUser != $pitch->user_id) && (!in_array($currentUser, User::$admins)))) {
                return $this->redirect('/pitches');
            }
            if (($this->request->data)) {
                $this->request->data['pitch_id'] = $this->request->id;
                $this->request->data['user_id'] = Session::read('user.id');
                $result = Solution::uploadSolution($this->request->data);
                if ($result) {
                    return $result->data();
                } else {
                    return false;
                }
            }
            if ($pitch->category_id != 7) {
                return compact('pitch');
            } else {
                return $this->render(array('template' => '/upload-copy', 'data' => array('pitch' => $pitch)));
            }
        } else {
        }
    }

    public function getlatestsolution()
    {
        header('Content-Type: application/javascript');
        if (!isset($this->request->query['category_id'])) {
            $this->request->query['category_id'] = null;
        }
        $result = Pitch::apiGetPitch($this->request->query['category_id']);
        echo 'writePitch('.json_encode($result).')';
        die();
    }

    public function getpdf()
    {
        if (($pitch = Pitch::first($this->request->id)) && ($bill = Bill::first($this->request->id))) {
            if (Session::read('user.id') != $pitch->user_id) {
                die();
            }
            require_once LITHIUM_APP_PATH.'/'.'libraries'.'/'.'MPDF54/MPDF54/mpdf.php';
            $options = compact('pitch', 'bill');
            $mpdf = new \mPDF();
            if (($pitch->type == 'plan-payment') && ($extracted = SubscriptionPlan::extractFundBalanceAmount($pitch->id))) {
                $options = compact('pitch', 'bill', 'extracted');
                $mpdf->WriteHTML(PdfGetter::get('BillSubscription', $options));
            } else {
                $mpdf->WriteHTML(PdfGetter::get('Bill', $options));
            }
            $mpdf->Output('godesigner-pitch-'.$pitch->id.'.pdf', 'd');
            exit;
        }
        die();
    }

    /**
     * Метод выводит на скачиване сгенерированный акт pdf.
     */
    public function getPdfAct()
    {
        if (($pitch = Pitch::first($this->request->id))) {
            if (!$this->userHelper->isPitchOwner($pitch->user_id) && !User::checkRole('admin')) {
                return $this->redirect('/users/mypitches');
            }
            $user = User::first($pitch->user_id);
            if ((!$bill = Bill::first($this->request->id)) && (!$user->getUnserializedCompanyData())) {
                return $this->redirect('/users/mypitches');
            }
            $destination = 'Download';
            $addons = Addon::all(array('conditions' => [
                'pitch_id' => $pitch->id,
                'billed' => 1,
                'prolong' => ['>' => 0],
            ]));
            $options = compact('pitch', 'bill', 'addons', 'destination');
            Pitch::generatePdfAct($options);
            die();
        } else {
            return $this->redirect('/users/mypitches');
        }
    }

    /**
     * Мето выводит на скачивание сгенерированный отчёт pdf.
     *
     * @return object
     */
    public function getPdfReport()
    {
        if (($pitch = Pitch::first($this->request->id))) {
            if (!$this->userHelper->isPitchOwner($pitch->user_id) && !User::checkRole('admin')) {
                return $this->redirect('/users/mypitches');
            }
            $user = User::first($pitch->user_id);
            if ((!$bill = Bill::first($this->request->id)) && (!$user->getUnserializedCompanyData())) {
                return $this->redirect('/users/mypitches');
            }
            $destination = 'Download';
            $options = compact('pitch', 'bill', 'destination');
            Pitch::generatePdfReport($options);
            die();
        } else {
            return $this->redirect('/users/mypitches');
        }
    }

    public function addon()
    {
        $pitch = Pitch::first($this->request->id);
        $experts = Expert::all(array('order' => array('id' => 'asc')));
        if($pitch->category_id == 20) {
            $prolongCoeff = 1000;
        }else {
            $prolongCoeff = 1950;
        }
        return compact('pitch', 'experts', 'prolongCoeff');
    }

    public function penalty()
    {
        $solution = Solution::first($this->request->id);
        $designer = User::first(array('conditions' => array('id' => $solution->user_id)));
        $pitch = Pitch::first($solution->pitch_id);
        $penaltyId = Pitch::getNextPenaltyId($this->userHelper->getId(), $solution->id);
        $receipt = array(
            array(
                'name' => 'Несвоевременный выбор победителя',
                'value' => $pitch->getPenaltyAmount(),
            ),
        );
        $penaltyRecord = Pitch::first($penaltyId);
        $penaltyRecord->total = $pitch->getPenaltyAmount();
        $penaltyRecord->save();

        return compact('solution', 'pitch', 'receipt', 'designer', 'penaltyId');
    }

    public function addmoney()
    {
        $pitch = Pitch::first($this->request->id);
        if ($pitch->id != '101534') {
            return $this->redirect('/');
        } else {
            $addon = Addon::first(array('conditions' => array('reward' => '10120')));
        }
    }

    public function promocode()
    {
        Pitch::dailypitch();
        die();
    }

    /**
     * Метод отображения формы оплаты второго победителя.
     *
     * @respond_to html
     *
     * @return array|object
     */
    public function newwinner()
    {
        if (($pitch = Pitch::first($this->request->id)) && $this->userHelper->isPitchOwner($pitch->user_id) && ($receipt = Receipt::all(array('conditions' => array('pitch_id' => $this->request->id), 'fields' => array('name', 'value'))))) {
            return compact('pitch', 'receipt');
        } else {
            return $this->redirect('/pitches');
        }
    }

    public function setnewwinner()
    {
        $solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('Pitch')));
        $pitch = $solution->pitch;
        if (!is_null($pitch->id) && $pitch->awarded != $solution->id && Session::read('user.id') == $pitch->user_id) {
            $copyPitch = Pitch::first(array('conditions' => array('user_id' => $pitch->user_id, 'multiwinner' => $pitch->id, 'billed' => 0)));
            if (!empty($copyPitch)) {
                $copyPitch->awarded = Solution::copy($copyPitch->id, $this->request->id);
                $copyPitch->save();
            } else {
                $newPitchId = Pitch::createNewWinner($solution->id);
                $copyPitch = Pitch::first($newPitchId);
            }
            if ($copyPitch->free == 1) {
                Pitch::activateNewWinner($copyPitch->id);

                return $this->redirect(array('controller' => 'users', 'action' => 'step1', 'id' => $copyPitch->awarded));
            } else {
                if (User::isSubscriptionActive($pitch->user_id)) {
                    $total = Receipt::getTotalForProject($copyPitch->id);
                    $paymentResult = User::reduceBalance($copyPitch->user_id, (int) $total);
                    if (!$paymentResult) {
                        $result = array(
                            'error' => 'need to fill balance',
                            'needToFillAmount' => (int) ($total - User::getBalance($copyPitch->user_id)),
                        );
                        $url = '/subscription_plans/subscriber?amount='.$result['needToFillAmount'];

                        return $this->redirect($url);
                    } else {
                        Pitch::activateNewWinner($copyPitch->id);

                        return $this->redirect(array('controller' => 'users', 'action' => 'step1', 'id' => $copyPitch->awarded));
                    }
                }

                return $this->redirect(array('controller' => 'pitches', 'action' => 'newwinner', 'id' => $copyPitch->id));
            }
        } else {
            return $this->redirect('/pitches');
        }
    }

    public function addfastpitch()
    {
        if ($this->request->is('json')) {
            $pitch = Pitch::create();
            $gatracking = new \Racecore\GATracking\GATracking('UA-9235854-5');
            $gaId = $gatracking->getClientId();
            $pitch->set(array(
                'title' => 'Логотип в один клик ('.$this->request->data['phone'].')',
                'category_id' => 1,
                'phone-brief' => $this->request->data['phone'],
                'expert' => 1,
                'expert-ids' => serialize(array(1)),
                'guaranteed' => 1,
                'pinned' => 1,
                'brief' => 1,
                'specifics' => 'a:2:{s:9:"qualities";s:64:"Прагматичный, надежный, элегантный";s:15:"logo-properties";a:7:{i:0;s:1:"5";i:1;s:1:"5";i:2;s:1:"5";i:3;s:1:"5";i:4;s:1:"5";i:5;s:1:"5";i:6;s:1:"5";}}',
                'price' => 14000,
                'total' => 19600,
                'ga_id' => $gaId
                ));
            if (Session::read('user.id')) {
                $pitch->user_id = Session::read('user.id');
            }
            if ($pitch->save()) {
                $start = new \DateTime();
                $start->setTimestamp($this->request->data['date']);
                \app\models\Schedule::create(array(
                    'pitch_id' => $pitch->id,
                    'title' => 'Логотип в один клик ('.$this->request->data['phone'].')',
                    'start' => $start->format('Y-m-d H:i:s'),
                    'end' => $start->setTime($start->format('H') + 1, '00', '00')->format('Y-m-d H:i:s'),
                ))->save();
                $receiptData = array(
                    'features' => array(
                        'award' => $pitch->price,
                        'discount' => -4030,
                        'brief' => 2750,
                        'experts' => array(1),
                        'guaranteed' => 950,
                        'pinned' => 1000, ),
                    'commonPitchData' => array(
                        'id' => $pitch->id,
                        'category_id' => 0,
                        'promocode' => 0, ), );
                if (isset($_COOKIE['fastpitch'])) {
                    $cookies = unserialize($_COOKIE['fastpitch']);
                    $cookies[] = $pitch->id;
                    setcookie('fastpitch', serialize($cookies), strtotime('+2 month'), '/');
                } else {
                    setcookie('fastpitch', serialize(array($pitch->id)), strtotime('+2 month'), '/');
                }

                return json_encode('/pitches/fastpitch/'.Receipt::createReceipt($receiptData));
            } else {
                return json_encode('false');
            }
        }
    }

    public function fastpitch()
    {
        if (($pitch = Pitch::first($this->request->id)) && ($receipt = Receipt::all(array('conditions' => array('pitch_id' => $this->request->id), 'fields' => array('name', 'value'))))) {
            return compact('pitch', 'receipt');
        } else {
            return $this->redirect('/pitches');
        }
    }

    public function getags()
    {
        if (isset($this->request->query['name']) && strlen($this->request->query['name']) > 0) {
            $tags = Tag::getSuggest($this->request->query['name']);

            return json_encode($tags);
        }
    }

    public function accept()
    {
        if (Pitch::acceptLogosalePitch($this->request->id, Session::read('user.id'))) {
            $logosalePitch = Pitch::first($this->request->id);
            if (!is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
                return compact('true');
            } else {
                $this->redirect('/users/step2/'.$logosalePitch->awarded);
            }
        } else {
            return $this->redirect('/');
        }

        return compact('true');
    }

    public function decline()
    {
        $result = Pitch::declineLogosalePitch($this->request->id, Session::read('user.id'));
        if (!is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
            return compact('result');
        } else {
            $this->redirect('/');
        }

        return compact('true');
    }

    /**
     * Метод для оформления автоматического возврата.
     *
     * @return object
     */
    public function refund_subscription()
    {
        $id = $this->request->id;
        if (($pitch = Pitch::first($id))
            && ($pitch->type == 'company_project')
            && ($this->userHelper->isPitchOwner($pitch->user_id))) {
            Pitch::markAsRefunded($id);
        }

        return $this->redirect('/pitches/view/'.$id);
    }
}
