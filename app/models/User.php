<?php

namespace app\models;
use \lithium\util\Validator;
use \lithium\util\String;
use \lithium\storage\Session;

use \app\models\Expert;
use \app\models\Promocode;
use \app\models\Pitch;
use \app\models\Favourite;
use \app\models\Solution;
use \app\models\Wincomment;
use \app\models\Comment;
use \app\extensions\mailers\SpamMailer;
use \app\extensions\helper\NameInflector;
use \app\extensions\smsfeedback\SmsFeedback;
use \tmhOAuth\tmhOAuth;
use \tmhOAuth\tmhUtilities;
use app\extensions\mailers\CommentsMailer;

use \DirectoryIterator;

class User extends \app\models\AppModel {

	public $hasOne = array();
	public $hasMany = array('Pitch');

	/**
	 * Fill Admin role user's ids here
	 *
	 * @var array
	 */
    public static $admins = array(32, 4, 5, 108, 81);
    public static $experts = array();

    /**
     * Fill Editor role user's ids here
     *
     * @var array
     */
    public static $editors = array(32, 4, 5, 108, 81, 3049);

    /**
     * Массив хранящий айдишники авторов блога
     *
     * @var array
     */
    public static $authors = array(8472);

    protected static $_behaviors = array(
        'UploadableAvatar'
    );

    public static $attaches = array('avatar' => array(
        'moveFile' => array('preserveFileName' => false, 'path' => '/webroot/avatars/'),
        'setPermission' => array('mode' => 0600),
        'processImage' => array(
            //'largest' => array('image_resize' => true, 'image_ratio_crop' => true, 'image_x' => 960, 'image_y' => 740, 'file_overwrite' => true),
            'small' => array('image_resize' => true, 'image_x' => 41,  'image_y' => 41, 'image_ratio_crop' => 'T', 'file_overwrite' => true),
            //'gallerySmallSize' => array('image_resize' => true, 'image_ratio_crop' => 'T', 'image_x' => 99, 'image_y' => 75, 'file_overwrite' => true),
            'normal' => array('image_resize' => true, 'image_x' => 180, 'image_y' => 180, 'image_ratio_crop' => 'T', 'file_overwrite' => true),
            /*'galleryLargeSize' => array('image_resize' => true, 'image_ratio_crop' => 'TB', 'image_x' => 179, 'image_y' => 135, 'file_overwrite' => true),*/
            //'promoSize' => array('image_resize' => true, 'image_ratio_crop' => 'T', 'image_x' => 259, 'image_y' => 258, 'file_overwrite' => true),
        ),
    ));

	public static function __init() {
		parent::__init();
		self::applyFilter('save', function($self, $params, $chain){
		    $record = $params['entity'];
		    if (!$record->id) {
		    	if(empty($record->password)) {
		    		if(!isset($params['data']['password'])) {
		    			$params['data']['password'] = $password = $comfirmPassword = '';
					}else {
						$password = $params['data']['password'];
						$comfirmPassword = $params['data']['confirm_password'];
					}
		    	}else {
		    		$password = $record->password;
					$comfirmPassword = $record->confirm_password;
		    	}
		        $params['data']['password'] = String::hash($password);
				$params['data']['confirm_password'] = String::hash($comfirmPassword);
		    }
		    if (!empty($params['data'])) {
		        $record->set($params['data']);
		    }
		    $params['entity'] = $record;
		    return $chain->next($self, $params, $chain);
		});
	}

	public $validates = array(
		'password' => array(
			array('notEmpty', 'message' => 'Требуется пароль'),
			array('passwordConfirmed', 'message' => 'Пароли не совпадают',),
		),
		'first_name' => array(
			array('notEmpty', 'message' => 'Имя обязательно'),
		),
		'last_name' => array(
			array('notEmpty', 'message' => 'Фамилия обязетальна'),
		),
	    'email' => array(
	    	array('userUniqueEmail', 'message' => 'Email уже занят'),
	        array('notEmpty', 'message' => 'Email обязателен'),
	        array('email', 'message' => 'Email обязателен'),
		)
	);

    public function checkFacebookUser($entity, $data) {
        $conditions = array(
            'facebook_uid' => $data['facebook_uid']
        );
        return (bool) (self::find('first', array('conditions' => $conditions)));
    }

    public function isUserExistsByEmail($entity, $email) {
        $conditions = array(
            'email' => $email,
            'facebook_uid' => ''
        );
        return (bool) (self::find('first', array('conditions' => $conditions)));
    }

