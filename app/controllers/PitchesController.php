<?php

namespace app\controllers;

use \app\models\Bill;
use \app\models\Pitch;
use \app\models\Pitchfile;
use \app\models\Category;
use \app\models\Grade;
use \app\models\Addon;
use \app\models\Solution;
use \app\models\Comment;
use \app\models\User;
use \app\models\Historycomment;
use \app\models\Historysolution;
use \app\models\Transaction;
use \app\models\Receipt;
use \app\models\Request;
use \app\models\Expert;
use \app\models\Promocode;
use \app\models\Paymaster;
use \app\models\Payanyway;
use \app\models\Promoted;
use app\models\Ratingchange;
use \app\models\Avatar;
use \app\models\Url;
use \app\models\Like;

use \app\extensions\paymentgateways\Webgate;
use \lithium\storage\Session;
use \lithium\analysis\Logger;
use \app\extensions\helper\MoneyFormatter;
use \app\extensions\helper\PitchTitleFormatter;
use \app\extensions\helper\PdfGetter;
use \app\extensions\helper\Avatar as AvatarHelper;

use \Exception;

class PitchesController extends \app\controllers\AppController {

	/**
     * Методы, доступные без аутентификации
     *
     * @var array
     */
	public $publicActions = array(
        'crowdsourcing', 'blank',  'promocode', 'index', 'printpitch', 'robots', 'fillbrief', 'finished', 'add', 'create',
	    'brief', 'activate', 'view', 'details', 'paymaster', 'callback', 'payanyway', 'viewsolution', 'getlatestsolution', 'getpitchdata', 'getcomments'
	);

    public function blank() {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $var = unserialize('a:13:{s:15:"LMI_MERCHANT_ID";s:36:"d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60";s:18:"LMI_PAYMENT_SYSTEM";s:1:"3";s:12:"LMI_CURRENCY";s:3:"RUB";s:18:"LMI_PAYMENT_AMOUNT";s:8:"11710.00";s:14:"LMI_PAYMENT_NO";s:2:"13";s:16:"LMI_PAYMENT_DESC";s:23:"Оплата питча";s:20:"LMI_SYS_PAYMENT_DATE";s:19:"2013-10-25T10:36:27";s:18:"LMI_SYS_PAYMENT_ID";s:8:"10299519";s:15:"LMI_PAID_AMOUNT";s:8:"11710.00";s:17:"LMI_PAID_CURRENCY";s:3:"RUB";s:12:"LMI_SIM_MODE";s:1:"0";s:20:"LMI_PAYER_IDENTIFIER";s:12:"212571931422";s:8:"LMI_HASH";s:24:"uCYU7ZDqmnzNLK335/WPSQ==";}');
        echo '<pre>';
        echo (http_build_query($var));
        //Logger::write('info', serialize('init'), array('name' => 'masterbank'));
        //Logger::write('info', serialize('init'), array('name' => 'paymaster'));
        //Logger::write('debug', serialize('init'));
        die();
    }

    public function blank2() {

    }

