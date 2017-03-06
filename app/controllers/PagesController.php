<?php

namespace app\controllers;

use app\extensions\mailers\ContactMailer;
use app\extensions\social\TwitterAPI;
use app\extensions\storage\Rcache;
use app\models\Answer;
use app\models\Expert;
use app\models\Grade;
use app\models\Pitch;
use app\models\Schedule;
use app\models\Solution;
use app\models\Solutionfile;
use app\models\User;
use app\models\Wp_post;
use tmhOAuth\tmhOAuth;

/**
 * Class PagesController
 *
 * Метод для показа статических и полустатических страниц
 *
 * @package app\controllers
 * @property \app\extensions\helper\User $userHelper
 */
class PagesController extends AppController
{

    /**
     * @var array публичные методы
     */
    public $publicActions = [
        'view', 'home', 'contacts', 'howitworks', 'experts', 'fastpitch', 'subscribe', 'goldenfish'
    ];

    /**
     * Метод для отображения статических страниц
     */
    public function view()
    {
        $path = func_get_args() ? : ['home'];
        if (preg_match('/experts/', $path[0])) {
            return $this->redirect('/experts');
        }
        if (preg_match('/fastpitch/', $path[0])) {
            return $this->redirect('/fastpitch');
        }
        if (preg_match('/golden-fish/', $path[0])) {
            return $this->redirect('/golden-fish');
        }
        $questions = $this->popularQuestions();
        $answers = Answer::all(['conditions' => ['questioncategory_id' => 2], 'limit' => 10, 'order' => ['hits' => 'desc']]);
        return $this->render(['template' => join('/', $path), 'data' => ['questions' => $questions, 'answers' => $answers]]);
    }

    public function goldenfish()
    {
        $solutionRecords = Solution::all([
            'conditions' => [
                'Pitch.category_id' => 20,
                'Solution.awarded' => 1,
                'Pitch.multiwinner' => 0
            ],
            'limit' => 96,
            'with' => ['Pitch']
        ]);
        foreach ($solutionRecords as $solution) {
            if ($solution->pitch->isSubscriberProjectForCopyrighting()) {
                continue;
            }
            if (isset($solution->images['solution_promo'])) {
                continue;
            }
            if (isset($solution->images['solution'][0])) {
                $newfiledata = pathinfo($solution->images['solution'][0]['filename']);
                $originalFilename = $solution->images['solution'][0]['filename'];
            } else {
                $newfiledata = pathinfo($solution->images['solution']['filename']);
                $originalFilename = $solution->images['solution']['filename'];
            }
            $newfilename = $newfiledata['dirname'] . '/' . $newfiledata['filename'] . '_promo.' . $newfiledata['extension'];
            $imageProcessor = new \upload($originalFilename);
            $imageProcessor->upload($originalFilename);
            $imageProcessor->init();
            $imageProcessor->uploaded = true;
            $imageProcessor->no_upload_check = true;
            $imageProcessor->file_src_pathname = $originalFilename;
            $imageProcessor->file_src_name_ext = $newfiledata['extension'];
            $imageProcessor->file_new_name_body = $newfiledata['filename'] . '_promo';
            $promoSize = ['image_resize' => true, 'image_ratio_crop' => 'T', 'image_x' => 322, 'image_y' => 322, 'file_overwrite' => true];
            foreach ($promoSize as $param => $value) {
                $imageProcessor->{$param} = $value;
            }
            $imageProcessor->process($newfiledata['dirname']);
            $conditions = ['model' => '\app\models\Solution', 'model_id' => $solution->id, 'filekey' => 'solution' . '_promo', 'filename' => $newfilename];
            $data = ['filename' => $newfilename] + $conditions;
            if ($existingRow = Solutionfile::first(['conditions' => $conditions])) {
                $existingRow->set($data);
                $existingRow->save();
            } else {
                Solutionfile::create($data)->save();
            }
            $solution = Solution::first($solution->id);
        }
        $separatorPrice = 5000;
        $solutions = $lowList = $highList = [];
        $totalCount = count($solutionRecords);
        foreach ($solutionRecords as $solution) {
            if ($solution->pitch->isSubscriberProjectForCopyrighting()) {
                continue;
            }
            if ((int) $solution->pitch->price < $separatorPrice) {
                $lowList[] = $solution;
            } else {
                $highList[] = $solution;
            }
            //$solutions[] = $solution;
        }
        for ($i = 0; $i < $totalCount; $i++) {
            if (count($lowList) >= ($i + 1)) {
                $solutions[] = $lowList[$i];
            }
            if (count($highList) >= ($i + 1)) {
                $solutions[] = $highList[$i];
            }
        }
        return compact('solutions');
    }