    public function saveFacebookUser($entity, $data) {
        $saveData = array(
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name'=> $data['first_name'],
            'facebook_uid'=> $data['facebook_uid'],
            'confirmed_email' => 1,
            'created' => date('Y-m-d H:i:s'),
        );
        if($entity->save($saveData, array(
            'first_name' => array(
                array('notEmpty', 'message' => 'Имя обязательно'),
            ),
            'last_name' => array(
                array('notEmpty', 'message' => 'Фамилия обязетальна'),
            ),
            'email' => array(
                array('userUniqueEmail', 'message' => 'Email уже занят'),
                array('notEmpty', 'message' => 'Email обязателен'),
                array('email', 'message' => 'Email обязателен'),
            )
        ))) {
            return true;
        }else {
            return false;
        }
    }

    /**
     * Get and store Facebook user avatar
     *
     * @return array
     */
    public function getFbAvatar($entity) {
        $id = $entity->facebook_uid;
        $userpic = file_get_contents('http://graph.facebook.com/' . $id . '/picture?type=large');
        $tmp = array_search('uri', @array_flip(stream_get_meta_data($GLOBALS[mt_rand()]=tmpfile())));

        file_put_contents($tmp, $userpic);
        $imageData = getimagesize($tmp);
        switch ($imageData['mime']) {
            case 'image/gif':
                $filename = uniqid() . '.gif';
                break;
            case 'image/jpeg':
                $filename = uniqid() . '.jpg';
                break;
            case 'image/png':
                $filename = uniqid() . '.png';
                break;
            default:
                return array('error' => 'nouserpic set');
                break;
        }
        Avatar::clearOldAvatars(Session::read('user.id'));
        $entity->set(array('avatar' => array('name' => $filename, 'tmp_name' => $tmp, 'error' => 0)));
        $entity->save();
        return array('result' => 'true');
    }

    public function unsubscribeToken($entity) {
        $from = 'from=' . base64_encode($entity->email);
        $token = base64_encode(sha1($entity->id . $entity->created));
        return '?token=' . $token . '&' . $from;
    }

	public function generateToken() {
		return uniqid();
	}

	public function generatePassword() {
		return substr(md5(rand().rand()), 0, 14);
	}

    public static function generateReferalToken($length = 4) {
        $exists = true;
        while ($exists == true) {
            $token = substr(md5(rand().rand()), 0, $length);
            if (!self::first(array('conditions' => array('referal_token' => $token)))) {
                $exists = false;
            }
        }
        return $token;
    }

	public function activateUser($entity) {
		$entity->token = '';
		$entity->confirmed_email = 1;
		$res = $entity->save(null, array('validate' => false));
	}

    public static function getSubscribedPitches($userId) {
        $pitches = Pitch::find('all', array('conditions' =>
        	array('user_id' => $userId),
        	 ));
        $pitchesIds = array();
        foreach($pitches as $pitch) {
            $pitchesIds[$pitch->id . ''] = $pitch->started;

        }
        $fav = Favourite::find('all', array('conditions' => array('Favourite.user_id' => $userId), 'order' => array('id' => 'desc'), 'with' => array('Pitch')));
        foreach($fav as $f) {
            $pitchesIds[$f->pitch->id . ''] = $f->created;

        }
        $solutions = Solution::find('all', array('conditions' => array('Solution.user_id' => $userId), 'order' => array('id' => 'desc'), 'with' => array('Pitch')));
        foreach($solutions as $s) {
            $pitchesIds[$s->pitch->id . ''] = $s->created;

        }
        ksort($pitchesIds);
        return $pitchesIds;
    }

    /**
     * Checks if the User is in specified Role
     *
     * @return boolean
     */
    public static function checkRole($role) {
        $res = false;
        switch ($role) {
            case 'admin':
                $res = in_array(Session::read('user.id'), self::$admins);;
                break;
            case 'editor':
                $res = in_array(Session::read('user.id'), self::$editors);
                break;
            case 'author':
                $res = in_array(Session::read('user.id'), self::$authors);
                break;
            default:
                $res = false;
                break;
        }

        return $res;
    }

    public static function getParticipatePitches($userId) {
        $pitches = Pitch::find('all', array('conditions' =>
        array('user_id' => $userId),
        ));
        $pitchesIds = array();
        foreach($pitches as $pitch) {
            $pitchesIds[$pitch->id . ''] = $pitch->started;

        }
        //var_dump($pitches->data());die();
        $solutions = Solution::find('all', array('conditions' => array('Solution.user_id' => $userId), 'order' => array('id' => 'desc'),  'with' => array('Pitch')));
        foreach($solutions as $s) {
            //$pitchesIds[$s->pitch->id . ''] = $s->created;
            $pitchesIds[$s->pitch->id . ''] = $s->pitch->started;
        }
        ksort($pitchesIds);
        return $pitchesIds;
    }

