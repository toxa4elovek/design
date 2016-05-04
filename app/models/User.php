<?php

namespace app\models;

use app\models\Pitch;
use lithium\net\http\Service;
use \lithium\util\Validator;
use \lithium\util\String;
use \lithium\storage\Session;
use \app\models\Expert;
use \app\models\Promocode;
use \app\models\Option;
use \app\models\Favourite;
use \app\models\Solution;
use \app\models\Wincomment;
use \app\models\Comment;
use \app\models\Test;
use \app\extensions\mailers\SpamMailer;
use \app\extensions\helper\NameInflector;
use \app\extensions\helper\PitchTitleFormatter;
use \app\extensions\smsfeedback\SmsFeedback;
use app\extensions\smsfeedback\SmsUslugi;
use app\extensions\mailers\CommentsMailer;
use app\extensions\storage\Rcache;
use \DirectoryIterator;
use \app\extensions\helper\MoneyFormatter;
use app\models\Facebook;
use app\models\Url;
use app\extensions\social\TwitterAPI;
use app\extensions\social\FacebookAPI;
use app\extensions\social\SocialMediaManager;
use app\models\SubscriptionPlan;
use \app\models\Category;
use \tmhOAuth\tmhOAuth;
use \tmhOAuth\tmhUtilities;
use lithium\data\collection\RecordSet;
use lithium\data\entity\Record;

/**
 * Class User
 * @package app\models
 * @method Record|null first(array $conditions) static
 * @method int count(array $conditions) static
 * @method RecordSet|null all(array $conditions = []) static
 */
class User extends AppModel
{

    /**
     * Fill Admin role user's ids here
     *
     * @var array
     */
    public static $admins = array(32, 4, 5, 108, 81, 8472, 47);
    public static $experts = array();

    /**
     * Fill Editor role user's ids here
     *
     * @var array
     */
    public static $editors = array(32, 4, 5, 108, 81, 3049, 120, 17865, 23759);

    /**
     * Массив хранящий айдишники авторов блога
     *
     * @var array
     */
    public static $authors = array(8472, 18856, 25252, 30454, 34461);

    /**
     * Массив хранящий айдишнки авторов ленты новостей
     *
     * @var array
     */
    public static $feedAuthors = array(17865, 108);

    protected static $_behaviors = array(
        'UploadableAvatar'
    );

    public static $attaches = array('avatar' => array(
        'moveFile' => array('preserveFileName' => false, 'path' => '/webroot/avatars/'),
        'setPermission' => array('mode' => 0600),
        'processImage' => array(
            'small' => array('image_resize' => true, 'image_x' => 41, 'image_y' => 41, 'image_ratio_crop' => 'T', 'file_overwrite' => true),
            'normal' => array('image_resize' => true, 'image_x' => 180, 'image_y' => 180, 'image_ratio_crop' => 'T', 'file_overwrite' => true),
        ),
    ));

