<?php

namespace app\controllers;

use \app\models\Pitch;
use \app\models\Expert;
use \app\models\User;
use \app\models\Grade;
use app\models\Answer;
use \app\models\Solution;
use \app\extensions\mailers\ContactMailer;
use app\extensions\storage\Rcache;
use app\models\Schedule;

/**
 * Class PagesController
 *
 * Метод для показа статических и полустатических страниц
 *
 * @package app\controllers
 */
class PagesController extends AppController {

    /**
     * @var array публичные методы
     */
    public $publicActions = array(
        'view', 'home', 'contacts', 'howitworks', 'experts', 'fastpitch', 'subscribe'
    );

    public function view() {
        $path = func_get_args() ? : array('home');
        if (preg_match('/experts/', $path[0])) {
            return $this->redirect('/experts');
        }
        $questions = $this->popularQuestions();
        $answers = Answer::all(array('conditions' => array('questioncategory_id' => 2), 'limit' => 10, 'order' => array('hits' => 'desc')));
        return $this->render(array('template' => join('/', $path), 'data' => array('questions' => $questions, 'answers' => $answers)));
    }

    public function stats() {
        return $this->render(array('layout' => 'stats'));
    }

    public function twitter() {
        echo '<pre>';
        var_dump($_GET);
        die();
    }

    public function home() {
        $pool = array(1, 3, 7);
        $category_id = $pool[array_rand($pool)];
        if (!$statistic = Rcache::read('statistic')) {
            $statistic = array(
                'numOfSolutionsPerProject' => array(
                    '1' => Pitch::getNumOfSolutionsPerProjectOfCategory(1),
                    '3' => Pitch::getNumOfSolutionsPerProjectOfCategory(3),
                    '7' => Pitch::getNumOfSolutionsPerProjectOfCategory(7),
                ),
                'numOfCurrentPitches' => Pitch::getNumOfCurrentPitches(),
                'totalAwards' => Pitch::getTotalAwards(),
                'totalWaitingForClaim' => Pitch::getTotalWaitingForClaim(),
                'totalParticipants' => Solution::getTotalParticipants(),
                'lastDaySolutionNum' => Solution::getNumOfUploadedSolutionInLastDay(),
            );
            Rcache::write('statistic', $statistic, '+1 hour');
        }
        $pitches = Pitch::getPitchesForHomePage();
        $promoSolutions = Solution::all(array(
            'conditions' => array(
                'Promo.enabled' => 1,
            ),
            'with' => array('Promo', 'Pitch'),
            'order' => array('RAND()'),
            'limit' => 2
        ));
        foreach($promoSolutions as $promoSolution) {
            $promoSolution->pitch->days = ceil((strtotime($promoSolution->pitch->finishDate) - strtotime($promoSolution->pitch->started)) / DAY);
        }
        $grades = Grade::all(array('limit' => 2, 'conditions' => array('enabled' => 1), 'order' => array('RAND()'), 'with' => array('Pitch')));
        foreach ($grades as $grade) {
            $grade->user = User::first(array('conditions' => array('id' => $grade->user_id)));
        }
        $experts = Expert::all();
        $totalCount = Solution::solutionsForSaleCount();
        return compact('category_id', 'statistic', 'pitches', 'promoSolutions', 'experts', 'grades', 'totalCount');
    }

    public function contacts() {
        $success = false;
        if ($this->request->data) {
            $this->request->data['user'] = User::getUserInfo();
            ContactMailer::contact_mail($this->request->data);
            $success = true;
        }
        $questions = $this->popularQuestions();
        return compact('success', 'questions');
    }

    public function fastpitch() {
        $schedule = Schedule::all(array('conditions' => array('start' => array('>=' => time()))));
        $alllow_time = array();
        $deny_time = array();
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
        // var_dump($max_time,date('H',  strtotime($max_time)));
        $end_hours->setTime('15', '00', '00');
        //date('Y-m-d H:i:s', mktime(date("H"), 0, 0));
        $temp = new \DateTime();
        $x = true;
        for ($i = 1; $i <= 15;) {
            if ($x) {
                $temp->setTime($temp->format('H'), '00', '00');
            } else {
                $temp->setTime($temp->format('H') + 1, '00', '00');
            }
            $x = false;
            if (!in_array($temp->format('Y-m-d H:i:s'), $deny_time) && $temp->getTimestamp() >= $start_hours->getTimestamp()) {
                if ($temp->getTimestamp() <= $end_hours->getTimestamp() && (int) $temp->format('w') != 0 && (int) $temp->format('w') != 6) {
                    //var_dump($end_hours->format('H:i d/m/y'), $temp->format('H:i d/m/y'));
                    $alllow_time[$temp->getTimestamp()] = $temp->format('H:i d/m/y');
                    $i++;
                } else {
                    $start_hours->setDate($start_hours->format('Y'), $start_hours->format('m'), $start_hours->format('d') + 1);
                    $end_hours->setDate($end_hours->format('Y'), $end_hours->format('m'), $end_hours->format('d') + 1);
                    $temp->setDate($temp->format('Y'), $temp->format('m'), $temp->format('d') + 1);
                    $temp->setTime('12', '00', '00');
                    $x = true;
                }
            } elseif ($max_hour >= '16' && strtotime($max_time) > time()) {
                $max_hour = '';
                $alllow_time[strtotime($max_time)] = date('H:i d/m/y', strtotime($max_time));
            }
        }

        return compact('alllow_time');
    }

    /**
     * Метод для отображения страницы лендинга
     */
    public function subscribe() {
        if($this->userHelper->isLoggedIn() && User::hasActiveSubscriptionDiscount($this->userHelper->getId())) {
            $discount = User::getSubscriptionDiscount($this->userHelper->getId());
            $discountEndTime = User::getSubscriptionDiscountEndTime($this->userHelper->getId());
            $data = array('discount' => $discount, 'discountEndTime' => $discountEndTime);
            return $this->render(array('template' => 'subscribe_discount', 'data' => $data));
        }

    }

}