    public static function getFavouritePitches($userId) {
        $fav = Favourite::find('all', array('conditions' => array('Favourite.user_id' => $userId), 'with' => array('Pitch')));
        $pitchesIds = array();
        foreach($fav as $f) {
            $pitchesIds[$f->pitch->id . ''] = $f->created;

        }
        ksort($pitchesIds);
        return $pitchesIds;
    }

    public static function getTotalSolutionNum($userId) {
        $count = Solution::count(array('conditions' => array('user_id' => $userId)));
        return $count;
    }

    public static function getAwardedSolutionNum($userId) {
        $count = Solution::count(array('conditions' => array('user_id' => $userId, 'OR' => array(array('awarded' => 1), array('nominated' => 1)))));
        return $count;
    }

    public static function getTotalLikes($userId) {
        $all = Solution::all(array('conditions' => array('user_id' => $userId), 'fields' => array('SUM(likes) as totalLikes')));
        return $all->first()->totalLikes;
    }

    public static function getTotalViews($userId) {
        $all = Solution::all(array('conditions' => array('user_id' => $userId), 'fields' => array('SUM(views) as totalViews')));
        return $all->first()->totalViews;
    }

    public static function getAverageGrade($userId) {
        $all = Solution::all(array('conditions' => array('user_id' => $userId, 'awarded' => 1)));
        $pitches = array();
        foreach($all as $solution) {
            $pitches[] = $solution->pitch_id;
        }
        $userGrades = array();
        if(!empty($pitches)) {
            $grades = Grade::all(array('conditions' => array('type' => 'client', 'pitch_id' => $pitches)));

            foreach($grades as $grade) {
                $userGrades[] = $grade->partner_rating;
            }
        }
        $pitches = array();
        $userPitches = Pitch::all(array('conditions' => array('user_id' => $userId, 'status' => 2)));
        foreach($userPitches as $pitch) {
            $pitches[] = $pitch->id;
        }
        if(!empty($pitches)) {
            $grades = Grade::all(array('conditions' => array('type' => 'designer', 'pitch_id' => $pitches)));
            $userGrades = array();
            foreach($grades as $grade) {
                $userGrades[] = $grade->partner_rating;
            }
        }
        if(count($userGrades) > 0) {
            return (string)round(array_sum($userGrades) / count($userGrades), 1);
        }else {
            return false;
        }
    }

    public static function getPitchCount($userId) {
        $pitches = Pitch::count(array('conditions' => array('user_id' => $userId, 'published' => 2)));
        $participatedPitches = count(Solution::all(array('conditions' => array('user_id' => $userId), 'group' => array('pitch_id'))));
        return $pitches + $participatedPitches;
    }

    public static function getDesignersForSpam($category) {
        // Designers
        $result1 = array();
        if ($category != 7) {
            $users1 = self::all(array(
                'fields' => array('id'),
                'conditions' => array(
                    array('isDesigner' => 1, 'email_newpitch' => 1)
                )
            ));
            $result1 = $users1->data();
        }

        // All but pitches owners
        $users2 = self::all(array(
            'conditions' => array(
                array('isDesigner' => 0, 'isClient' => 0, 'isCopy' => 0, 'email_newpitch' => 1)
            ),
            'with' => array('Pitch')
        ));

        $ids = array();
        foreach($users2 as $user) {
            if(count($user->pitches) > 0) {
                if(is_null($user->pitches[0]->id)) {
                    $ids[] = $user->id;
                }
            }
        }
        $result2 = array();
        if(!empty($ids)) {
            $users2 = self::all(array(
                'conditions' => array(
                    array('id' => $ids, 'isDesigner' => 0, 'isClient' => 0, 'isCopy' => 0, 'email_newpitch' => 1)
                ),
            ));
            $result2 = $users2->data();
        }

        // Copywriters
        $result3 = array();
        if ($category == 7) {
            $users3 = self::all(array(
                'fields' => array('id'),
                'conditions' => array(
                    array('isCopy' => 1, 'email_newpitch' => 1)
                )
            ));
            $result3 = $users3->data();
        }

        if((!empty($result1)) || (!empty($result2)) || (!empty($result3))) {
            $temp1 = array_keys($result1);
            $temp2 = array_keys($result2);
            $temp3 = array_keys($result3);
            return array_merge($temp1, $temp2, $temp3);
        }else {
            return array();
        }
    }

    /// !!!!
    public static function sendSpamNewPitch($params) {
        $recipientsIds = self::getDesignersForSpam($params['pitch']->category_id);
        $recipientsIds = array_unique($recipientsIds);
        foreach($recipientsIds as $person){
            $user = self::first($person);
            $data = array('user' => $user, 'pitch' => $params['pitch']);
            SpamMailer::newpitch($data);
        }
        $user = new \stdClass();
        $user->email = 'team@godesigner.ru';
        $user->first_name = 'godesigner.ru';
        $data = array('user' => $user, 'pitch' => $params['pitch']);
        SpamMailer::newpitch($data);
        return true;
    }