    public static function __init()
    {
        parent::__init();
        self::applyFilter('save', function ($self, $params, $chain) {
            $record = $params['entity'];
            if (!$record->id) {
                if (empty($record->password)) {
                    if (!isset($params['data']['password'])) {
                        $params['data']['password'] = $password = $comfirmPassword = '';
                    } else {
                        $password = $params['data']['password'];
                        $comfirmPassword = $params['data']['confirm_password'];
                    }
                } else {
                    $password = $record->password;
                    $comfirmPassword = $record->confirm_password;
                }
                $params['data']['password'] = String::hash($password);
                $params['data']['confirm_password'] = String::hash($comfirmPassword);
            } else {
                if (isset($params['data']['avatar'])) {
                    $cacheKey = 'avatars_' . $record->id;
                    Rcache::delete($cacheKey);
                }
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

    public function checkFacebookUser($entity, $data)
    {
        $conditions = array(
            'facebook_uid' => $data['facebook_uid']
        );
        return (bool) (self::find('first', array('conditions' => $conditions)));
    }

    public function checkVkontakteUser($entity, $data)
    {
        $conditions = array(
            'vkontakte_uid' => $data['uid']
        );
        return (bool) (self::first(array('conditions' => $conditions)));
    }

    public function isUserExistsByEmail($entity, $email)
    {
        $conditions = array(
            'email' => $email,
            'facebook_uid' => ''
        );
        $fbUser = self::find('first', array('conditions' => $conditions));
        $conditions = array(
            'email' => $email,
            'vkontakte_uid' => ''
        );
        $vkUser = self::find('first', array('conditions' => $conditions));
        if ((!$fbUser) && (!$vkUser)) {
            return false;
        } else {
            return true;
        }
    }

    public function saveVkontakteUser($entity, $data)
    {
        $gender = 0;
        if (isset($data['gender']) && $data['gender'] == 2) {
            $gender = 1;
        } elseif (isset($data['gender']) && $data['gender'] == 1) {
            $gender = 2;
        }
        $screen_name = explode(' ', $data['screen_name']);
        $saveData = array(
            'email' => $data['email'],
            'last_name' => $screen_name[1],
            'first_name' => $screen_name[0],
            'vkontakte_uid' => $data['uid'],
            'confirmed_email' => 1,
            'created' => date('Y-m-d H:i:s'),
            'gender' => $gender
        );
        if ($entity->save($saveData, array(
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
        } else {
            return false;
        }
    }

    public function saveFacebookUser($entity, $data)
    {
        $gender = 0;
        if (isset($this->request->data['gender']) && $this->request->data['gender'] == 'male') {
            $gender = 1;
        } elseif (isset($this->request->data['gender']) && $this->request->data['gender'] == 'female') {
            $gender = 2;
        }
        $saveData = array(
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'facebook_uid' => $data['facebook_uid'],
            'confirmed_email' => 1,
            'created' => date('Y-m-d H:i:s'),
            'gender' => $gender
        );
        if ($entity->save($saveData, array(
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
        } else {
            return false;
        }
    }

    /**
     * Метод сохраняет и возвращяет аватарки (с вк)
     *
     * @param $userRecord
     * @return array
     */
    public function getVkAvatar($userRecord)
    {
        return Avatar::getVkAvatar($userRecord);
    }

    /**
     * Метод сохраняет и возвращяет аватарки (с фейсбука)
     *
     * @param $userRecord
     * @return array
     */
    public function getFbAvatar($userRecord)
    {
        return Avatar::getFbAvatar($userRecord);
    }

    public function unsubscribeToken($entity)
    {
        $from = 'from=' . base64_encode($entity->email);
        $token = base64_encode(sha1($entity->id . $entity->created));
        return '?token=' . $token . '&' . $from;
    }

    public function generateToken()
    {
        return uniqid();
    }

    public function generatePassword()
    {
        return substr(md5(rand() . rand()), 0, 14);
    }

    public static function generateReferalToken($length = 4)
    {
        $exists = true;
        while ($exists == true) {
            $token = substr(md5(rand() . rand()), 0, $length);
            if (!self::first(array('conditions' => array('referal_token' => $token)))) {
                $exists = false;
            }
        }
        return $token;
    }

    public function activateUser($entity)
    {
        $entity->token = '';
        $entity->confirmed_email = 1;
        $res = $entity->save(null, array('validate' => false));
    }

    public static function getSubscribedPitches($userId)
    {
        $pitches = Pitch::find('all', array('conditions' =>
                    array('user_id' => $userId, 'blank' => 0, 'status' => array('<' => 2)),
        ));
        $pitchesIds = [];
        foreach ($pitches as $pitch) {
            if(!$pitch->isCopyrighting()) {
                $pitchesIds[$pitch->id . ''] = $pitch->started;
            }
        }
        $fav = Favourite::find('all', array('conditions' => array('Favourite.user_id' => $userId), 'order' => array('id' => 'desc'), 'with' => array('Pitch')));
        foreach ($fav as $f) {
            $pitchesIds[$f->pitch->id . ''] = $f->created;
        }
        $solutions = Solution::find('all', array('conditions' => array('Solution.user_id' => $userId), 'order' => array('id' => 'desc'), 'with' => array('Pitch')));
        foreach ($solutions as $s) {
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
    public static function checkRole($role)
    {
        $res = false;
        switch ($role) {
            case 'admin':
                $res = in_array(Session::read('user.id'), self::$admins);
                ;
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

    public static function getUserRelatedPitches($userId, $awarded = false)
    {
        // сначала ищем созданные пользователем питчи
        $pitches = Pitch::find('all', array('conditions' =>
            array(
                'user_id' => $userId,
                'blank' => 0,
                'AND' => array(
                    array("Pitch.type != 'fund-balance'"),
                    array("Pitch.type != 'plan-payment'"),
                ),
            ),
        ));
        $pitchesIds = array();
        foreach ($pitches as $pitch) {
            $pitchesIds[$pitch->id . ''] = $pitch->started;
        }
        // затем ищем питчи, где пользователь выложил решения
        if ($awarded) {
            $solutions = Solution::all(array('conditions' =>
                array(
                    'user_id' => $userId,
                    'OR' => array(
                        array('awarded' => 1),
                        array('nominated' => 1)
                    ),
                )
            ));
        } else {
            $solutions = Solution::find('all', array('conditions' => array('Solution.user_id' => $userId), 'order' => array('id' => 'desc'), 'with' => array('Pitch')));
        }

        foreach ($solutions as $s) {
            $pitchesIds[$s->pitch_id . ''] = date('Y-m-d H:i:s');
        }

        ksort($pitchesIds);
        return $pitchesIds;
    }

    public static function getFavouritePitches($userId)
    {
        $fav = Favourite::find('all', array('conditions' => array('Favourite.user_id' => $userId), 'with' => array('Pitch')));
        $pitchesIds = array();
        foreach ($fav as $f) {
            $pitchesIds[$f->pitch->id . ''] = $f->created;
        }
        ksort($pitchesIds);
        return $pitchesIds;
    }

    public static function getTotalSolutionNum($userId)
    {
        $count = Solution::count(array('conditions' => array('user_id' => $userId)));
        return $count;
    }

    public static function getAwardedSolutionNum($userId)
    {
        $count = Solution::count(array('conditions' => array('user_id' => $userId, 'OR' => array(array('awarded' => 1), array('nominated' => 1)))));
        return $count;
    }

    public static function getTotalLikes($userId)
    {
        $all = Solution::all(array('conditions' => array('user_id' => $userId), 'fields' => array('SUM(likes) as totalLikes')));
        return $all->first()->totalLikes;
    }

    public static function getTotalViews($userId)
    {
        $all = Solution::all(array('conditions' => array('user_id' => $userId), 'fields' => array('SUM(views) as totalViews')));
        return $all->first()->totalViews;
    }

    public static function getAverageGrade($userId)
    {
        $all = Solution::all(array('fields' => ['pitch_id'], 'conditions' => array('user_id' => $userId, 'awarded' => 1)));
        $pitches = array();
        foreach ($all as $solution) {
            $pitches[] = $solution->pitch_id;
        }
        $userGrades = array();
        if (!empty($pitches)) {
            $grades = Grade::all(array('conditions' => array('type' => 'client', 'pitch_id' => $pitches)));

            foreach ($grades as $grade) {
                $userGrades[] = $grade->partner_rating;
            }
        }
        $pitches = array();
        $userPitches = Pitch::all(array('conditions' => array('user_id' => $userId, 'status' => 2)));
        foreach ($userPitches as $pitch) {
            $pitches[] = $pitch->id;
        }
        if (!empty($pitches)) {
            $grades = Grade::all(array('conditions' => array('type' => 'designer', 'pitch_id' => $pitches)));
            $userGrades = array();
            foreach ($grades as $grade) {
                $userGrades[] = $grade->partner_rating;
            }
        }
        if (count($userGrades) > 0) {
            return (string) round(array_sum($userGrades) / count($userGrades), 1);
        } else {
            return false;
        }
    }

    /**
     * Метод считают сумму проектов пользователя и количество проектов, в которых он участвовал
     *
     * @param int $userId
     * @return int
     */
    public static function getPitchCount($userId)
    {
        $projects = Pitch::count(['conditions' => ['user_id' => (int) $userId, 'published' => 2]]);
        $participatedProjects = Solution::count([
            'fields' => ['distinct(pitch_id)'],
            'conditions' => ['user_id' => (int) $userId]
        ]);
        return $projects + $participatedProjects;
    }

    public static function getDesignersForSpam($category)
    {
        // Designers
        $result1 = array();
        if ($category != 7) {
            $users1 = self::all(array(
                        'fields' => array('id'),
                        'conditions' => array(
                            'isDesigner' => 1,
                            'email_newpitch' => 1,
                            'email_onlycopy' => 0,
                            'User.email' => array('!=' => ''),
                            'confirmed_email' => 1
                        )
            ));
            $result1 = $users1->data();
        }

        // All but pitches owners
        $users2 = self::all(array(
                    'conditions' => array(
                        'isDesigner' => 0, 'isClient' => 0, 'isCopy' => 0, 'email_newpitch' => 1, 'confirmed_email' => 1, 'User.email' => array('!=' => ''),
                    ),
        ));

        $ids = array();
        foreach ($users2 as $user) {
            $user->pitches = Pitch::all(['conditions' => ['user_id' => $user->id]]);
            if (count($user->pitches) > 0) {
                if (is_null($user->pitches[0]->id)) {
                    $ids[] = $user->id;
                }
            }
        }
        $result2 = array();
        if (!empty($ids)) {
            $users2 = self::all(array(
                        'conditions' => array(
                            'id' => $ids, 'isDesigner' => 0, 'isClient' => 0, 'isCopy' => 0, 'email_newpitch' => 1, 'User.email' => array('!=' => ''),
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
                            'isCopy' => 1, 'email_newpitch' => 1, 'confirmed_email' => 1, 'User.email' => array('!=' => ''),
                        )
            ));
            $result3 = $users3->data();
        }

        if ((!empty($result1)) || (!empty($result2)) || (!empty($result3))) {
            $temp1 = array_keys($result1);
            $temp2 = array_keys($result2);
            $temp3 = array_keys($result3);
            return array_merge($temp1, $temp2, $temp3);
        } else {
            return array();
        }
    }

    public static function sendSpamNewPitch($params)
    {
        $recipientsIds = self::getDesignersForSpam($params['pitch']->category_id);
        $recipientsIds = array_unique($recipientsIds);
        foreach ($recipientsIds as $person) {
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

    public static function sendClientSpamNewPitch($params)
    {
        $user = self::first($params['pitch']->user_id);
        if ($params['pitch']->brief == 0) {
            $text = 'Ваша оплата прошла успешно и проект опубликован на сайте. Как только дизайнеры загрузят свои решения, комментируйте их, выставляйте рейтинг и помогайте лучше понять вас, и тогда вы точно получите то, что хотели.';
        } else {
            $text = 'Ваша оплата прошла успешно. Так как вы заказали опцию “Заполнить бриф”, то проект еще не опубликован на сайте. Мы свяжемся с вами в течение рабочего дня по телефону, указанному в брифе, сформулируем тех.задание для дизайнеров по результатам нашей беседы и выложим конкурс на сайт. ';
        }
        $data = array('user' => $user, 'pitch' => $params['pitch'], 'text' => $text);
        SpamMailer::newclientpitch($data);
    }

    public static function sendSpamNewcomment($params)
    {
        $pitch = Pitch::first($params['pitch_id']);
        $solution = Solution::first($params['solution_id']);
        $user = self::first(array(
                    'conditions' => array('id' => $solution->user_id, 'confirmed_email' => 1),
        ));
        if (($user->email_newcomments == 1) && ($params['user_id'] != $solution->user_id)) {
            $data = array('user' => $user, 'pitch' => $pitch, 'comment' => $params);
            SpamMailer::newcomment($data);
        }
        return true;
    }

    public static function sendAdminBriefPitch($params)
    {
        $users = self::all(['conditions' => ['id' => [4, 5, 32, 47]]]);
        $schedule = \app\models\Schedule::first(['conditions' => ['pitch_id' => $params['pitch']->id]]);
        foreach ($users as $user) {
            $text = 'На сайт добавлен новый проект с опцией "заполнить бриф"';
            $data = ['user' => $user, 'pitch' => $params['pitch'], 'text' => $text, 'schedule' => $schedule];
            SpamMailer::newbriefedpitch($data);
        }
    }

    public static function sendAdminModeratedPitch($pitch)
    {
        $users = self::all(array('conditions' => array('id' => array(4, 5, 32))));
        foreach ($users as $user) {
            $data = array('user' => $user, 'pitch' => $pitch);
            SpamMailer::newmoderatedpitch($data);
        }
    }

    public static function sendAdminNewAddon($addon)
    {
        $users = self::all(array('conditions' => array('id' => User::$admins)));
        $pitch = Pitch::first($addon->pitch_id);
        foreach ($users as $user) {
            $data = array('user' => $user, 'addon' => $addon, 'pitchName' => $pitch->title);
            SpamMailer::newaddon($data);
        }
    }

    public static function sendAdminNewAddonBrief($addon)
    {
        $users = self::all(array('conditions' => array('id' => User::$admins)));
        $pitch = Pitch::first($addon->pitch_id);
        foreach ($users as $user) {
            $data = array('user' => $user, 'addon' => $addon, 'pitchName' => $pitch->title);
            SpamMailer::newaddonbrief($data);
        }
    }

    /**
     * Метод отправляет уведомление о новом комментарии на завершении
     *
     * @param $comment
     * @param $recipient
     * @param bool $blue
     * @return bool
     */
    public static function sendSpamWincomment($comment, $recipient, $blue = false)
    {
        $solution = Solution::first($comment->solution_id);
        $pitch = Pitch::first($solution->pitch_id);
        $data = array(
            'user' => $recipient,
            'pitch' => $pitch,
            'solution' => $solution,
            'comment' => $comment,
            'blue' => $blue
        );
        SpamMailer::newwincomment($data);
        return true;
    }

    public static function sendSpamWinstep($user, $solution, $step)
    {
        $pitch = Pitch::first($solution->pitch_id);
        if ($step == 3) {
            $text = 'Ваши макеты были одобрены, вы переходите на следующую стадию предоставления исходников.';
        }
        if ($step == 4) {
            $text = 'Ваши исходники одобрены заказчиком, вы переходите на стадию выставления оценок. Деньги поступят вам на счетв течение 5 рабочих дней.';
        }
        $data = array('user' => $user, 'pitch' => $pitch, 'text' => $text, 'solution' => $solution);
        SpamMailer::winstep($data);
        return true;
    }

    public static function sendSpamWinstepGo($user, $solution, $step)
    {
        $pitch = Pitch::first($solution->pitch_id);
        $text = 'Ваши исходники одобрены администрацией GoDesigner, вы переходите на стадию выставления оценок.';
        $data = array('user' => $user, 'pitch' => $pitch, 'text' => $text, 'solution' => $solution);
        SpamMailer::winstep($data);
        return true;
    }

    public static function sendPersonalComment($params)
    {
        $user = User::first($params['reply_to']);
        $pitch = Pitch::first($params['pitch_id']);
        if ($user->email_newcomments == 1) {
            $data = array('user' => $user, 'pitch' => $pitch, 'comment' => $params);
            SpamMailer::newpersonalcomment($data);
        }
        return true;
    }

    public static function sendAdminNotification($params)
    {
        $admin = 'fedchenko@godesigner.ru';
        $pitch = Pitch::first($params['pitch_id']);
        $data = array('admin' => $admin, 'pitch' => $pitch, 'comment' => $params);
        SpamMailer::newadminnotification($data);
        return true;
    }

    public static function sendPromoCode($userId)
    {
        $user = User::first(array('conditions' => array('id' => $userId)));
        $code = Promocode::createPromocode($user->id);
        $data = array('user' => $user, 'promocode' => $code);
        SpamMailer::promocode($data);
    }

    public static function sendDailyPitch($user, $pitches)
    {
        $data = array('user' => $user, 'pitches' => $pitches);
        SpamMailer::dailypitch($data);
    }

    public static function sendOpenLetter($pitch)
    {
        $data = array('user' => $pitch->user, 'pitch' => $pitch);
        return SpamMailer::openletter($data);
    }

    public static function sendExpertMail($addon)
    {
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

    public static function sendExpertReminder($pitch)
    {
        $data = array('pitch' => $pitch);
        $experts = unserialize($pitch->{'expert-ids'});
        foreach ($experts as $expert) {
            $expert = Expert::first(array(
                        'conditions' => array(
                            'Expert.id' => $expert,
                        ),
                        'with' => array('User'),
            ));
            if ($comments = Comment::all(array('conditions' => array('pitch_id' => $pitch->id, 'user_id' => $expert->user_id)))) {
                continue;
            }
            $data['user'] = $expert->user;
            SpamMailer::expertreminder($data);
        }
        return true;
    }

    public static function sendSpamExpertSpeaking($params)
    {
        $user = self::first($params['pitch']->user_id);
        $data = array('user' => $user, 'pitch' => $params['pitch'], 'text' => $params['text']);
        SpamMailer::sendclientexpertspeaking($data);
        return true;
    }

    public static function sendSpamFirstSolutionForPitch($pitchId)
    {
        $pitch = Pitch::first($pitchId);
        $user = self::first($pitch->user_id);
        $data = array('user' => $user, 'pitch' => $pitch);
        SpamMailer::firstsolution($data);
        return true;
    }

    public static function sendSpamReferal()
    {
        $users = self::all(array('conditions' => array('User.email' => array('!=' => ''))));
        $sent = 0;
        foreach ($users as $user) {
            $data = array(
                'email' => $user->email,
                'subject' => 'Запуск партнёрской программы',
            );
            SpamMailer::referalspam($data);
            $sent++;
        }
        return $sent;
    }

    public static function sendDvaSpam()
    {
        $users = self::all(array('conditions' => array('User.email' => array('!=' => ''))));
        $sent = 0;
        // Test User
        //$user = new \stdClass();
        //$user->email = 'nyudmitriy@godesigner.ru';
        //$users = array($user);
        // End Test User
        foreach ($users as $user) {
            $data = array(
                'email' => $user->email,
                'subject' => 'Подарочный промо-код на скидку',
            );
            SpamMailer::dvaspam($data);
            $sent++;
        }
        return $sent;
    }

    public static function getAdmin()
    {
        $admin = self::first(array('conditions' => array('isAdmin' => 1)));
        return $admin->id;
    }

    public static function editUser($data)
    {
        $user = User::first($data['user-id']);
        $user->active = (isset($data['active']) ? 1 : 0);
        $user->confirmed_email = (isset($data['confirmed_email']) ? 1 : 0);
        $user->invited = (isset($data['invited']) ? 1 : 0);
        $user->isClient = (isset($data['isClient']) ? 1 : 0);
        $user->isDesigner = (isset($data['isDesigner']) ? 1 : 0);
        $user->isCopy = (isset($data['isCopy']) ? 1 : 0);
        $user->isAdmin = (isset($data['isAdmin']) ? 1 : 0);
        $user->email_newpitch = (isset($data['email_newpitch']) ? 1 : 0);
        $user->email_newcomments = (isset($data['email_newcomments']) ? 1 : 0);
        $user->email_newpitchonce = (isset($data['email_newpitchonce']) ? 1 : 0);
        $user->email_newsolonce = (isset($data['email_newsolonce']) ? 1 : 0);
        $user->email_newsol = (isset($data['email_newsol']) ? 1 : 0);
        $user->email_digest = (isset($data['email_digest']) ? 1 : 0);
        $user->email_onlycopy = (isset($data['email_onlycopy']) ? 1 : 0);
        $user->banned = (isset($data['banned']) ? 1 : 0);
        $user->phone_valid = (isset($data['phone_valid']) ? 1 : 0);
        return $user->save();
    }

    public static function sendDailyDigest()
    {
        $activePitches = Pitch::all(array('conditions' => array('status' => 0, 'published' => 1)));
        $ids = array();
        foreach ($activePitches as $pitch) {
            $ids[] = $pitch->user_id;
        }
        foreach ($ids as $id) {
            self::getDailyDigest($id);
        }
        return $ids;
    }

    public static function getDailyDigest($userId)
    {
        $user = self::first($userId);
        if (empty($user->email)) {
            return true;
        }
        $pitches = Pitch::all(array('conditions' => array(
                        'status' => 0,
                        'published' => 1,
                        'user_id' => $user->id
        )));
        $start = date('Y-m-d H:i:s', (time() - DAY));
        //$start = date('Y-m-d H:i:s', (time() - YEAR));
        $blocks = array();
        $nameInflector = new nameInflector();
        foreach ($pitches as $pitch) {
            $solutions = Solution::all(array('conditions' => array('pitch_id' => $pitch->id, 'created' => array('>' => $start))));
            $blocks[$pitch->id] = array('pitch' => $pitch, 'solutions' => null, 'comments' => null);
            if (count($solutions) > 0) {
                $solArray = array();
                foreach ($solutions as $solution) {
                    $solArray[] = '<a style="color: #7ea0ac; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;"" href="https://www.godesigner.ru/pitches/viewsolution/' . $solution->id . '" target="_blank">#' . $solution->num . '</a>';
                }
                $blocks[$pitch->id]['solutions'] = 'Для вашего проекта выложены новые решения: ' . implode(', ', $solArray) . '. Комментируйте идеи, выставляйте рейтинг (звезды), помогайте дизайнерам лучше понять вас, и тогда вы обязательно получите то, что хотели!';
            } else {
                $blocks[$pitch->id]['solutions'] = '';
            }
            $comments = Comment::all(array('conditions' => array('pitch_id' => $pitch->id, 'Comment.created' => array('>' => $start)), 'with' => array('User')));
            if (count($comments) > 0) {
                $comArray = array();
                foreach ($comments as $comment) {
                    if ($comment->user_id == $pitch->user_id) {
                        continue;
                    }
                    if ($comment->user->isAdmin != 1) {
                        $comArray[] = $nameInflector->renderName($comment->user->first_name, $comment->user->last_name) . ': ' . $comment->text;
                    } else {
                        $comArray[] = 'GoDesigner: ' . $comment->text;
                    }
                }
                $blocks[$pitch->id]['comments'] = implode('<br/><br/>', $comArray);
            } else {
                $blocks[$pitch->id]['comments'] = '';
            }
            if ((count($comments) == 0) && (count($solutions) == 0)) {
                unset($blocks[$pitch->id]);
            }
        }
        if (empty($blocks)) {
            return true;
        }
        $data = array('user' => $user, 'blocks' => $blocks);
        SpamMailer::dailydigest($data);
        return true;
    }

    public static function sendLastDigest()
    {
        if ($lastPosts = Option::first(array('conditions' => array('name' => 'last_posts')))) {
            $ids = unserialize($lastPosts->value);

            $posts = Post::all(array('conditions' => array('id' => array_values($ids)), 'order' => array('created' => 'desc')));
            $users = User::all(array(
                        'conditions' => array(
                            'email_digest' => 1,
                            'confirmed_email' => 1,
                            'created' => array(
                                '>=' => date('Y-m-d H:i:s', time() - (DAY * 4)),
                                '<' => date('Y-m-d H:i:s', time() - (DAY * 3)),
                            ),
                        ),
            ));
            $count = count($users);
            if (count($posts) > 0) {
                foreach ($users as $user) {
                    $data = array(
                        'email' => $user->email,
                        'subject' => 'Дайджест новостей',
                        'posts' => $posts
                    );
                    SpamMailer::blogdigest($data);
                }
            }
            return $count;
        }
        return 0;
    }

    public static function sendSpamToLostClients()
    {
        $pitches = Pitch::all(array(
                    'conditions' => array(
                        'ideas_count' => array('>' => 0),
                        'status' => 0,
                        'published' => 1,
                        'User.email' => array('!=' => ''),
                        'User.lastActionTime' => array('<' => date('Y-m-d H:i:s', time() - (DAY * 3))),
                    ),
                    'with' => array('User', 'Category'),
        ));
        $count = 0;
        foreach ($pitches as $pitch) {
            $pitchData = $pitch->pitchData();
            $avgNum = $pitchData['avgNum'];
            if ($avgNum > 3) {
                continue;
            }
            $data = array('user' => $pitch->user, 'pitch' => $pitch, 'text' => 'Мы просим вас принимать более активное участие в процессе проведения проекта. Комментируйте предлагаемые вам идеи, выставляйте рейтинг (звезды), отвечайте на вопросы и помогайте дизайнерам лучше понять вас, и тогда вы обязательно получите то, что хотели!');
            SpamMailer::comeback($data);
            $count++;
        }
        return $count;
    }

    public static function sendChooseWinnerSpam()
    {
        $pitches = Pitch::all(array('conditions' => array('status' => 1, 'awarded' => 0, 'multiwinner' => 0, 'blank' => 0, 'finishDate' => array('<' => date('Y-m-d H:i:s', time() - (4 * DAY))))));
        $ids = array();
        foreach ($pitches as $pitch) {
            if (($pitch->type == 'fund-balance') || ($pitch->type == 'plan-payment')) {
                continue;
            }
            $user = User::first($pitch->user_id);
            $ids[] = $pitch->user_id;

            if (($pitch->expert == 1) && (strtotime($pitch->finishDate) < time() - (DAY * 4))) {
                continue;
            }

            $defaultTerm = 4;
            if ($pitch->type == 'company_project') {
                $diff = strtotime($pitch->chooseWinnerFinishDate) - strtotime($pitch->finishDate);
                $defaultTerm = floor((($diff / 60) / 60) / 24);
            }

            if ($pitch->expert == 1) {
                $text = 'Срок выбора победителя подошел к концу. Вам необходимо срочно номинировать лучшее решение!<br/>Дизайнеры больше не могут добавлять и комментировать решения.<br/>
В случае, если предложенные идеи вам не понравились, мы инициируем возврат средств. Для этого необходимо в срок до ' . $defaultTerm . ' дней после того, как эксперты выскажут своё мнение, оставить комментарий в галерее работ и объяснить дизайнерам, что эти идеи вам не подходят. Решение о возврате необходимо принять в течение 4 дней после окончания срока проекта, в противном случае такая возможность будет недоступна.';
            } else {
                $text = 'Срок выбора победителя подошел к концу. Вам необходимо срочно номинировать лучшее решение!<br/>Дизайнеры больше не могут добавлять и комментировать решения.<br/>
В случае, если предложенные идеи вам не понравились, мы инициируем возврат средств. Для этого необходимо в срок до ' . $defaultTerm . ' дней после завершения проекта оставить комментарий в галерее работ и объяснить дизайнерам, что эти идеи вам не подходят. Решение о возврате необходимо принять в течение 4 дней после окончания срока проекта, в противном случае такая возможность будет недоступна.';
            }
            if ($pitch->guaranteed == 1) {
                if ($pitch->expert == 1) {
                    $text = 'Срок выбора победителя подошел к концу. Вам необходимо срочно номинировать лучшее решение!<br/>Дизайнеры больше не могут добавлять и комментировать решения. На выбор победителя у вас есть ' . $defaultTerm . ' рабочих дня после того, как все выбранные вами эксперты выскажут своё мнение.';
                } else {
                    $text = 'Срок выбора победителя, на который отводится ' . $defaultTerm . ' дня, подошел к концу. Вам необходимо срочно номинировать лучшее решение!<br>
Внести дальнейшие правки вы сможете на <a href="https://www.godesigner.ru/answers/view/63">завершающем этапе</a>. У вас также есть возможность номинировать <a href="https://www.godesigner.ru/answers/view/97">двух и более дизайнеров</a>. ';
                }
            }
            $data = array('user' => $user, 'pitch' => $pitch, 'text' => $text);
            SpamMailer::choosewinner($data);
        }
        return $ids;
    }

    public static function sendStep2Spam()
    {
        $pitches = Pitch::all(array('conditions' => array('status' => 1, 'awarded' => array('>' => 0), 'finishDate' => array('<' => date('Y-m-d H:i:s', time() - DAY)))));
        $pitchesToSpam = array();
        foreach ($pitches as $pitch) {
            $solution = Solution::first($pitch->awarded);
            $files = array();
            $comments = Wincomment::all(array('conditions' => array('step' => 2, 'solution_id' => $solution->id), 'order' => array('created' => 'asc'), 'with' => array('User')));
            $lastDate = null;
            foreach ($comments as $comment) {
                if ($comment->user_id == $solution->user_id) {
                    $lastDate = $comment->created;
                    if (!empty($comment->images)) {
                        $files[] = $comment->images;
                    }
                }
            }
            if (empty($files)) {
                $nofiles = true;
            } else {
                $nofiles = false;
            }

            if (($solution->step < 3) && ($nofiles == false) && (strtotime($lastDate) < time() - DAY)) {
                $pitchesToSpam[] = $pitch;
            }
        }
        foreach ($pitchesToSpam as $pitch) {
            $user = User::first($pitch->user_id);
            $solution = Solution::first($pitch->awarded);
            $data = array('user' => $user, 'pitch' => $pitch, 'solution' => $solution, 'text' => 'На этапе «Доработка макетов» вам нужно внести правки и утвердить доработки. Для получения рабочих файлов, пожалуйста, нажмите кнопку «Одобрить макеты» и перейдите на этап «Предоставление исходников». Мы убедительно просим вас не затягивать процесс. Спасибо!');
            SpamMailer::step2($data);
        }
    }

    public static function sendStep3Spam()
    {
        $pitches = Pitch::all(array('conditions' => array('status' => 1, 'awarded' => array('>' => 0), 'finishDate' => array('<' => date('Y-m-d H:i:s', time() - DAY)))));
        $pitchesToSpam = array();
        foreach ($pitches as $pitch) {
            $solution = Solution::first($pitch->awarded);
            $files = array();
            $comments = Wincomment::all(array('conditions' => array('step' => 3, 'solution_id' => $solution->id), 'order' => array('created' => 'asc'), 'with' => array('User')));
            $lastDate = null;
            foreach ($comments as $comment) {
                if ($comment->user_id == $solution->user_id) {
                    if (!empty($comment->images)) {
                        $files[] = $comment->images;
                    }
                }
            }
            if (empty($files)) {
                $nofiles = true;
            } else {
                $nofiles = false;
            }
            if (($solution->step == 3) && ($nofiles == false) && (strtotime($lastDate) < time() - DAY)) {
                $pitchesToSpam[] = $pitch;
            }
        }
        foreach ($pitchesToSpam as $pitch) {
            $user = User::first($pitch->user_id);
            $solution = Solution::first($pitch->awarded);
            $data = array('user' => $user, 'pitch' => $pitch, 'solution' => $solution, 'text' => 'На этом этапе дизайнер должен предоставить вам рабочие файлы, указанные в брифе. Для того, чтобы дизайнер получил денежное вознаграждение, вы должны полностью пройти завершающий этап. Пожалуйста, не затягивайте рабочий процесс и перейдите на следующую стадию, нажав «Одобрить макеты».');
            SpamMailer::step3($data);
        }
    }

    public static function sendStep4Spam()
    {
        $pitches = Pitch::all(array('conditions' => array('status' => 1, 'awarded' => array('>' => 0), 'finishDate' => array('<' => date('Y-m-d H:i:s', time() - DAY)))));
        $pitchesToSpam = array();
        foreach ($pitches as $pitch) {
            $solution = Solution::first($pitch->awarded);
            if ($solution->step == 4) {
                $pitchesToSpam[] = $pitch;
            }
        }
        foreach ($pitchesToSpam as $pitch) {
            $solution = Solution::first($pitch->awarded);
            $user = User::first($pitch->user_id);
            $data = array('user' => $user, 'pitch' => $pitch, 'solution' => $solution, 'text' => 'Для того, чтобы дизайнер получил денежное вознаграждение, вы должны полностью пройти завершающий этап. Проставив и рейтинг и нажав «Завершить», вы автоматически инициируете перевод вознаграждения дизайнеру. Деньги поступят ему на счёт в течении 4 рабочих дней. Пожалуйста, не затягивайте процесс вознаграждения!');
            SpamMailer::step4($data);
        }
    }

    public static function sendWinnerComment($solution)
    {
        $pitch = Pitch::first($solution->pitch_id);
        $admin = User::getAdmin();
        if (($pitch->category_id == 1) && ($pitch->private == 0)) {
            $message = 'Друзья, выбран победитель. <a href="https://www.godesigner.ru/pitches/viewsolution/' . $solution->id . '">Им стал</a> #' . $solution->num . '.  Мы поздравляем автора решения и благодарим всех за участие. Если ваша идея не выиграла в этот раз, то, возможно, в следующий вам повезет больше — все права сохраняются за вами, и вы можете адаптировать идею для участия в другом проекте!<br/>
    Подробнее читайте тут: <a href="https://www.godesigner.ru/answers/view/51">http://godesigner.ru/answers/view/51</a><br>
    Через 30 дней ваши работы автоматически попадут на распродажу логотипов и будут доступны каждому за 9500 рублей. Подробнее тут: <a href="https://www.godesigner.ru/answers?search=%D1%80%D0%B0%D1%81%D0%BF%D1%80%D0%BE%D0%B4%D0%B0%D0%B6%D0%B0">https://www.godesigner.ru/answers?search=распродажа</a>';
        } else {
            $message = 'Друзья, выбран победитель. <a href="https://www.godesigner.ru/pitches/viewsolution/' . $solution->id . '">Им стал</a> #' . $solution->num . '.  Мы поздравляем автора решения и благодарим всех за участие. Если ваша идея не выиграла в этот раз, то, возможно, в следующий вам повезет больше — все права сохраняются за вами, и вы можете адаптировать идею для участия в другом проекте!<br/>
    Подробнее читайте тут: <a href="https://www.godesigner.ru/answers/view/51">http://godesigner.ru/answers/view/51</a>';
        }
        $data = array('pitch_id' => $solution->pitch_id, 'user_id' => $admin, 'text' => $message, 'public' => 1);
        Comment::createComment($data);
    }

    /**
     * Отправка сообщений в соц сети о новом победители
     *
     * @param $solution
     * @return bool
     */
    public function sendMessageToSocial($solution)
    {
        $mediaManager = new SocialMediaManager;
        $solution->winner = self::first($solution->user_id);
        $solution->pitch  = Pitch::first($solution->pitch_id);
        return $mediaManager->postWinnerSolutionMessage($solution);
    }

    public static function sendFinishReports($pitch)
    {
        $user = self::first($pitch->user_id);
        $path = LITHIUM_APP_PATH . '/' . 'libraries' . '/' . 'MPDF54/MPDF54/tmp/';
        $files = array();
        foreach (new DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot() || !$fileInfo->isFile() || (false == strpos($fileInfo->getFilename(), $pitch->id))) {
                continue;
            }
            $files[] = $path . $fileInfo->getFilename();
        }
        $data = array('user' => $user, 'pitch' => $pitch, 'files' => $files);
        SpamMailer::finishreports($data);
        return true;
    }

    /**
     * Метод пополняет баланс пользователя
     *
     * @param $userId
     * @param $amount
     * @return bool
     */
    public static function fillBalance($userId, $amount)
    {
        if ($user = self::first($userId)) {
            $user->balance += (int) $amount;
            return $user->save(null, array('validate' => false));
        }
        return false;
    }

    /**
     * Метод вычитает сумму из баланса пользователя
     *
     * @param $userId
     * @param $amount
     * @return bool
     */
    public static function reduceBalance($userId, $amount)
    {
        if (($user = self::first($userId)) && ($user->balance >= $amount)) {
            $user->balance -= (int) $amount;
            return $user->save(null, array('validate' => false));
        }
        return false;
    }

    /**
     * Метод для начала процесса валидации телефонного номера
     *
     * @param $userId
     * @param $phone string
     * @param string $phoneOperator
     * @return array|bool
     */
    public static function phoneValidationStart($userId, $phone, $phoneOperator = '')
    {
        if (($user = self::first($userId)) && !empty($phone)) {
            $user->phone = $phone;
            $user->phone_operator = $phoneOperator;
            $user->phone_code = self::generatePhoneCode();
            $user->phone_valid = 0;
            $user->save(null, array('validate' => false));
            $text = $user->phone_code . ' - код для проверки';
            $params = array(
                "text" => $text
            );
            $phones = array($user->phone);
            $smsService = new SmsUslugi();
            $respond = $smsService->send($params, $phones);
            if(!isset($respond['smsid'])) {
                $smsId = 0;
            }else {
                $smsId = $respond['smsid'];
            }
            $data = [
                'user_id' => $user->id,
                'created' => date('Y-m-d H:i:s'),
                'phone' => $user->phone,
                'text' => $text,
                'status' => $respond['descr'],
                'text_id' => $smsId
            ];
            TextMessage::create($data)->save();
            $phone_valid = 0;
            return compact('respond', 'phone', 'phone_valid');
        }
        return false;
    }

    public static function phoneValidationFinish($userId, $code)
    {
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

    protected static function generatePhoneCode($count = 5, $string = '0123456789')
    {
        return substr(str_shuffle($string), 0, $count);
    }

    public static function sendAddonProlong($pitch)
    {
        $data = array('user' => $pitch->user, 'pitch' => $pitch);
        return SpamMailer::duration($data);
    }

    public static function sendAddonExpert($pitch)
    {
        $data = array('user' => $pitch->user, 'pitch' => $pitch);
        return SpamMailer::expertaddon($data);
    }

    public static function sendAddonBrief($pitch)
    {
        $data = array('user' => $pitch->user, 'pitch' => $pitch);
        return SpamMailer::briefaddon($data);
    }

    public static function isReferalAllowed($userId)
    {
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

    public static function setReferalCookie($ref)
    {
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

    public function blockUntil($user) {
        $user->banned_until = date('Y-m-d H:i:s', time() + 30 * DAY);
        $user->save(null, array('validate' => false));
    }

    public function block($user)
    {
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
                    ), 'with' => ['Pitch']
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
            $cacheKey = 'commentsraw_' . $comment->pitch_id;
            Rcache::delete($cacheKey);
            $comment->delete();
        }
    }

    /**
     * Метод возвращает список айдишников авторов блога
     *
     * @return array
     */
    public static function getAuthorsIds()
    {
        return self::$authors;
    }

    /**
     * Метод возвращает список айдишников админов
     *
     * @return array
     */
    public static function getAdminsIds()
    {
        return self::$admins;
    }

    /**
     * Метод возвращает список айдишников редакторов блога
     *
     * @return array
     */
    public static function getEditorsIds()
    {
        return self::$editors;
    }

    /**
     * Метод возвращает список айдишников авторов ленты новостей
     *
     * @return array
     */
    public static function getFeedAuthorsIds()
    {
        return self::$feedAuthors;
    }

    /**
     * Метод обновляет дату последнего действия для пользователя
     *
     * @param $record
     */
    public function setLastActionTime($record)
    {
        if (!Rcache::write('user_' . $record->id . '_LastActionTime', date('Y-m-d H:i:s'))) {
            $record->lastActionTime = date('Y-m-d H:i:s');
            $record->save(null, array('validate' => false));
        }
    }

    /**
     * Метод возвращается время (unix-time) последнего действия для пользователя
     *
     * @param $record
     * @return int
     */
    public function getLastActionTime($record)
    {
        if (!$lastActionTime = Rcache::read('user_' . $record->id . '_LastActionTime')) {
            $lastActionTime = $record->lastActionTime;
        }
        return strtotime($lastActionTime);
    }

    /**
     * Метод возвращяет количество пользователей с положительным балансо и не абоненты
     *
     * @return mixed
     */
    public static function getReferalPaymentsCount()
    {
        return self::count(array(
            'conditions' => array(
                'balance' => array(
                    '>' => 0,
                ),
                'phone_valid' => 1,
                'phone' => array('!=' => ''),
                'subscription_status' => 0
            ),
        ));
    }

    /**
     * Check User's Accounts
     */
    public static function accountCheck($entity)
    {
        $options = unserialize($entity->paymentOptions);
        $options = $options[0];
        if ((isset($options['coraccount'])) and (isset($options['accountnum'])) and (isset($options['bik']))) {
            $resultCor = 1; //$resultCor = self::fn_checkKS($options['coraccount']) ? 1 : 0;
            $resultAcc = self::fn_checkRS($options['accountnum'], $options['bik']) ? 2 : 0;
            $result = $resultCor + $resultAcc;
            $message = '';
            switch ($result) {
                case 0:
                    $message = 'Неверно указан Счёт.<br>Неверно указан Корсчёт.<br>';
                    break;
                case 1:
                    $message = 'Неверно указан Счёт.<br>';
                    break;
                case 2:
                    $message = 'Неверно указан Корсчёт.<br>';
                    break;
                default:
                    break;
            }
            $messageBik = (preg_match('/^[0-9]{9}$/', $options['bik'])) ? '' : 'Неверно указан БИК.<br>';
            $messageInn = (preg_match('/^[0-9]{12}$/', $options['inn'])) ? '' : 'Неверно указан ИНН.<br>';
            $message = $messageBik . $messageInn . $message;
            return !(bool) $message;
        }
        return false;
    }

    public static function designerTimeWait($user_id)
    {
        $query = array(
            'conditions' => array(
                'first_time' => 1,
                'user_id' => $user_id,
                'percent' => array(
                    '>=' => 80,
                ),
                'active' => 1
        ));

        if ($test = Test::first($query)) {
            return 5;
        }

        return 10;
    }

    protected static function fn_bank_account($str)
    {
        $result = false;
        $sum = 0;
        if ($str == 0) {
            return $result;
        }

        //весовые коэффициенты
        $v = array(7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1);

        for ($i = 0; $i <= 22; $i++) {
            //вычисляем контрольную сумму
            $sum = $sum + (((int) $str{$i}) * $v[$i]) % 10;
        }

        //сравниваем остаток от деления контрольной суммы на 10 с нулём
        if ($sum % 10 == 0) {
            $result = true;
        }

        return $result;
    }

    protected static function fn_checkKS($account)
    {
        return (bool) preg_match('/^[0-9]{20}$/', $account);
    }

    /*
     * Проверка правильности указания расчётного счёта:
     * 1. Для проверки контрольной суммы перед расчётным счётом добавляются три последние цифры БИКа банка.
     */

    protected function fn_checkRS($account, $BIK)
    {
        return self::fn_bank_account(substr($BIK, -3, 3) . $account);
    }

    public static function getUserInfo()
    {
        $res = null;
        $currentUserId = Session::read('user.id');
        if ((false != $currentUserId) && ($user = self::first(array('conditions' => array('User.id' => $currentUserId))))) {
            $user->pitches = Pitch::all(['conditions' => ['user_id' => $user->id, 'blank' => 0]]);
            $res = array(
                'id' => $user->id,
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'pitches' => $user->pitches->data(),
            );
        }
        return $res;
    }

    /**
     * Метод генерирует токен, если нету
     *
     * @param $userid
     * @return object
     */
    public static function setUserToken($userid)
    {
        $user = self::first($userid);
        if (!$user->token) {
            $user->token = $user->generateToken();
            $user->save(null, array('validate' => false));
        }
        return $user;
    }

    public static function postOnFacebook($text)
    {
        $facebook = new Facebook(array(
            'appId'  => '202765613136579',
            'secret' => '404ec2eea7487d85eb69ecceea341821',
        ));
        $user = $facebook->getUser();
        $accessToken = $facebook->getAccessToken();
        $facebook->setAccessToken($accessToken);
        if ($user) {
            try {
                $facebook->api('/me/feed', 'post', array('message' => $text));
            } catch (FacebookApiException $e) {
                error_log($e);
            }
        }
        if ($user) {
            $logoutUrl = $facebook->getLogoutUrl();
        } else {
            $loginUrl = $facebook->getLoginUrl();
            header('Location: ' . $loginUrl);
        }
    }

    /**
     * Метод возвращаяет список айдишников решений-победителей для пользователя $userId
     *
     * @param $userId
     * @return array
     */
    public static function getUsersWinnerSolutionsIds($userId)
    {
        return self::__findIdsOfWonProjectsOfUser($userId, 'id');
    }

    /**
     * Метод возвращяет спиской айдишников питчей, в который побеждал пользователь $userId
     *
     * @param $userId
     * @return array
     */
    public static function getUsersWonProjectsIds($userId)
    {
        return self::__findIdsOfWonProjectsOfUser($userId, 'pitch_id');
    }

    /**
     * Метод возвращяет список полей $fields питчей, в которых побеждал пользователь $userId
     *
     * @param $userId
     * @param $field
     * @return array
     */
    private static function __findIdsOfWonProjectsOfUser($userId, $field)
    {
        $fields = array();
        $fields[] = $field;
        $solutions = Solution::all(array('fields' => $fields, 'conditions' => array(
            'user_id' => $userId,
            'OR' => array(
                array('awarded' => 1),
                array('nominated' => 1)
            ))));
        $result = array();
        foreach ($solutions as $solution) {
            $result[] = $solution->{$field};
        }
        return $result;
    }

    /**
     * Метод удаляет приватную информацию из объекта $user
     *
     * @param $user
     * @return mixed
     */
    public static function removeExtraFields($user)
    {
        $blacklist = array(
            'email',
            'oldemail',
            //'last_name',
            'location',
            'birthdate',
            'password',
            'confirmed_email',
            'token',
            'facebook_uid',
            'vkontakte_uid',
            'created',
            'invited',
            'paymentOptions',
            'userdata',
            'balance',
            'phone',
            'phone_operator',
            'phone_code',
            'phone_valid',
            'referal_token',
            'autologin_token',
        );
        foreach ($blacklist as $field) {
            $user->{$field} = null;
        }
        return $user;
    }

    /**
     * Метод активирует подписку и добавляет время подписки
     *
     * @param $userId int
     * @param $plan array
     * @return mixed
     *
     */
    public static function activateSubscription($userId, $plan)
    {
        $userId = (int) $userId;
        $user = User::first($userId);
        if (self::isSubscriptionActive($userId, $user)) {
            $user->subscription_expiration_date = date('Y-m-d H:i:s', strtotime($user->subscription_expiration_date) + $plan['duration']);
        } else {
            $user->subscription_status = $plan['id'];
            $user->subscription_expiration_date = date('Y-m-d H:i:s', time() + $plan['duration']);
        }
        return $user->save(null, array('validate' => false));
    }

    /**
     * Метод проверяет активна ли подписка пользователя
     *
     * @param $userId
     * @param $userObject
     * @return bool
     */
    public static function isSubscriptionActive($userId, $userObject = null)
    {
        if (!$userObject) {
            $userObject = self::first($userId);
        }
        if ((bool) $userObject->subscription_status) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Метод возвращяет дату окончания подписки
     *
     * @param $userId
     * @param $format
     * @return bool|date
     */
    public static function getSubscriptionExpireDate($userId, $format = 'd.m.Y H:i:s')
    {
        $userObject = self::first($userId);
        if (self::isSubscriptionActive($userId, $userObject)) {
            return date($format, strtotime($userObject->subscription_expiration_date));
        } else {
            return false;
        }
    }

    /**
     * Метод возвращяет баланс текущего пользователя
     *
     * @param $userId
     * @return int
     */
    public static function getBalance($userId)
    {
        $userObject = self::first($userId);
        return (int) $userObject->balance;
    }

    /**
     * Метод возвращяет краткое название компании
     *
     * @param $userId
     * @return mixed
     */
    public static function getShortCompanyName($userId)
    {
        $userObject = self::first($userId);
        return $userObject->short_company_name;
    }

    /**
     * Метод возвращяет полное название компании, если установлено
     * если нет, возвращяем краткое название
     *
     * @param $userId
     * @return mixed
     */
    public static function getFullCompanyName($userId)
    {
        $userObject = self::first($userId);
        if (($companyData = unserialize($userObject->companydata)) && (isset($companyData['company_name']))) {
            return $companyData['company_name'];
        }
        return self::getShortCompanyName($userId);
    }

    /**
     * Метод возвращяет информацию о текущем плане пользователя
     *
     * @param $userId
     * @return array|null
     */
    public static function getCurrentPlanData($userId)
    {
        if (self::isSubscriptionActive($userId)) {
            $user = self::first($userId);
            $planId = $user->subscription_status;
            $plan = SubscriptionPlan::getPlan($planId);
            return $plan;
        }
        return array();
    }

    /**
     * Метод устанавливает скидку и дату окончания скидки
     *
     * @param $userId
     * @param $discount
     * @param $endDate
     * @return bool
     */
    public static function setSubscriptionDiscount($userId, $discount, $endDate)
    {
        if ($user = self::first($userId)) {
            $data = array('subscription_discount' => (int) $discount, 'subscription_discount_end_date' => $endDate);
            return $user->save($data, array('validate' => false));
        }
        return false;
    }

    /**
     * Метод возвращяет, есть ли активная скидка для абонента
     *
     * @param $userRecord
     * @return bool
     */
    public function hasActiveSubscriptionDiscountForRecord(Record $userRecord)
    {
        if(strtotime($userRecord->subscription_discount_end_date)) {
            $discountEndDate = new \DateTime($userRecord->subscription_discount_end_date);
            $currentDateTime = new \DateTime;
            return $discountEndDate > $currentDateTime;
        }
        return false;
    }

    /**
     * Метод возвращяет, есть ли активная скидка для абонента
     *
     * @param $userId
     * @return bool
     */
    public static function hasActiveSubscriptionDiscount($userId)
    {
        if ($user = self::first((int) $userId)) {
            return $user->hasActiveSubscriptionDiscountForRecord();
        }
        return false;
    }

    /**
     * Метод возвращяет текущую активную скидку, если активная
     *
     * @param $userRecord
     * @return null|integer
     */
    public function getSubscriptionDiscountForRecord(Record $userRecord)
    {
        if ($userRecord->hasActiveSubscriptionDiscountForRecord()) {
            return (int) $userRecord->subscription_discount;
        }
        return null;
    }

    /**
     * Метод возвращяет текущую активную скидку, если активная
     *
     * @param $userId
     * @return null|integer
     */
    public static function getSubscriptionDiscount($userId)
    {
        if ($user = self::first((int) $userId)) {
            return $user->getSubscriptionDiscountForRecord();
        }
        return null;
    }

    /**
     * Метод возвращяет дату окончания текущей скидки, если активная
     *
     * @param $userRecord
     * @return null|string
     */
    public static function getSubscriptionDiscountEndTimeForRecord(Record $userRecord)
    {
        if ($userRecord->hasActiveSubscriptionDiscountForRecord()) {
            return $userRecord->subscription_discount_end_date;
        }
        return null;
    }

    /**
     * Метод возвращяет дату окончания текущей скидки, если активная
     *
     * @param $userId
     * @return null|string
     */
    public static function getSubscriptionDiscountEndTime($userId)
    {
        if ($user = self::first((int) $userId)) {
            return $user->getSubscriptionDiscountEndTimeForRecord();
        }
        return null;
    }

    /**
     * Метод проверяет, состоит ли пользователей в группе в ВК
     *
     * @param $userId
     * @return bool
     */
    public static function isMemberOfVKGroup($userId)
    {
        if (($user = self::first($userId))) {
            return (bool) $user->isUserRecordMemberOfVKGroup();
        }
        return false;
    }

    /**
     * Метод проверяет, состоит ли запись пользователя в группе в ВК
     *
     * @param $record
     * @return bool
     */
    public function isUserRecordMemberOfVKGroup($record)
    {
        if ($record->vkontakte_uid != '') {
            $config = array(
                'scheme'     => 'https',
                'host'       => 'api.vk.com'
            );
            $service = new Service($config);
            $params = array(
                'group_id' => 36153921,
                'user_id' => $record->vkontakte_uid
            );
            $result = $service->get("/method/groups.isMember", $params);
            $decoded = json_decode($result);
            return (bool) $decoded->response;
        }
        return false;
    }

    /**
     * Метод определяет, является ли пользователь ИП
     *
     * @param $record
     * @return bool
     */
    public function isEntrepreneur($record)
    {
        if ($data = $this->getUnserializedCompanyData($record)) {
            return !$this->isCompany($record);
        }
        return false;
    }

    /**
     * Метод определяет, является ли пользователь компанией
     *
     * @param $record
     * @return bool
     */
    public function isCompany($record)
    {
        if ($data = $this->getUnserializedCompanyData($record)) {
            if ((isset($data['kpp'])) && (isset($data['inn'])) && (mb_strlen($data['inn'], 'UTF-8') == 10)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Метод помощник для полуения сериализорованных данных
     *
     * @param $record
     * @return mixed|null
     */
    public function getUnserializedCompanyData($record)
    {
        if (($record->companydata != '') && ($data = unserialize($record->companydata))) {
            return $data;
        }
        return null;
    }
}