	public function index() {
		$categories = Category::all();
		$allowedCategories = array();
		$allowedOrder = array('price', 'finishDate', 'ideas_count', 'title', 'category', 'started');
		$allowedSortDirections = array('asc', 'desc');
        $allowedTimeframe = array(1,2,3,4,'all');
        $hasOwnHiddenPitches = false;
		foreach($categories as $catI) {
			$allowedCategories[] = $catI->id;
		}
        if(Session::read('user.id')) {
            $usersPitches = Pitch::all(array('conditions' => array(
                'user_id' => Session::read('user.id'),
                'published' => 0,
                'status' => 0
            ), 'with' => array('Category')));
            if($usersPitches) {
                $hasOwnHiddenPitches = true;
            }
            $totalOwn = count($usersPitches);
        }

        $limit = 50;
		$page = 1;
		$types = array(
			'finished' => array('OR' => array(array('status = 2'), array('(status = 1 AND awarded > 0)'))),
			'current' => array('status' => array('<' => 2), 'awarded' => 0),
            'all' => array(),
            'index' => array(
                'OR' => array(
                    array('awardedDate >= \'' . date('Y-m-d H:i:s', time() - DAY) . '\''),
                    array('status < 2 AND awarded = 0'),
                ),
            ),
        );
		$priceFilter = array(
			'all' => array(),
			'1' => array('price' => array('>' => 3000, '<=' => 10000)),
			'2' => array('price' => array('>' => 10000, '<=' => 20000)),
			'3' => array('price' => array('>' => 20000))
		);
		$order = array(
			'price' => 'desc',
            'started' => 'desc'
		);
        $timeleftFilter = array(
            '1' => array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 3)))),
            '2' => array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 7)))),
            '3' => array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 10)))),
            '4' => array('finishDate' => array('=>' => date('Y-m-d H:i:s', time() + (DAY * 14)))),
            'all' => array()
        );
        $type = 'index';
		$category = array();
		$conditions = array('published' => 1);
        $hasTag = false;
		if(isset($this->request->query['page'])) {
			$page = abs(intval($this->request->query['page']));
		}
		if((isset($this->request->query['priceFilter'])) && ($this->request->query['priceFilter'] != 'all') && (isset($priceFilter[$this->request->query['priceFilter']]))) {
            $priceFilter = $priceFilter[$this->request->query['priceFilter']];
            $hasTag = true;
		}else {
			$priceFilter = $priceFilter['all'];
		}
        if((isset($this->request->params['category'])) && (($this->request->params['category'] == 'all') || (in_array($this->request->params['category'], $allowedCategories)))){
            $category = $this->request->params['category'];
            if($category != 'all') {
                $hasTag = true;
                $category = array('category_id' => $category);
            }else {
                $category = array();
            }
        }
		if((isset($this->request->query['category'])) && (($this->request->query['category'] == 'all') || (in_array($this->request->query['category'], $allowedCategories)))){
			$category = $this->request->query['category'];
			if($category != 'all') {
                $hasTag = true;
				$category = array('category_id' => $category);
			}else {
				$category = array();
			}
		}
        $timeframe = array();
        if((isset($this->request->query['timeframe'])) && (($this->request->query['timeframe'] == 'all') || (in_array($this->request->query['timeframe'], $allowedTimeframe)))){
            $hasTag = true;
            $timeframe = $timeleftFilter[$this->request->query['timeframe']];
        }
        $search = array();
        if((isset($this->request->query['searchTerm'])) && ($this->request->query['searchTerm'] != 'НАЙТИ ПИТЧ ПО КЛЮЧЕВОМУ СЛОВУ ИЛИ ТИПУ' && $this->request->query['searchTerm'] != '')){
            $hasTag = true;
            $word = urldecode(filter_var($this->request->query['searchTerm'], FILTER_SANITIZE_STRING));
            $firstLetter = mb_substr($word, 0, 1, 'utf-8');
            $firstUpper = (mb_strtoupper($firstLetter, 'utf-8'));
            $firstLower = (mb_strtolower($firstLetter, 'utf-8'));
            $string = $firstLower . mb_substr($word, 1, mb_strlen($word, 'utf-8'), 'utf-8') . '|' . $firstUpper . mb_substr($word, 1, mb_strlen($word, 'utf-8'), 'utf-8') . '|' . mb_strtoupper($word, 'utf-8');
            $search =  array('Pitch.title' => array('REGEXP' => $string));
        }
        if(($hasTag) || ((isset($this->request->query['type'])) && (in_array($this->request->query['type'], array_keys($types))))) {
            $type = $this->request->query['type'];
            //if(($hasTag)) {
            //    $type = 'all';
            //}
        }
		if((isset($this->request->query['order']))) {
			$newOrder = $this->request->query['order'];
			foreach($newOrder as $key => $direction) {
				$field = $key;
				$dir = $direction;
				break;
			}
			if((in_array($field, $allowedOrder)) && (in_array($dir, $allowedSortDirections)))  {
				if($field == 'category') $field = 'category_id';
                if($field == 'finishDate') {
                    $order = array('(finishDate - \'' . date('Y-m-d H:i:s') . '\')' => $dir);
                }else {
                    $order = array(
                        $field => $dir,
                        'started' => 'desc'
                    );
                }
			}
		}

		$conditions += $types[$type];
		$conditions += $category;
		$conditions += $priceFilter;
        $conditions += $timeframe;
        $conditions += $search;

		/*******/
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
        if($pitches) {
            if(($hasOwnHiddenPitches) && ($page == 1)){
                foreach($usersPitches as $pitch) {
                    $tempPitchList[] = $pitch->data();
                }
            }
            foreach($pitches as $pitch) {
                $tempPitchList[] = $pitch->data();
            }
        }
		$pitchList = array();
        $pitchTitleHelper = new PitchTitleFormatter;
		foreach($tempPitchList as &$pitch) {
			$pitch['sort'] = $i;
            $pitch['title'] = $pitchTitleHelper->renderTitle($pitch['title']);
            $pitch['multiple'] = Pitch::getMultiple($pitch['category_id'], $pitch['specifics']);
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
		$query = $this->request->query;
		return compact('data', 'categories', 'query', 'selectedCategory');
	}

    public function finished() {
        $categories = Category::all();
        $allowedCategories = array();
        $allowedOrder = array('price', 'finishDate', 'ideas_count', 'title', 'category', 'started');
        $allowedSortDirections = array('asc', 'desc');
        $allowedTimeframe = array(1,2,3,4,'all');
        $hasOwnHiddenPitches = false;
        foreach($categories as $catI) {
            $allowedCategories[] = $catI->id;
        }
        if(Session::read('user.id')) {
            $usersPitches = Pitch::all(array('conditions' => array(
                'user_id' => Session::read('user.id'),
                'published' => 0,
                'status' => 0
            ), 'with' => array('Category')));
            if($usersPitches) {
                $hasOwnHiddenPitches = true;
            }
            $totalOwn = count($usersPitches);
        }

        $limit = 10;
        $page = 1;
        $types = array(
            'finished' => array('OR' => array(array('status = 2'), array('(status = 1 AND awarded > 0)'))),
            'current' => array('status' => array('<' => 2), 'awarded' => 0)
        );
        $priceFilter = array(
            'all' => array(),
            '1' => array('price' => array('>' => 1000, '<=' => 3000)),
            '2' => array('price' => array('>' => 3000, '<=' => 6000)),
            '3' => array('price' => array('>' => 6000))
        );
        $timeleftFilter = array(
            '1' => array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 3)))),
            '2' => array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 7)))),
            '3' => array('finishDate' => array('<=' => date('Y-m-d H:i:s', time() + (DAY * 10)))),
            '4' => array('finishDate' => array('=>' => date('Y-m-d H:i:s', time() + (DAY * 14)))),
            'all' => array()
        );
        $search = array();
        if((isset($this->request->query['searchTerm'])) && ($this->request->query['searchTerm'] != 'НАЙТИ ПИТЧ ПО КЛЮЧЕВОМУ СЛОВУ ИЛИ ТИПУ' && $this->request->query['searchTerm'] != '')){
            $hasTag = true;
            $word = urldecode(filter_var($this->request->query['searchTerm'], FILTER_SANITIZE_STRING));
            $firstLetter = mb_substr($word, 0, 1, 'utf-8');
            $firstUpper = (mb_strtoupper($firstLetter, 'utf-8'));
            $firstLower = (mb_strtolower($firstLetter, 'utf-8'));
            $string = $firstLower . mb_substr($word, 1, mb_strlen($word, 'utf-8'), 'utf-8') . '|' . $firstUpper . mb_substr($word, 1, mb_strlen($word, 'utf-8'), 'utf-8');
            $search =  array('Pitch.title' => array('REGEXP' => $string));
        }

        $order = array(
            'price' => 'desc',
            'started' => 'desc'
        );
        $type = 'current';
        $category = array();
        $conditions = array('published' => 1);
        if(isset($this->request->query['page'])) {
            $page = abs(intval($this->request->query['page']));
        }
        if((isset($this->request->query['type'])) && (in_array($this->request->query['type'], array_keys($types)))) {
            $type = $this->request->query['type'];
        }
        if((isset($this->request->query['priceFilter'])) && (isset($priceFilter[$this->request->query['priceFilter']]))) {
            $priceFilter = $priceFilter[$this->request->query['priceFilter']];
        }else {
            $priceFilter = $priceFilter['all'];
        }
        if((isset($this->request->query['category'])) && (($this->request->query['category'] == 'all') || (in_array($this->request->query['category'], $allowedCategories)))){
            $category = $this->request->query['category'];
            if($category != 'all') {
                $category = array('category_id' => $category);
            }else {
                $category = array();
            }
        }
        $timeframe = array();
        if((isset($this->request->query['timeframe'])) && (($this->request->query['timeframe'] == 'all') || (in_array($this->request->query['timeframe'], $allowedTimeframe)))){
            $timeframe = $timeleftFilter[$this->request->query['timeframe']];
        }
        if((isset($this->request->category)) && (($this->request->category == 'all') || (in_array($this->request->category, $allowedCategories)))){
            if($this->request->category != 'all') {
                $selectedCategory = $this->request->category;
            }else {
                $selectedCategory = 'all';
            }
        }else {
            $selectedCategory = 'all';
        }
        if((isset($this->request->query['order']))) {
            $newOrder = $this->request->query['order'];
            foreach($newOrder as $key => $direction) {
                $field = $key;
                $dir = $direction;
                break;
            }
            if((in_array($field, $allowedOrder)) && (in_array($dir, $allowedSortDirections)))  {
                if($field == 'category') $field = 'category_id';
                if($field == 'finishDate') {
                    $order = array('(finishDate - \'' . date('Y-m-d H:i:s') . '\')' => $dir);
                }else {
                    $order = array(
                        $field => $dir,
                        'started' => 'desc'
                    );
                }
            }
        }

        $conditions += $types[$type];
        $conditions += $category;
        $conditions += $priceFilter;
        $conditions += $timeframe;
        $conditions += $search;

        /*******/
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
        if($pitches) {
            if(($hasOwnHiddenPitches) && ($page == 1)){
                foreach($usersPitches as $pitch) {
                    $tempPitchList[] = $pitch->data();
                }
            }
            foreach($pitches as $pitch) {
                $tempPitchList[] = $pitch->data();
            }
        }
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
        $query = $this->request->query;
        return compact('data', 'categories', 'query', 'selectedCategory');
    }

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

                $pitch->hasBill = false;
                if (($pitch->status == 2) && ($pitch->user_id == Session::read('user.id'))) {
                    if ($bill = Bill::first($pitch->id)) {
                        $pitch->hasBill = ($bill->individual == 1) ? 'fiz' : 'yur';
                    }
                }
            }
            $i = 1;
            $tempPitchList = $pitches->data();
            $pitchList = array();
            foreach($tempPitchList as &$pitch) {
                $pitch['sort'] = $i;
                $pitch['multiple'] = Pitch::getMultiple($pitch['category_id'], $pitch['specifics']);
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

    /*public function test() {
        $url = "http://www.godesigner.ru/callback";
        $array = unserialize('a:21:{s:8:"FUNCTION";s:13:"TransResponse";s:2:"RC";s:2:"00";s:6:"AMOUNT";s:7:"7665.00";s:8:"CURRENCY";s:3:"RUB";s:5:"ORDER";s:6:"100198";s:3:"RRN";s:12:"215003928732";s:8:"AUTHCODE";s:6:"700740";s:3:"PAN";s:16:"4XXXXXXXXXXX4021";s:3:"BIN";s:0:"";s:8:"TERMINAL";s:8:"71846655";s:6:"TRTYPE";s:1:"0";s:11:"TEXTMESSAGE";s:8:"Approved";s:14:"CARDHOLDERNAME";s:13:"ANNA KISELEVA";s:3:"ACS";s:71:"http://web3ds.masterbank.ru:8235/way4mpi/xml/pa?traceid=#004021eba85b9e";s:4:"ACS1";s:79:"https://acs.sbrf.ru/acs/uidispatcherjsessionid=A1C02BE0C210F8F9E880CD9B04F9B030";s:6:"RESULT";s:1:"0";s:7:"INT_REF";s:16:"2B11C6CB0ACA7BF8";s:9:"TIMESTAMP";s:14:"20120529064821";s:7:"USER_IP";s:15:"192.168.201.107";s:4:"SIGN";s:32:"ef6d940d541f50963a575f000a459121";s:13:"SIGN_CALLBACK";s:32:"c90c1c78d448afe26dbe06d7d4d17940";}');
        $string = http_build_query($array);
        echo $string;die();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 3); // times out after 4s
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string); // add POST fields
        $result = curl_exec($ch); // run the whole process

        echo '****<br>';
        echo curl_getinfo($ch, CURLINFO_HTTP_CODE);
        echo '<br>****<br>';
        curl_close($ch);
        echo $result;
        die();
    }*/


    public function updatefiles() {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        if($pitch = Pitch::first($this->request->data['id'])) {
            $existingArray = array();
            //$existingArray = unserialize($pitch->filesId);
            if (isset($this->request->data['fileids'])) {
                foreach($this->request->data['fileids'] as $key => $item) {
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

    public function delete() {
        if($pitch = Pitch::first($this->request->id)) {
            if(($pitch->user_id == Session::read('user.id')) && ($pitch->published == 0) && ($pitch->billed == 0) && ($pitch->ideas_count == 0)) {
                $pitch->delete();
            }
            return $pitch->data();
        }
    }

    public function callback() {
        $secretword = 'ge6biTwUghs78g73sY6'; //секретное слово
        //$secretword = 'mO74WC9rnOJu'; //секретное слово
        //проверяем что пришли правильные данные
        if((isset($this->request->data["SIGN_CALLBACK"])) && (trim($this->request->data["SIGN_CALLBACK"]) == md5($this->request->data["TERMINAL"].$this->request->data["TIMESTAMP"].$this->request->data["ORDER"].$this->request->data["AMOUNT"].$this->request->data["RESULT"].$this->request->data["RC"].$this->request->data["RRN"].$this->request->data["INT_REF"].$this->request->data["TRTYPE"].$this->request->data["AUTHCODE"].$secretword))) {
            //проверяем что операция прошла успешно
            Logger::write('info', serialize($this->request->data), array('name' => 'masterbank'));
            if (0 == $this->request->data["RESULT"]) {
                switch ($this->request->data["TRTYPE"]) {
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
                        if($pitch = Pitch::first($this->request->data['ORDER'])) {
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
        if(!empty($this->request->data)) {
            $transaction = Transaction::create();
            $transaction->set($this->request->data);
            $transaction->save();
        }
        header("HTTP/1.0 200 OK");
        die();
    }

    public function readlogs() {
        /*
        2012-02-27 15:30:24 a:0:{}
2012-02-27 15:30:24 a:0:{}
2012-02-27 15:32:22 a:19:{s:8:"FUNCTION";s:13:"TransResponse";s:6:"RESULT";s:1:"3";s:2:"RC";s:2:"-2";s:6:"AMOUNT";s:7:"3500.00";s:8:"CURRENCY";s:3:"RUB";s:5:"ORDER";s:2:"47";s:3:"RRN";s:0:"";s:8:"AUTHCODE";s:0:"";s:3:"PAN";s:16:"4XXXXXXXXXXX1896";s:8:"TERMINAL";s:8:"10000059";s:6:"TRTYPE";s:1:"0";s:11:"TEXTMESSAGE";s:15:"Bad CGI request";s:14:"CARDHOLDERNAME";s:0:"";s:3:"ACS";s:0:"";s:7:"INT_REF";s:0:"";s:9:"TIMESTAMP";s:14:"20120227153222";s:7:"USER_IP";s:14:"192.168.200.35";s:4:"SIGN";s:32:"72d89e1593c669c49808f7731c2216e7";s:13:"SIGN_CALLBACK";s:32:"383f33976775347289f0bb3ff0e6255c";}
2012-02-27 15:32:22 a:19:{s:8:"FUNCTION";s:13:"TransResponse";s:6:"RESULT";s:1:"3";s:2:"RC";s:2:"-2";s:6:"AMOUNT";s:7:"3500.00";s:8:"CURRENCY";s:3:"RUB";s:5:"ORDER";s:2:"47";s:3:"RRN";s:0:"";s:8:"AUTHCODE";s:0:"";s:3:"PAN";s:16:"4XXXXXXXXXXX1896";s:8:"TERMINAL";s:8:"10000059";s:6:"TRTYPE";s:1:"0";s:11:"TEXTMESSAGE";s:15:"Bad CGI request";s:14:"CARDHOLDERNAME";s:0:"";s:3:"ACS";s:0:"";s:7:"INT_REF";s:0:"";s:9:"TIMESTAMP";s:14:"20120227153222";s:7:"USER_IP";s:14:"192.168.200.35";s:4:"SIGN";s:32:"72d89e1593c669c49808f7731c2216e7";s:13:"SIGN_CALLBACK";s:32:"383f33976775347289f0bb3ff0e6255c";}

         * */
        echo '<pre>';
        var_dump(unserialize('a:19:{s:8:"FUNCTION";s:13:"TransResponse";s:6:"RESULT";s:1:"3";s:2:"RC";s:2:"-2";s:6:"AMOUNT";s:7:"3500.00";s:8:"CURRENCY";s:3:"RUB";s:5:"ORDER";s:2:"47";s:3:"RRN";s:0:"";s:8:"AUTHCODE";s:0:"";s:3:"PAN";s:16:"4XXXXXXXXXXX1896";s:8:"TERMINAL";s:8:"10000059";s:6:"TRTYPE";s:1:"0";s:11:"TEXTMESSAGE";s:15:"Bad CGI request";s:14:"CARDHOLDERNAME";s:4:"Test";s:3:"ACS";s:0:"";s:7:"INT_REF";s:0:"";s:9:"TIMESTAMP";s:14:"20120227154456";s:7:"USER_IP";s:14:"192.168.200.35";s:4:"SIGN";s:32:"346ac406b6f44ad743ae446294efe154";s:13:"SIGN_CALLBACK";s:32:"0cd09d1829025aacfca6b8a98c18c2a0";}'));
        echo '</pre>';
        die();
    }

    function paymaster() {
        Logger::write('info', serialize($this->request->data), array('name' => 'paymaster'));
        if (!empty($this->request->data)
            && !empty($this->request->data['LMI_MERCHANT_ID'])
            && !empty($this->request->data['LMI_PAYMENT_SYSTEM'])
            && !empty($this->request->data['LMI_CURRENCY'])
            && !empty($this->request->data['LMI_PAYMENT_AMOUNT'])
            && !empty($this->request->data['LMI_PAYMENT_NO'])
            && !empty($this->request->data['LMI_SYS_PAYMENT_DATE'])
            && !empty($this->request->data['LMI_SYS_PAYMENT_ID'])
            && !empty($this->request->data['LMI_PAID_AMOUNT'])
            && !empty($this->request->data['LMI_HASH'])) {
                $transaction = Paymaster::create();
                $transaction->set($this->request->data);
                $transaction->save();
                if($pitch = Pitch::first($this->request->data['LMI_PAYMENT_NO'])) {
                    Pitch::activate($this->request->data['LMI_PAYMENT_NO']);
                } elseif ($addon = Addon::first($this->request->data['LMI_PAYMENT_NO'])) {
                    Addon::activate($addon);
                }
        }
        header("HTTP/1.0 200 OK");
        die();
    }

    public function payanyway() {
        Logger::write('info', serialize($this->request->data), array('name' => 'payanyway'));
        if (!empty($this->request->data)
            && !empty($this->request->data['MNT_ID'])
            && !empty($this->request->data['MNT_TRANSACTION_ID'])
            && !empty($this->request->data['MNT_AMOUNT'])
        ) {
            $transaction = Payanyway::create();
            $transaction->set($this->request->data);
            $transaction->save();
            if(($pitch = Pitch::first($this->request->data['MNT_TRANSACTION_ID'])) && ($pitch->total == $this->request->data['MNT_AMOUNT'])) {
                Pitch::activate($this->request->data['MNT_TRANSACTION_ID']);
            } elseif ($addon = Addon::first($this->request->data['MNT_TRANSACTION_ID'])) {
                Addon::activate($addon);
            }
            echo 'SUCCESS';
        }else {
            echo 'FAIL';
        }
        header("HTTP/1.0 200 OK");
        die();
    }


    public function favourites() {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        $categories = Category::all();
        $pitchesId = User::getFavouritePitches(Session::read('user.id'));
        $data = array(
            'pitches' => array(),
            'info' => array(
                'page' => 1,
                'total' => 0
            ),
        );
        if(!empty($pitchesId)) {
            $pitchesId = array_keys($pitchesId);
            $limit = 5;
            $page = 1;
            if(isset($this->request->query['page'])) {
                $page = abs(intval($this->request->query['page']));
            }
            $conditions = array('Pitch.id' => $pitchesId);
            $conditions += array('status' => array('<' => 2));
            /*******/
            $total = ceil(Pitch::count(array(
                'conditions' => $conditions,
            )) / $limit);
            $pitches = Pitch::all(array(
                'with' => 'Category',
                'conditions' => $conditions,
                'order' => array('price' => 'desc'),
                'limit' => $limit,
                'page' => $page,
            ));
            $i = 1;
            $pitchList = array();
            if($pitches) {
            $tempPitchList = $pitches->data();
                foreach($tempPitchList as &$pitch) {
                    $pitch['sort'] = $i;
                    $pitch['multiple'] = Pitch::getMultiple($pitch['category_id'], $pitch['specifics']);
                    $pitchList[] = $pitch;
                    $i++;
                }
            }else {

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

	public function create() {
		$temp = Category::all();
		$categories = array();
		foreach($temp as $cat) {
			$categories[$cat->id] = $cat;
		}
		return compact('categories');
	}

    public function getpitchdata() {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        $pitchId = $this->request->data['pitch_id'];
        $pitch = Pitch::first(array('conditions' => array('Pitch.id' => $pitchId), 'with' => array('Category')));
        $award = $pitch->price;
        $category = $pitch->category;
        $money = 3;
        if($award >= $category->normalAward) {
            $money = 4;
        }else if ($award >= $category->goodAward) {
            $money = 5;
        }
        $begin = new \DateTime( $pitch->started );
        if(strtotime($pitch->finishDate) > time()) {
            $end = new \DateTime( date('Y-m-d', time() + DAY ) );
        }else{
            $end = new \DateTime( date('Y-m-d', strtotime($pitch->finishDate) ) );
        }
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);


        $ratingArray = array();
        $moneyArray = array();
        $commentArray = array();
        $dates = array();
        $firstSolution = Solution::first(array('conditions' => array('pitch_id' => $pitchId), 'order' => array('created' => 'asc')) );
        if($firstSolution) {
            $firstSolutionTime = strtotime($firstSolution->created);
        }
        foreach ( $period as $dt ) {
            $time = strtotime($dt->format('Y-m-d'));
            $plusDay = date('Y-m-d H:i:s', $time + DAY);
            if(strtotime($pitch->created) > strtotime('2013-03-25 00-00-00')) {
                $solutions = Historysolution::all(array('conditions' => array('pitch_id' => $pitchId, 'date(created)' => array('<' => $plusDay))));
            }else {
                $solutions = Solution::all(array('conditions' => array('pitch_id' => $pitchId, 'date(created)' => array('<' => $plusDay))));
            }
            $ids = array();
            foreach($solutions as $solution) {
                $ids[] = $solution->id;
            }
            $dates[] = $dt->format('d/m');
            $moneyArray[] = $money;
            if(!empty($ids)) {
                $ratingsNum = Ratingchange::all(array('conditions' => array('solution_id' => $ids ,'user_id' => $pitch->user_id, 'date(created)' => array('<' => $plusDay))));
            }else {
                $ratingsNum = array();
            }
            $rating = 0;
            $percents = 0;
            if(count($solutions) > 0) {
                $percents = (count($ratingsNum) / count($solutions)) * 100;
            }
            if($percents > 100) {
                $percents = 100;
            }
            switch($percents) {
                case $percents < 50: $rating =1; break;
                case $percents < 63: $rating =2; break;
                case $percents < 79: $rating =3; break;
                case $percents < 89: $rating =4; break;
                case $percents <= 100: $rating =5; break;
            }
            if((!$firstSolution) || (($firstSolution) && ($firstSolutionTime > strtotime($dt->format('Y-m-d H:i:s')) + DAY))) {
                $rating = 3;
            }elseif(($dt->format('d/m') == '12/03') && ($pitch->id == '100757')) {
                $rating = 3;
            }elseif(($dt->format('d/m') == '13/03') && ($pitch->id == '100757')) {
                $rating = 2;
            }elseif(($dt->format('d/m') == '16/08') && ($pitch->id == '101187')) {
                $rating = 5;
            }

            $ratingArray[] = $rating;
            if(!empty($ids)){
                if(strtotime($pitch->created) > strtotime('2013-03-24 18:00:00')) {
                    $commentsNum = Historycomment::all(array('conditions' => array('user_id' => $pitch->user_id, 'date(created)' => array('<' => $plusDay))));
                }else {
                    $commentsNum = Comment::all(array('conditions' => array('user_id' => $pitch->user_id, 'date(created)' => array('<' => $plusDay))));
                }
            }else {
                $commentsNum = array();
            }

            //var_dump(count($solutions));
            //var_dump(count($commentsNum));
            $comments = 0;
            $percents = 0;
            if(count($solutions) > 0) {
                $percents = (count($commentsNum) / count($solutions)) * 100;
            }
            //var_dump($percents);
            if($percents > 100) {
                $percents = 100;
            }
            switch($percents) {
                case $percents < 50: $comments =1; break;
                case $percents < 63: $comments =2; break;
                case $percents < 79: $comments =3; break;
                case $percents < 89: $comments =4; break;
                case $percents <= 100: $comments =5; break;
            }

            if((!$firstSolution) || (($firstSolution) && ($firstSolutionTime > strtotime($dt->format('Y-m-d H:i:s')) + DAY))) {
                $comments = 3;
            }elseif(($dt->format('d/m') == '13/03') && ($pitch->id == '100757')) {
                $comments = 4;
            }elseif(($dt->format('d/m') == '16/08') && ($pitch->id == '101187')) {
                $comments = 5;
            }
            $commentArray[] = $comments;
        }
        function calcAvg($first, $second, $third) {
            $avgArray = array();
            for($i=0;$i<count($first);$i++) {
                $avg = round((($first[$i] + $second[$i] + $third[$i]) / 3), 1);
                $avgArray[] = $avg;
            }
            return $avgArray;
        }
        $ratingAverage = round(array_sum($ratingArray) / count($ratingArray), 1);
        $moneyAverage = round(array_sum($moneyArray) / count($moneyArray), 1);
        $commentAverage = round(array_sum($commentArray) / count($commentArray), 1);
        $percentages = array(
            'rating' => round(($ratingAverage / 15) * 100),
            'money' => round(($moneyAverage / 15) * 100),
            'comment' => round(($commentAverage / 15) * 100),
        );
        $total = 0;
        foreach($percentages as $key => $value) {
            $total += $value;
        }
        $percentages['empty'] = 100 - $total;
        $avgArray = calcAvg($ratingArray, $moneyArray, $commentArray);
        $avgNum = round(array_sum($avgArray) / count($avgArray), 1);
        $guaranteed = $pitch->guaranteed;
        $needRatingPopup = $pitch->ratingPopup($avgArray);
        $needWinnerPopup = $pitch->winnerPopup();
        return compact('guaranteed', 'dates', 'ratingArray', 'moneyArray', 'commentArray', 'avgArray', 'avgNum', 'percentages', 'needRatingPopup', 'needWinnerPopup');
    }

    public function fillbrief() {
        Session::write('fillbrief', true);
        return $this->redirect('/pitches/create');
    }

	public function brief() {
	    $referal = 0;
	    $referalId = 0;
	    if (isset($_COOKIE['ref']) && ($_COOKIE['ref'] != '')) {
            $referal = REFERAL_DISCOUNT;
            $referalId = $_COOKIE['ref'];
	    }
		if(!$this->request->category) {
			return $this->redirect('Pitches::create');
		}
		if($category = Category::first($this->request->category)) {
            $experts = Expert::all(array('order' => array('id' => 'asc')));
            $promocode = Session::read('promocode');
            if (!is_null($promocode)) {
                return compact('category', 'experts', 'referal', 'referalId', 'promocode');
            }
			return compact('category', 'experts', 'referal', 'referalId');
		}
		return $this->redirect('Pitches::create');
	}

	/*public function activate() {
		if(isset($this->request->data['id'])) {
			Pitch::activate($this->request->data['id']);
		}
		return $this->redirect('Pitches::index');
	}*/

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
            $promocode = '';
            if((isset($commonPitchData['promocode'])) && (!empty($commonPitchData['promocode']))) {
                $code = Promocode::first(array('conditions' => array('code' => $commonPitchData['promocode'])));
                if($code->type == 'pinned') {
                    $freePinned = true;
                }
                if($code) {
                    $promocode = $commonPitchData['promocode'];
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
                        'promocode' => $promocode
                    );
                }
			}else {
                $userId = Session::read('user.id');

                if(is_null($userId)) {
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
                        ))))) {
                    $referalId = $referalUser->id;
                    setcookie('ref', '', time() - 3600, '/');
                } else {
                    $referalSum = 0;
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
                    'promocode' => $promocode,
                    'referal' => $referalId,
                    'referal_sum' => $referalSum,
				);
			}
			if(!$pitch = Pitch::first(array('conditions' => array('id' => $commonPitchData['id'])))) {
				$pitch = Pitch::create();
			}
			$pitch->set($data);
			if($pitch->save()) {
                if(($edit == true) && ($pitch->published == 1) && ($pitch->status != 2)) {
                    $message = 'Друзья, в брифе возникли изменения, и мы убедительно просим вас с ними ознакомиться.';
                    $admin = $admin = User::getAdmin();
                    $data = array('pitch_id' => $pitch->id, 'reply_to' => 0, 'user_id' => $admin, 'text' => $message);
                    Comment::createComment($data);
                }

                if(isset($code)) {
                    $code->pitch_id = $pitch->id;
                    $code->save();
                    Session::delete('promocode');
                }
            }

            $this->request->data['commonPitchData']['id'] = $pitch->id;
            // Receipt here
            if($pitch->billed == 0) {
                if($pitch->promocode != '') {
                    $this->request->data['commonPitchData']['promocode'] = $pitch->promocode;
                }
                if ($pitch->referal_sum > 0) {
                    $this->request->data['commonPitchData']['referalDiscount'] = $pitch->referal_sum;
                }
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
            // Referal correction
            if (!empty($pitch->referal)) {
                if ((User::isReferalAllowed($pitch->user_id) != 1) || (false == Pitch::isReferalAllowed($pitch))) {
                    $receiptComission = Receipt::first(array('conditions' => array('pitch_id' => $pitch->id, 'name' => 'Сбор GoDesigner')));
                    $receiptComission->value += $pitch->referal_sum;
                    $receiptComission->save();
                    $pitch->referal = 0;
                    $pitch->referal_sum = 0;
                    $pitch->total = Receipt::findTotal($pitch->id);
                    $pitch->save();
                }
            }
            return compact('pitch', 'category', 'files', 'experts', 'code');
        }
    }


    public function printpitch() {
        if($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) {
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
            if(!empty($fileIds)) {
                $files = Pitchfile::all(array('conditions' => array('id' => $fileIds)));
            }
            return $this->render(array('layout' => 'print', 'data' => compact('pitch', 'files')));

        }
    }

	public function view() {
		if($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) {
            $currentUser = Session::read('user');
            if(($pitch->published == 0) && (($currentUser['id'] != $pitch->user_id) && ($currentUser['isAdmin'] != 1) && (!in_array($currentUser['id'], User::$admins)))) {
                return $this->redirect('/pitches');
            }
            if($pitch->private == 1) {
                if(($pitch->user_id != $currentUser['id']) && (!in_array($currentUser['id'], User::$admins)) && (!$isExists = Request::first(array('conditions' => array('user_id' => $currentUser['id'], 'pitch_id' => $pitch->id))))) {
                    return $this->redirect('/requests/sign/' . $pitch->id);
                }
            }

            $canViewPrivate = false;
            if (User::getAwardedSolutionNum($currentUser['id']) >= WINS_FOR_VIEW) {
                $canViewPrivate = true;
            }

            if ((Session::read('user.id') == $pitch->user_id) && (strtotime($pitch->finishDate) < time()) && ($pitch->status == 0)) {
                $sort = 'created';
                $order = array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc');
            } else if ((Session::read('user.id') == $pitch->user_id) || ($pitch->status > 0)) {
                //$sort = 'rating';
                //$order = array('nominated' => 'desc', 'rating' => 'desc');
                $sort = 'rating';
                $order = array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc');
            }else {
                $sort = 'created';
                $order = array('hidden' => 'asc', 'awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc');
            }
            $validSorts = array('rating', 'created', 'likes');
            if((isset($this->request->query['sorting'])) && (in_array($this->request->query['sorting'], $validSorts))){
                if(Session::read('user.id') == $pitch->user_id) {
                    switch($this->request->query['sorting']) {
                        case 'rating':
                            $sort = 'rating';
                            $order = array('awarded' => 'desc', 'hidden' => 'asc', 'nominated' => 'desc', 'rating' => 'desc', );
                            break;
                        case 'created':
                            $sort = 'created';
                            $order = array('awarded' => 'desc', 'hidden' => 'asc', 'nominated' => 'desc', 'created' => 'desc');
                            break;
                        case 'likes':
                            $sort = 'likes';
                            $order = array('awarded' => 'desc', 'hidden' => 'asc', 'nominated' => 'desc', 'likes' => 'desc');
                            break;
                        default: break;
                    }
                }else {
                    switch($this->request->query['sorting']) {
                        case 'rating':
                            $sort = 'rating';
                            $order = array('awarded' => 'desc', 'nominated' => 'desc', 'rating' => 'desc');
                            break;
                        case 'created':
                            $sort = 'created';
                            $order = array('awarded' => 'desc', 'nominated' => 'desc', 'created' => 'desc');
                            break;
                        case 'likes':
                            $sort = 'likes';
                            $order = array('awarded' => 'desc', 'nominated' => 'desc', 'likes' => 'desc');
                            break;
                        default: break;
                    }
                }
            }

			$solutions = Solution::all(array('conditions' => array('pitch_id' => $this->request->id), 'with' => array('User'), 'order' => $order));
            $selectedsolution = false;
            $nominatedSolutionOfThisPitch = Solution::first(array(
                'conditions' => array('nominated' => 1, 'pitch_id' => $pitch->id)
            ));
            if($nominatedSolutionOfThisPitch) {
                $selectedsolution = true;
            }
            $experts = Expert::all(array('conditions' => array('Expert.user_id' => array('>' => 0))));
            if(is_null($this->request->env('HTTP_X_REQUESTED_WITH'))){
			    return compact('pitch', 'solutions', 'selectedsolution', 'sort', 'experts', 'canViewPrivate');
            }else {
                return $this->render(array('layout' => false), compact('pitch', 'solutions', 'selectedsolution', 'sort', 'experts', 'canViewPrivate'));
            }
		}
		throw new Exception('Public:Такого питча не существует.', 404);
	}

    public function getComments() {
        if (!$this->request->is('json')) {
            return $this->redirect('/pitches');
        }
        if ($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id)))) {
            $currentUser = Session::read('user');
            if (
               $pitch->published == 0 &&
               $currentUser['id'] != $pitch->user_id &&
               $currentUser['isAdmin'] != 1 &&
               !User::checkRole('admin')
               ) {
                return false;
	        }
	        if (
	           $pitch->private == 1 &&
	           $currentUser['id'] != $pitch->user_id &&
	           !User::checkRole('admin') &&
	           !$isExists = Request::first(array(
	               'conditions' => array(
	                   'user_id' => $currentUser['id'],
	                   'pitch_id' => $pitch->id,
	               ),
	           ))
	           ) {
	           return false;
	        }

	        $experts = Expert::all(array('conditions' => array('Expert.user_id' => array('>' => 0))));
	        $expertsIds = array();
	        foreach ($experts as $expert) {
	           $expertsIds[] = $expert->user_id;
	        }

	        $solutions = Solution::all(array('conditions' => array('pitch_id' => $pitch->id), 'with' => array('User')));
	        $mySolutionList = array();
	        $mySolutionNumList = array();
	        if (count($solutions) > 0 && $pitch->published == 1) {
                foreach ($solutions as $solution) {
                    if ($currentUser['id'] == $solution->user_id) {
                        $mySolutionList[] = $solution->id;
                        $mySolutionNumList[] = '#' . $solution->num;
                    }
                }
	        }

	        $commentsRaw = Comment::all(array(
	            'conditions' => array('pitch_id' => $this->request->id),
	            'order' => array('Comment.id' => 'desc'),
	            'with' => array('User')));

	        $commentsRaw = Comment::addAvatars($commentsRaw);
	        $comments = new \lithium\util\Collection();

	        foreach ($commentsRaw as $comment) {
                if ($pitch->category_id == 7 || $pitch->private == 1) {
                    if ($pitch->user_id != $currentUser['id'] && $comment->user_id != $currentUser['id'] && !in_array($currentUser['id'], $expertsIds) && !User::checkRole('admin')) {
                        if (!in_array($comment->solution_id, $mySolutionList) && $comment->user_id == $pitch->user_id && $comment->solution_id != 0 && $comment->reply_to != $currentUser['id']) {
                            continue;
                        }
                        if ($comment->user_id != $pitch->user_id && !$comment->user->isAdmin) {
                            continue;
                        }
                        if (preg_match_all('/^(#\d+)\D/', $comment->originalText, $matches, PREG_PATTERN_ORDER)) {
                            $array = array();
                            foreach ($matches[1] as $match) {
                                $array[] = $match;
                            }

                            $noSolutions = true;
                            foreach ($mySolutionNumList as $mySolutionNum) {
                                if (in_array($mySolutionNum, $array)) {
                                    $noSolutions = false;
                                    break;
                                }
                            }
                            if ($noSolutions) {
                                continue;
                            }
                        }
                    }
                }
                $comments->append($comment);
            }

            return compact('comments', 'experts', 'pitch');
	    } else {
	        return false;
	    }
	}

	public function getCommentsNew() {
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

	        $experts = Expert::all(array('conditions' => array('Expert.user_id' => array('>' => 0))));
	        $expertsIds = array();
	        foreach ($experts as $expert) {
	            $expertsIds[] = $expert->user_id;
	        }

	        // Fetch Top Level Comments
	        $commentsRaw = Comment::all(array(
	            'conditions' => array(
                    'pitch_id' => $pitch->id,
                    'question_id' => 0,
	            ),
	            'order' => array('Comment.id' => 'desc'),
	            'with' => array('User', 'Pitch')));

	        $commentsRaw = Comment::addAvatars($commentsRaw);
	        $comments = new \lithium\util\Collection();

	        if ((true == $isUserClient) || (true == $isUserAdmin)) {
	            foreach ($commentsRaw as $comment) {
	                $comment->needAnswer = '';
	                if (($comment->user->isAdmin != 1) && ($comment->user->id != $comment->pitch->user_id) && (!in_array($comment->user->id, User::$admins))) {
	                    $comment->needAnswer = 1;
	                }
	                $comments->append($comment);
	            }
	        } else {
	            foreach ($commentsRaw as $comment) {
	                if (($comment->public == 0) && ($comment->user_id != $currentUser['id'])) {
	                    continue;
	                }
	                $comments->append($comment);
	            }
	        }

	        // Fetch Child
	        foreach ($comments as $comment) {
	            $comment->child = '';
	            $comment->hasChild = '';
	            if ($child = Comment::first(array('conditions' => array('question_id' => $comment->id), 'with' => array('User')))) {
	                $avatarHelper = new AvatarHelper;
	                $child->avatar = $avatarHelper->show($child->user->data(), false, true);
	                $child->isChild = 1;
	                $comment->child = $child;
	                $comment->hasChild = 1;
	            }
	        }

	        return compact('comments', 'experts', 'pitch');
	    } else {
	        return false;
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

            $solutions = Solution::all(array('conditions' => array('pitch_id' => $this->request->id), 'with' => array('User'), 'order' => $order));
            $experts = Expert::all(array('conditions' => array('Expert.user_id' => array('>' => 0))));

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
                return compact('pitch', 'files', 'comments', 'prevpitch', 'solutions', 'experts');
            }else {
                //return compact('pitch', 'files');
                return $this->render(array('layout' => false, 'data' => compact('pitch', 'files', 'comments')));
            }
        }
        throw new Exception('Public:Такого питча не существует.', 404);
    }

    public function crowdsourcing() {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $pitches = Pitch::all(array('conditions' => array('status' => array('<' => 1), 'Pitch.awarded' => 0, 'published' => 1), 'order' => array('started' => 'desc'), 'with' => array('User')));
        foreach($pitches as $pitch) {
            $solution = Solution::first(array('conditions' => array(
                'pitch_id' => $pitch->id

            ), 'order' => array('created' => 'desc')));
            $pitch->solution = $solution;
        }
        return compact('pitches');
    }

	public function viewsolution() {
        //error_reporting(E_ALL);
        //ini_set('display_errors', '1');
		Solution::increaseView($this->request->id);
		if($solution = Solution::first(array('conditions' => array('Solution.id' => $this->request->id), 'with' => array('User', 'Pitch')))) {
            $validSorts = array('rating', 'created', 'likes');
            if((isset($this->request->query['sorting'])) && (in_array($this->request->query['sorting'], $validSorts))){
                switch($this->request->query['sorting']) {
                    case 'rating':
                        $sort = 'rating';
                        $order = array('nominated' => 'desc', 'rating' => 'desc');
                        break;
                    case 'created':
                        $sort = 'created';
                        $order = array('nominated' => 'desc', 'created' => 'desc');
                        break;
                    case 'likes':
                        $sort = 'likes';
                        $order = array('nominated' => 'desc', 'likes' => 'desc');
                        break;
                    default: break;
                }
            }else {
                $sort = 'created';
                $order = array('nominated' => 'desc', 'created' => 'desc');
            }

            $solution->description = nl2br($solution->description);

            $solutions = Solution::all(array('conditions' => array('pitch_id' => $solution->pitch_id), 'order' => $order));
            /*foreach($solutions->data() as $setSolution){
                if($solution->id == $setSolution['id']) {
                	if($item = prev($solutions)) {
                		$prev = $item;
                	}else {
                		$prev = array_pop(array_keys($solutions->data()));
                	}
                	$res = next($solutions);
                	var_dump($res);
                    if($item = next($solutions)) {
                		$next = $item;
                	}else {
                		$next = array_shift(array_keys($solutions->data()));
                	}
                    break;
                }
            }*/
            function getArrayNeighborsByKey($array, $findKey) {

			    if ( ! array_key_exists($findKey, $array)) {
			        return FALSE;
			    }

			    $select = $prevous = $next = NULL;

			    foreach($array as $key => $value) {
			        $thisValue = array($key => $value);
			        if ($key === $findKey) {
			           $select = key($thisValue);
			           continue;
			        }
			        if ($select !== NULL) {
			            $next = key($thisValue);
			            break;
			        }
			        $previous = key($thisValue);

			    }
			    if(!isset($previous)) {
			    	$previous = array_pop(array_keys($array));
			    }
			    if(!isset($next)) {
			    	$next = array_shift(array_keys($array));
			    }

			   return array(
			            'prev' => $previous,
			            'current' => $select,
			            'next' => $next
			    );

			}
			$results = getArrayNeighborsByKey($solutions->data(), (int) $solution->id);
			$next = $results['next'];
			$prev = $results['prev'];
            $comments = Comment::all(array('conditions' => array('pitch_id' => $solution->pitch->id), 'order' => array('Comment.created' => 'desc'), 'with' => array('User')));
            $comments = Comment::filterComments($solution->num, $comments);
            $comments = Comment::addAvatars($comments);
            $pitch = Pitch::first(array('conditions' => array('Pitch.id' => $solution->pitch_id), 'with' => array('User')));
            $experts = Expert::all(array('conditions' => array('Expert.user_id' => array('>' => 0))));
            if ($pitch->category_id == 7) {
                $expertsIds = array();
                foreach($experts as $expert) :
                    $expertsIds[] = $expert->user_id;
                endforeach;
                if((Session::read('user') == null) || ($solution->user_id != Session::read('user.id')) && (!in_array(Session::read('user.id'), $expertsIds)) && (!in_array(Session::read('user.id'), User::$admins)) && ($pitch->user_id != Session::read('user.id'))){
                    return $this->redirect('/pitches/view/' . $pitch->id);
                }
            }
            if ($pitch->private == 1) {
                $canViewPrivate = false;
                $currentUser = Session::read('user');
                if (!empty($currentUser) && (User::getAwardedSolutionNum($currentUser['id']) >= WINS_FOR_VIEW)) {
                    $canViewPrivate = true;
                }
                $expertsIds = array();
                foreach($experts as $expert) :
                    $expertsIds[] = $expert->user_id;
                endforeach;
                if((Session::read('user') == null) || ($solution->user_id != Session::read('user.id')) && (!in_array(Session::read('user.id'), $expertsIds)) && (!in_array(Session::read('user.id'), User::$admins)) && ($pitch->user_id != Session::read('user.id')) && !$canViewPrivate){
                    return $this->redirect('/pitches/view/' . $pitch->id);
                }
            }
            $selectedsolution = false;
            $nominatedSolutionOfThisPitch = Solution::first(array(
                'conditions' => array('nominated' => 1, 'pitch_id' => $pitch->id)
            ));
            if($nominatedSolutionOfThisPitch) {
                $selectedsolution = true;
            }
            $userData = unserialize($solution->user->{'userdata'});
            $copyrightedInfo = unserialize($solution->copyrightedInfo);
            foreach ($copyrightedInfo['source'] as $key => $value) {
                $copyrightedInfo['source'][$key] = Url::view($value);
            }
            $avatarHelper = new AvatarHelper;
            $userAvatar = $avatarHelper->show($solution->user->data(), false, true);
            $likes = false;
            if(Session::read('user')) {
                $like = Like::find('first', array('conditions' => array('solution_id' => $solution->id, 'user_id' => Session::read('user.id'))));
                if ($like) {
                    $likes = true;
                }
            } else {
                if (isset($_COOKIE['bmx_' . $solution->id]) && ($_COOKIE['bmx_' . $solution->id] == 'true')) {
                    $likes = true;
                }
            }
			//if($pitch->category_id != 7){
                return compact('pitch', 'solution', 'solutions', 'comments', 'prev', 'next', 'sort', 'selectedsolution', 'experts', 'userData', 'userAvatar', 'copyrightedInfo', 'likes');
            //}else{
                //return $this->render(array('template' => '/viewsolution-copy', 'data' => compact('pitch', 'solution', 'solutions', 'comments', 'prev', 'next', 'sort', 'selectedsolution')));
            //}
		}else {
		    throw new Exception('Public:Такого решения не существует.', 404);
        }
	}

    public function upload() {
        \lithium\net\http\Media::type('json', array('text/html'));
        if(($this->request->id > 0) && ($pitch =  Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) && ($pitch->status == 0)) {
            if(($pitch->status != 0) || ($pitch->published != 1)) {
                $this->redirect(array('Pitches::view', 'id' => $pitch->id));
            }
            $currentUser = Session::read('user.id');
            if(($pitch->published == 0) && (($currentUser != $pitch->user_id) && (!in_array($currentUser, User::$admins)))) {
                return $this->redirect('/pitches');
            }

            if(($this->request->data)) {
                if((isset($this->request->data['solution'])) && (is_array($this->request->data['solution'])) && ((isset($this->request->data['solution'][0]) || ($this->request->data['solution']['error'] == 0)))) {
                        $this->request->data['pitch_id'] = $this->request->id;
                        $this->request->data['user_id'] = Session::read('user.id');
                        $result = Solution::uploadSolution($this->request->data);
                        if($result) {
                            return $this->render(array('data' => array('json' => $result->data())));
                        }else {
                            return false;
                        }

                }else {
                    return 'nofile';
                }
            }
            if($pitch->category_id != 7){
                return compact('pitch');
            }else{
                return $this->render(array('template' => '/upload-copy', 'data' => array('pitch' => $pitch)));
            }
        }
        throw new Exception('Public:Такого питча не существует.', 404);
    }

    public function uploadcopy() {
        if(($this->request->id > 0) && ($pitch =  Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) && ($pitch->status == 0)) {
            if(($pitch->status != 0) || ($pitch->published != 1)) {
                $this->redirect(array('Pitches::view', 'id' => $pitch->id));
            }
            $currentUser = Session::read('user.id');
            if(($pitch->published == 0) && (($currentUser != $pitch->user_id) && (!in_array($currentUser, User::$admins)))) {
                return $this->redirect('/pitches');
            }
            if(($this->request->data)) {
                $this->request->data['pitch_id'] = $this->request->id;
                $this->request->data['user_id'] = Session::read('user.id');
                $result = Solution::uploadSolution($this->request->data);
                if($result) {
                    return $result->data();
                }else {
                    return false;
                }
                /*if((isset($this->request->data['solution'])) && (is_array($this->request->data['solution'])) && ((isset($this->request->data['solution'][0]) || ($this->request->data['solution']['error'] == 0)))) {
                        $this->request->data['pitch_id'] = $this->request->id;
                        $this->request->data['user_id'] = Session::read('user.id');
                        $result = Solution::uploadSolution($this->request->data);
                        if($result) {
                            return $result->data();
                        }else {
                            return false;
                        }

                }*/
            }
            if($pitch->category_id != 7){
                return compact('pitch');
            }else{
                return $this->render(array('template' => '/upload-copy', 'data' => array('pitch' => $pitch)));
            }
        }else {

        }
    }

    public function getlatestsolution() {
        if(!isset($this->request->query['category_id'])) {
            $this->request->query['category_id'] = null;
        }
        $result = Pitch::apiGetPitch($this->request->query['category_id']);
        return $this->render(array('text' => 'writePitch(' . json_encode($result) . ')'));
    }

    public function getpdf() {
        if (($pitch = Pitch::first($this->request->id)) && ($bill = Bill::first($this->request->id))) {
            if (Session::read('user.id') != $pitch->user_id) {
                die();
            }
            require_once(LITHIUM_APP_PATH . '/' . 'libraries' . '/' . 'MPDF54/MPDF54/mpdf.php');
            $options = compact('pitch', 'bill');
            $mpdf = new \mPDF();
            $mpdf->WriteHTML(PdfGetter::get('Bill', $options));
            $mpdf->Output('godesigner-pitch-' . $pitch->id . '.pdf', 'd');
            exit;
        }
        die();
    }

    public function getPdfAct() {
        if (($pitch = Pitch::first($this->request->id)) && ($bill = Bill::first($this->request->id))) {
            if (Session::read('user.id') != $pitch->user_id && !User::checkRole('admin')) {
                die();
            }
            $destination = 'Download';
            $options = compact('pitch', 'bill', 'destination');
            Pitch::generatePdfAct($options);
            exit;
        }
        die();
    }

    public function getPdfReport() {
        if (($pitch = Pitch::first($this->request->id)) && ($bill = Bill::first($this->request->id))) {
            if (Session::read('user.id') != $pitch->user_id && !User::checkRole('admin')) {
                die();
            }
            $destination = 'Download';
            $options = compact('pitch', 'bill', 'destination');
            Pitch::generatePdfReport($options);
            exit;
        }
        die();
    }

    public function addon() {
        $pitch = Pitch::first($this->request->id);
        $experts = Expert::all(array('order' => array('id' => 'asc')));
        return compact('pitch', 'experts');
    }

    public function addmoney() {
        $pitch = Pitch::first($this->request->id);
        if($pitch->id != '101534') {
            return $this->redirect('/');
        }else {
            $addon = Addon::first(array('conditions' => array('reward' => '10120')));
        }
    }

    public function promocode() {
        Pitch::dailypitch();
        die();
    }
}