    public static function sendClientSpamNewPitch($params) {
        $user = self::first($params['pitch']->user_id);
        if($params['pitch']->brief == 0) {
            $text = 'Ваша оплата прошла успешно и питч опубликован на сайте. Как только дизайнеры загрузят загрузят свои решения, комментируйте их, выставляйте рейтинг и помогайте лучше понять вас, и тогда вы точно получите то, что хотели.';
        }else {
            $text = 'Ваша оплата прошла успешно. Так как вы заказали опцию “Заполнить бриф”, то питч еще не опубликован на сайте. Мы свяжемся с вами в течение рабочего дня по телефону, указанному в брифе, сформулируем тех.задание для дизайнеров по результатам нашей беседы и выложим конкурс на сайт. ';
        }
        $data = array('user' => $user, 'pitch' => $params['pitch'], 'text' => $text);
        SpamMailer::newclientpitch($data);
    }

    public static function sendSpamNewcomment($params) {
        $pitch = Pitch::first($params['pitch_id']);
        $solution = Solution::first($params['solution_id']);
        $user = self::first(array(
            'conditions' => array('id' => $solution->user_id),
        ));
        if(($user->email_newcomments == 1) && ($params['user_id'] != $solution->user_id)){
            $data = array('user' => $user, 'pitch' => $pitch, 'comment' => $params);
            SpamMailer::newcomment($data);
        }
        return true;
    }

    public static function sendAdminBriefPitch($params) {
        $users = self::all(array('conditions' => array('id' => array(4, 5, 32))));
        foreach($users as $user) {
            $text = 'На сайт добавлен новый питч с опцией "заполнить бриф"';
            $data = array('user' => $user, 'pitch' => $params['pitch'], 'text' => $text);
            SpamMailer::newbriefedpitch($data);
        }
    }

    public static function sendAdminModeratedPitch($pitch) {
        $users = self::all(array('conditions' => array('id' => array(4, 5, 32))));
        foreach($users as $user) {
            $data = array('user' => $user, 'pitch' => $pitch);
            SpamMailer::newmoderatedpitch($data);
        }
    }

    public static function sendAdminNewAddon($addon) {
        $users = self::all(array('conditions' => array('id' => array(4, 5, 32))));
        $pitch = Pitch::first($addon->pitch_id);
        foreach($users as $user) {
            $data = array('user' => $user, 'addon' => $addon, 'pitchName' => $pitch->title);
            SpamMailer::newaddon($data);
        }
    }

    public static function sendAdminNewAddonBrief($addon) {
        $users = self::all(array('conditions' => array('id' => array(4, 5, 32))));
        $pitch = Pitch::first($addon->pitch_id);
        foreach($users as $user) {
            $data = array('user' => $user, 'addon' => $addon, 'pitchName' => $pitch->title);
            SpamMailer::newaddonbrief($data);
        }
    }

    public static function sendSpamWincomment($comment, $recipient) {
        $solution = Solution::first($comment->solution_id);
        $pitch = Pitch::first($solution->pitch_id);
        $data = array('user' => $recipient, 'pitch' => $pitch, 'solution' => $solution, 'comment' => $comment);
        SpamMailer::newwincomment($data);
        return true;
    }

    public static function sendSpamWinstep($user, $solution, $step) {
        $pitch = Pitch::first($solution->pitch_id);
        if($step == 3) {
            $text = 'Ваши макеты были одобрены, вы переходите на следующую стадию предоставления исходников.';
        }
        if($step == 4) {
            $text = 'Ваши исходники одобрены заказчиком, вы переходите на стадию выставления оценок.';
        }
        $data = array('user' => $user, 'pitch' => $pitch, 'text' => $text, 'solution' => $solution);
        SpamMailer::winstep($data);
        return true;
    }

    public static function sendSpamWinstepGo($user, $solution, $step) {
        $pitch = Pitch::first($solution->pitch_id);
        $text = 'Ваши исходники одобрены администрацией GoDesigner, вы переходите на стадию выставления оценок.';
        $data = array('user' => $user, 'pitch' => $pitch, 'text' => $text, 'solution' => $solution);
        SpamMailer::winstep($data);
        return true;
    }

    public static function sendPersonalComment($params) {
        $user = User::first($params['reply_to']);
        $pitch = Pitch::first($params['pitch_id']);
        if($user->email_newcomments == 1) {
            $data = array('user' => $user, 'pitch' => $pitch, 'comment' => $params);
            SpamMailer::newpersonalcomment($data);
        }
        return true;
    }