    /**
     * Метод для отображения главной страницы
     *
     * @return array
     */
    public function home()
    {
        $pool = [1, 3, 7];
        $category_id = $pool[array_rand($pool)];
        if (!$statistic = Rcache::read('statistic')) {
            $statistic = [
                'numOfSolutionsPerProject' => [
                    '1' => Pitch::getNumOfSolutionsPerProjectOfCategory(1),
                    '3' => Pitch::getNumOfSolutionsPerProjectOfCategory(3),
                    '7' => Pitch::getNumOfSolutionsPerProjectOfCategory(7),
                ],
                'numOfCurrentPitches' => Pitch::getNumOfCurrentPitches(),
                'totalAwards' => Pitch::getTotalAwards(),
                'totalWaitingForClaim' => Pitch::getTotalWaitingForClaim(),
                'totalParticipants' => Solution::getTotalParticipants(),
                'lastDaySolutionNum' => Solution::getNumOfUploadedSolutionInLastDay(),
            ];
            Rcache::write('statistic', $statistic, '+1 hour');
        }
        $pitches = Pitch::getPitchesForHomePage();
        $promoSolutions = Solution::all([
            'conditions' => [
                'Promo.enabled' => 1,
            ],
            'with' => ['Promo', 'Pitch'],
            'order' => ['RAND()'],
            'limit' => 2
        ]);
        foreach ($promoSolutions as $promoSolution) {
            $promoSolution->pitch->days = ceil((strtotime($promoSolution->pitch->finishDate) - strtotime($promoSolution->pitch->started)) / DAY);
        }
        $grades = Grade::all(['limit' => 2, 'conditions' => ['enabled' => 1], 'order' => ['RAND()'], 'with' => ['Pitch']]);
        foreach ($grades as $grade) {
            $grade->user = User::first(['conditions' => ['id' => $grade->user_id]]);
        }
        $experts = Expert::all();
        $totalCount = Solution::solutionsForSaleCount();
        return compact('category_id', 'statistic', 'pitches', 'promoSolutions', 'experts', 'grades', 'totalCount');
    }

    /**
     * Метод для отображения страницы "контакты" и обработка формы получения сообщений
     *
     * @return array
     */
    public function contacts()
    {
        $success = false;
        if ($this->request->data) {
            $this->request->data['user'] = User::getUserInfo();
            ContactMailer::contact_mail($this->request->data);
            $success = true;
        }
        $questions = $this->popularQuestions();
        return compact('success', 'questions');
    }

    /**
     * Метод для отображения лендинга "логотип в один клик"
     *
     * @return array
     */
    public function fastpitch()
    {
        $currentTime = new \DateTime();
        $schedule = Schedule::all(['conditions' => ['start' => ['>=' => $currentTime->format('Y-m-d H:i:s')]]]);
        $allowTime = [];
        $deny_time = [];
        $start_hours = new \DateTime();
        $start_hours->setTime('12', '00', '00');

        $end_hours = new \DateTime();

        $max_time = '';
        foreach ($schedule as $v) {
            $deny_time[] = $v->start;
            if ($max_time < $v->start) {
                $max_time = $v->start;
            }
        }
        $max_hour = date('H', strtotime($max_time));
        $end_hours->setTime('15', '00', '00');
        $temp = new \DateTime();
        $x = true;
        for ($i = 1; $i <= 15;) {
            if ($x) {
                $temp->setTime($temp->format('H'), '00', '00');
            } else {
                $temp->setTime((int) $temp->format('H') + 1, '00', '00');
            }
            $x = false;
            if (!in_array($temp->format('Y-m-d H:i:s'), $deny_time) && $temp->getTimestamp() >= $start_hours->getTimestamp()) {
                if ($temp->getTimestamp() <= $end_hours->getTimestamp() && (int) $temp->format('w') != 0 && (int) $temp->format('w') != 6) {
                    $allowTime[$temp->getTimestamp()] = $temp->format('H:i d/m/y');
                    $i++;
                } else {
                    $start_hours->setDate($start_hours->format('Y'), $start_hours->format('m'), (int) $start_hours->format('d') + 1);
                    $end_hours->setDate($end_hours->format('Y'), $end_hours->format('m'), (int) $end_hours->format('d') + 1);
                    $temp->setdate($temp->format('Y'), $temp->format('m'), (int) $temp->format('d') + 1);
                    $temp->setTime('12', '00', '00');
                    $x = true;
                }
            } elseif ($max_hour >= '16' && strtotime($max_time) > time()) {
                $max_hour = '';
                $allowTime[strtotime($max_time)] = date('H:i d/m/y', strtotime($max_time));
            }
        }

        return compact('allowTime');
    }

    /**
     * Метод для отображения страницы лендинга
     *
     * @return void
     */
    public function subscribe()
    {
        if ((!empty($this->request->query['sref'])) && User::isValidReferalCodeForSubscribers($this->request->query['sref'])) {
            return $this->redirect($this->request->url);
        }
        if ($this->userHelper->isLoggedIn() && $this->userRecord->hasActiveSubscriptionDiscountForRecord()) {
            $discount = $this->userRecord->getSubscriptionDiscountForRecord();
            $discountEndTime = $this->userRecord->getSubscriptionDiscountEndTimeForRecord();
            $data = compact('discount', 'discountEndTime');
            return $this->render(['template' => 'subscribe_discount', 'data' => $data]);
        }
        $cookieExists = false;
        if(isset($_COOKIE['sreftime'])) {
            $cookieExists = isset($_COOKIE['sreftime']);
            $cookieName = 'sreftime';
        }else if($_COOKIE['sref2time']) {
            $cookieExists = isset($_COOKIE['sref2time']);
            $cookieName = 'sref2time';
        }
        if (($this->discountForSubscriberReferal > 0) && $cookieExists) {
            $startTime = strtotime(date(MYSQL_DATETIME_FORMAT, $_COOKIE[$cookieName]));
            $delta = (time() - $startTime);
            if (floor($delta / DAY) < 10) {
                $discount = $this->discountForSubscriberReferal;
                $discountEndTime = date(MYSQL_DATETIME_FORMAT, $startTime + 10 * DAY);
                $data = compact('discount', 'discountEndTime');
                return $this->render(['template' => 'subscribe_discount', 'data' => $data]);
            }
        }
    }
}