    public static function sendAdminNotification($params) {
        $admin = 'fedchenko@godesigner.ru';
        $pitch = Pitch::first($params['pitch_id']);
        $data = array('admin' => $admin, 'pitch' => $pitch, 'comment' => $params);
        SpamMailer::newadminnotification($data);
        return true;
    }

    public static function sendPromoCode($userId) {
        $user = User::first(array('conditions' => array('id' => $userId)));
        $code = Promocode::createPromocode($user->id);
        $data = array('user' => $user, 'promocode' => $code);
        SpamMailer::promocode($data);
    }

    public static function sendDailyPitch($userId, $pitches) {
        $user = User::first(array('conditions' => array('id' => $userId)));
        //$user = User::first(array('conditions' => array('email' => 'nyudmitriy@gmail.com')));
        $data = array('user' => $user, 'pitches' => $pitches);
        SpamMailer::dailypitch($data);
    }

    public static function sendOpenLetter($pitch) {
        $data = array('user' => $pitch->user, 'pitch' => $pitch);
        return SpamMailer::openletter($data);
    }

    public static function sendExpertMail($addon) {
        $data = array('pitch' => Pitch::first($addon->pitch_id));
        $experts = unserialize($addon->{'expert-ids'});
        foreach ($experts as $expert) {
            $expert = Expert::first(array(
                'conditions' => array(
                    'Expert.id' => $expert,
                ),
                'with' => array('User'),
            ));
            $data['user'] = $expert->user;
            SpamMailer::expertselected($data);
        }
        return true;
    }

    public static function sendSpamFirstSolutionForPitch($pitchId) {
        $pitch = Pitch::first($pitchId);
        $user = self::first($pitch->user_id);
        $data = array('user' => $user, 'pitch' => $pitch);
        SpamMailer::firstsolution($data);
        return true;
    }

    public static function sendSpamReferal() {
        $users = self::all();
        $sent = 0;
        foreach ($users as $user) {
            if (!empty($user->email)) {
                $data = array(
                    'email' => $user->email,
                    'subject' => 'Запуск партнёрской программы',
                );
                SpamMailer::referalspam($data);
                $sent++;
            }
        }
        return $sent;
    }

    public static function getAdmin() {
        $admin = self::first(array('conditions' => array('isAdmin' => 1)));
        return $admin->id;
    }

    public static function sendDailyDigest() {
        $activePitches = Pitch::all(array('conditions' => array('status' => 0, 'published' => 1)));
        $ids = array();
        foreach($activePitches as $pitch) {
            $ids[] = $pitch->user_id;
        }
        foreach($ids as $id) {
            self::getDailyDigest($id);
        }
        return $ids;
    }

    public static function getDailyDigest($userId) {
        $user = self::first($userId);
        $pitches = Pitch::all(array('conditions' => array(
            'status' => 0,
            'published' => 1,
            'user_id' => $user->id
        )));
        $start = date('Y-m-d H:i:s', (time() - DAY));
        //$start = date('Y-m-d H:i:s', (time() - YEAR));
        $blocks = array();
        $nameInflector = new nameInflector();
        foreach($pitches as $pitch) {
            $solutions = Solution::all(array('conditions' => array('pitch_id' => $pitch->id, 'created' => array('>' => $start))));
            $blocks[$pitch->id] = array('pitch' => $pitch, 'solutions' => null, 'comments' => null);
            if(count($solutions) > 0) {
                $solArray = array();
                foreach($solutions as $solution) {
                    $solArray[] = '<a style="color: #7ea0ac; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;"" href="http://www.godesigner.ru/pitches/viewsolution/' . $solution->id . '" target="_blank">#' . $solution->num . '</a>';
                }
                $blocks[$pitch->id]['solutions'] = 'Для вашего питча выложены новые решения: ' . implode(', ', $solArray) . '. Комментируйте идеи, выставляйте рейтинг (звезды), помогайте дизайнерам лучше понять вас, и тогда вы обязательно получите то, что хотели!';
            }else {
                $blocks[$pitch->id]['solutions'] = '';
            }
            $comments = Comment::all(array('conditions' => array('pitch_id' => $pitch->id, 'Comment.created' => array('>' => $start)), 'with' => array('User')));
            if(count($comments) > 0) {
                $comArray = array();
                foreach($comments as $comment) {
                    if($comment->user_id == $pitch->user_id){
                        continue;
                    }
                    if($comment->user->isAdmin != 1) {
                        $comArray[] = $nameInflector->renderName($comment->user->first_name, $comment->user->last_name) . ': ' . $comment->text;
                    }else {
                        $comArray[] = 'GoDesigner: ' . $comment->text;
                    }
                }
                $blocks[$pitch->id]['comments'] =  implode('<br/><br/>', $comArray);
            }else {
                $blocks[$pitch->id]['comments'] = '';
            }
            if((count($comments) == 0) && (count($solutions) == 0)){
                unset($blocks[$pitch->id]);
            }
        }
        if(empty($blocks)) {
            return true;
        }
        $data = array('user' => $user, 'blocks' => $blocks);
        SpamMailer::dailydigest($data);
        return true;
    }

    public static function sendSpamToLostClients() {
        $pitches = Pitch::all(array('conditions' => array(
            'status' => 0,
            'published' => 1,
        )));
        $ids = array();
        foreach($pitches as $pitch) {
            $ids[] = $pitch->user_id;
        }
        $userArray = array();
        foreach($ids as $id) {
            $user = User::first($id);
            $start = time() - (DAY * 3);
            if(strtotime($user->lastActionTime) < $start ) {
                $userArray[] = $user;
            }
        }
        foreach($userArray as $user) {
            $pitch = Pitch::first(array('conditions' => array('user_id' => $user->id, 'status' => 0, 'published' => 1)));
            $data = array('user' => $user, 'pitch' => $pitch, 'text' => 'Мы просим вас принимать более активное участие в процессе проведения питча. Комментируйте предлагаемые вам идеи, выставляйте рейтинг (звезды), отвечайте на вопросы и помогайте дизайнерам лучше понять вас, и тогда вы обязательно получите то, что хотели!');
            SpamMailer::comeback($data);
        }
        return count($userArray);
    }


    public static function sendChooseWinnerSpam() {
        $pitches = Pitch::all(array('conditions' => array('status' => 1, 'awarded' => 0, 'finishDate' => array('<' => date('Y-m-d H:i:s', time() - DAY)))));
        $ids = array();
        foreach($pitches as $pitch) {
            $user = User::first($pitch->user_id);
            $ids[] = $pitch->user_id;
            if($pitch->expert == 1) {
                $text = 'Срок выбора победителя подошел к концу. Вам необходимо срочно номинировать лучшее решение!<br/>Дизайнеры больше не могут добавлять и комментировать решения.<br/>
В случае, если предложенные идеи вам не понравились, мы инициируем возврат средств. Для этого необходимо в срок до 3 рабочих дней после того, как эксперты выскажут своё мнение, оставить комментарий в галерее работ и объяснить дизайнерам, что эти идеи вам не подходят. Решение о возврате необходимо принять в течение 3 рабочих дней после окончания срока питча, в противном случае такая возможность будет недоступна.';
            }else {
                $text = 'Срок выбора победителя подошел к концу. Вам необходимо срочно номинировать лучшее решение!<br/>Дизайнеры больше не могут добавлять и комментировать решения.<br/>
В случае, если предложенные идеи вам не понравились, мы инициируем возврат средств. Для этого необходимо в срок до 3 рабочих дней после завершения питча оставить комментарий в галерее работ и объяснить дизайнерам, что эти идеи вам не подходят. Решение о возврате необходимо принять в течение 3 рабочих дней после окончания срока питча, в противном случае такая возможность будет недоступна.';
            }
            if($pitch->guaranteed == 1) {
                if($pitch->expert == 1) {
                    $text = 'Срок выбора победителя подошел к концу. Вам необходимо срочно номинировать лучшее решение!<br/>Дизайнеры больше не могут добавлять и комментировать решения. На выбор победителя у вас есть три рабочих дня после того, как все выбранные вами эксперты выскажут своё мнение.';
                }else {
                    $text = 'Срок выбора победителя подошел к концу. Вам необходимо срочно номинировать лучшее решение!<br/>Дизайнеры больше не могут добавлять и комментировать решения. На выбор победителя у вас есть три рабочих дня с момента окончания срока питча.';
                }
            }
            $data = array('user' => $user, 'pitch' => $pitch, 'text' => $text);
            SpamMailer::choosewinner($data);
        }
        return $ids;
    }

    public static function sendStep2Spam() {
        $pitches = Pitch::all(array('conditions' => array('status' => 1, 'awarded' => array('>' => 0), 'finishDate' => array('<' => date('Y-m-d H:i:s', time() - DAY)))));
        $pitchesToSpam = array();
        foreach($pitches as $pitch) {
            $solution = Solution::first($pitch->awarded);
            $files = array();
            $comments = Wincomment::all(array('conditions' => array('step' => 2, 'solution_id' => $solution->id), 'order' => array('created' => 'asc'), 'with' => array('User')));
            foreach($comments as $comment) {
                if($comment->user_id == $solution->user_id) {
                    if(!empty($comment->images)) {
                        $files[] = $comment->images;
                    }
                }
            }
            if(empty($files)) {
                $nofiles = true;
            }else {
                $nofiles = false;
            }
            if(($solution->step < 3) && ($nofiles == false)){
                $pitchesToSpam[] = $pitch;
            }
        }
        foreach($pitchesToSpam as $pitch) {
            $user = User::first($pitch->user_id);
            $solution = Solution::first($pitch->awarded);
            $data = array('user' => $user, 'pitch' => $pitch, 'solution' => $solution, 'text' => 'На этапе доработки макетов вы должны внести поправки и утвердить макеты. Для того, чтобы дизайнер получил денежное вознаграждение, вы должны полностью пройти завершающий этап. Пожалуйста, не затягивайте рабочий процесс и перейдите на следующую стадию, нажав «Одобрить макеты».');
            SpamMailer::step2($data);
        }
    }

    public static function sendStep3Spam() {
        $pitches = Pitch::all(array('conditions' => array('status' => 1, 'awarded' => array('>' => 0), 'finishDate' => array('<' => date('Y-m-d H:i:s', time() - DAY)))));
        $pitchesToSpam = array();
        foreach($pitches as $pitch) {
            $solution = Solution::first($pitch->awarded);
            $files = array();
            $comments = Wincomment::all(array('conditions' => array('step' => 3, 'solution_id' => $solution->id), 'order' => array('created' => 'asc'), 'with' => array('User')));
            foreach($comments as $comment) {
                if($comment->user_id == $solution->user_id) {
                    if(!empty($comment->images)) {
                        $files[] = $comment->images;
                    }
                }
            }
            if(empty($files)) {
                $nofiles = true;
            }else {
                $nofiles = false;
            }
            if(($solution->step == 3) && ($nofiles == false)){
                $pitchesToSpam[] = $pitch;
            }
        }
        foreach($pitchesToSpam as $pitch) {
            $user = User::first($pitch->user_id);
            $solution = Solution::first($pitch->awarded);
            $data = array('user' => $user, 'pitch' => $pitch, 'solution' => $solution, 'text' => 'На этом этапе дизайнер должен предоставить вам рабочие файлы, указанные в брифе. Для того, чтобы дизайнер получил денежное вознаграждение, вы должны полностью пройти завершающий этап. Пожалуйста, не затягивайте рабочий процесс и перейдите на следующую стадию, нажав «Одобрить макеты».');
            SpamMailer::step3($data);
        }
    }

    public static function sendStep4Spam() {
        $pitches = Pitch::all(array('conditions' => array('status' => 1, 'awarded' => array('>' => 0), 'finishDate' => array('<' => date('Y-m-d H:i:s', time() - DAY)))));
        $pitchesToSpam = array();
        foreach($pitches as $pitch) {
            $solution = Solution::first($pitch->awarded);
            if($solution->step == 4) {
                $pitchesToSpam[] = $pitch;
            }
        }
        foreach($pitchesToSpam as $pitch) {
            $solution = Solution::first($pitch->awarded);
            $user = User::first($pitch->user_id);
            $data = array('user' => $user, 'pitch' => $pitch, 'solution' => $solution, 'text' => 'Для того, чтобы дизайнер получил денежное вознаграждение, вы должны полностью пройти завершающий этап. Проставив и рейтинг и нажав «Завершить», вы автоматически инициируете перевод вознаграждения дизайнеру. Деньги поступят ему на счёт в течении 4 рабочих дней. Пожалуйста, не затягивайте процесс вознаграждения!');
            SpamMailer::step4($data);
        }
    }

    public static function sendTweet($tweet) {
        require_once LITHIUM_APP_PATH . '/libraries/tmhOAuth/tmhOAuth.php';
        require_once LITHIUM_APP_PATH . '/libraries/tmhOAuth/tmhUtilities.php';

        $tmhOAuth = new tmhOAuth(array(
              'consumer_key'    => '8r9SEMoXAacbpnpjJ5v64A',
              'consumer_secret' => 'I1MP2x7guzDHG6NIB8m7FshhkoIuD6krZ6xpN4TSsk',
              'user_token'      => '513074899-IvVlKCCD0kEBicxjrLGLjW2Pb7ZiJd1ZjQB9mkvN',
              'user_secret'     => 'ldmaK6qmlzA3QJPQemmVWJGUpfST3YuxrzIbhaArQ9M'
            ));
        $code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array(
            'status' => $tweet,
        ));

        if ($code == 200) {
            $data = json_decode($tmhOAuth->response['response'], true);
            return true;
        }else {
            return false;
        }
    }

    public static function sendFinishReports($pitch) {
        $user = self::first($pitch->user_id);
        $path = LITHIUM_APP_PATH . '/' . 'libraries' . '/' . 'MPDF54/MPDF54/tmp/';
        $files = array();
        foreach (new DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot() || !$fileInfo->isFile() || (false == strpos($fileInfo->getFilename(), $pitch->id))) continue;
            $files[] = $path . $fileInfo->getFilename();
        }
        $data = array('user' => $user, 'pitch' => $pitch, 'files' => $files);
        SpamMailer::finishreports($data);
        return true;
    }

    public static function fillBalance($userId, $sum) {
        if ($user = self::first($userId)) {
            $user->balance += (int) $sum;
            $user->save(null, array('validate' => false,));
        }
        return true;
    }

    public static function phoneValidationStart($userId, $phone, $phoneOperator) {
        if (($user = self::first($userId)) && !empty($phone)) {
            $user->phone = $phone;
            $user->phone_operator = $phoneOperator;
            $user->phone_code = self::generatePhoneCode();
            $user->phone_valid = 0;
            $user->save(null, array('validate' => false));
            $respond = SmsFeedback::send($user->phone, $user->phone_code . ' - код для проверки');
            $phone_valid = 0;
            return compact('respond', 'phone', 'phone_valid');
        }
        return false;
    }

    public static function phoneValidationFinish($userId, $code) {
        if (($user = self::first($userId)) && !empty($user->phone) && !empty($user->phone_code)) {
            if ($code == $user->phone_code) {
                $user->phone_valid = 1;
                $user->phone_code = 0;
                $user->save(null, array('validate' => false));
                $phone = $user->phone;
                $phone_valid = 1;
                return compact('phone', 'phone_valid');
            }
        }
        return false;
    }

    protected static function generatePhoneCode($count = 5, $string = '0123456789') {
        return substr(str_shuffle($string), 0, $count);
    }

    public static function sendAddonProlong($pitch) {
        $data = array('user' => $pitch->user, 'pitch' => $pitch);
        return SpamMailer::duration($data);
    }

    public static function sendAddonExpert($pitch) {
        $data = array('user' => $pitch->user, 'pitch' => $pitch);
        return SpamMailer::expertaddon($data);
    }

    public static function sendAddonBrief($pitch) {
        $data = array('user' => $pitch->user, 'pitch' => $pitch);
        return SpamMailer::briefaddon($data);
    }

    public static function isReferalAllowed($userId) {
        $query = array(
            'conditions' => array(
                'id' => $userId,
                'created' => array(
                    '>' => '2013-09-25 23:59:59',
                ),
            ),
        );
        if ($user = self::first($query)) {
            $pitches = Pitch::count(array('conditions' => array('user_id' => $user->id)));
            return $pitches;
        }
        return 0;
    }

    public static function setReferalCookie($ref) {
        $userId = Session::read('user.id');
        if (is_null($userId)) { // User not registered
            if (!isset($_COOKIE['ref']) || ($_COOKIE['ref'] == '')) {
                setcookie('ref', $ref, strtotime('+1 month'), '/');
                $_COOKIE['ref'] = $ref;
            }
        } else { // User registered
            if (self::isReferalAllowed($userId) === 0) { // User good and no pitches. === is important!
                if ((!isset($_COOKIE['ref']) || ($_COOKIE['ref'] == '')) && (self::first(array(
                        'conditions' => array(
                            'id' => array(
                                '!=' => $userId,
                            ),
                            'referal_token' => $ref,
                        ))))) {
                    setcookie('ref', $ref, strtotime('+1 month'), '/');
                    $_COOKIE['ref'] = $ref;
                }
            }
        }
    }

    public function block($user) {
        $user->banned = 1;
        $user->save(null, array('validate' => false));

        // Remove Solutions
        $solutions = Solution::all(array(
            'conditions' => array(
                'Solution.user_id' => $user->id,
                'OR' => array(
                    '(`Pitch`.`status` = 0)',
                    '(`Pitch`.`status` = 1 AND `Pitch`.`awarded` = 0)',
                ),
            ),
            'with' => array('Pitch'),
        ));
        foreach ($solutions as $solution) {
            $solution->delete();
        }

        // Remove Comments
        $comments = Comment::all(array(
            'conditions' => array(
                'Comment.user_id' => $user->id,
                'OR' => array(
                    '(`Pitch`.`status` = 0)',
                    '(`Pitch`.`status` = 1 AND `Pitch`.`awarded` = 0)',
                ),
            ),
            'with' => array('Pitch'),
        ));
        foreach ($comments as $comment) {
            $comment->delete();
        }
    }

    /**
     * Метод возвращает список айдишников авторов блога
     *
     * @return array
     */
    public static function getAuthorsIds() {
        return self::$authors;
    }

    /**
     * Метод возвращает список айдишников админов
     *
     * @return array
     */
    public static function getAdminsIds() {
        return self::$admins;
    }

    /**
     * Метод возвращает список айдишников редакторов блога
     *
     * @return array
     */
    public static function getEditorsIds() {
        return self::$editors;
    }

}
?>
