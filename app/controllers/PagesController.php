<?php

/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\controllers;

use \app\models\Pitch;
use \app\models\Answer;
use \app\models\Expert;
use \app\models\Promo;
use \app\models\User;
use \app\models\Grade;
use \app\models\Solution;
use \app\extensions\mailers\ContactMailer;
use app\extensions\storage\Rcache;
use app\models\Schedule;

class PagesController extends \app\controllers\AppController {

    public $publicActions = array(
        'view', 'home', 'contacts', 'howitworks', 'experts', 'fastpitch'
    );

    public function view() {
        $path = func_get_args() ? : array('home');
        if (preg_match('/experts/', $path[0])) {
            return $this->redirect('/experts');
        }
        $questions = $this->popularQuestions();
        return $this->render(array('template' => join('/', $path), 'data' => array('questions' => $questions)));
    }

    public function stats() {
        return $this->render(array('layout' => 'stats'));
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
        $promos = Promo::all(array(
                    'limit' => 2,
                    'conditions' => array('enabled' => 1),
                    'with' => array('Solution'),
                    'order' => array('RAND()')
        ));
        foreach ($promos as $promo) {
            if ($promo->solution->pitch_id == null) {
                $promos = array();
                break;
            }
            $promo->solution->pitch = Pitch::first($promo->solution->pitch_id);
            $promo->solution->pitch->days = ceil((strtotime($promo->solution->pitch->finishDate) - strtotime($promo->solution->pitch->started)) / DAY);
        }
        $grades = Grade::all(array('limit' => 2, 'conditions' => array('enabled' => 1), 'order' => array('RAND()'), 'with' => array('Pitch')));
        foreach ($grades as $grade) {
            $grade->user = User::first(array('conditions' => array('id' => $grade->user_id)));
        }
        $experts = Expert::all();
        return compact('category_id', 'statistic', 'pitches', 'promos', 'experts', 'grades');
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

    public function howitworks() {
        
    }

    public function fastpitch() {
        $schedule = Schedule::all(array('conditions' => array('end' => array('>=' => time()))));
        $alllow_time = array();
        $deny_time = array();
        $start_hours = new \DateTime();
        $start_hours->setTime('12', '00', '00');

        $end_hours = new \DateTime();
        $end_hours->setTime('16', '00', '00');
        foreach ($schedule as $v) {
            $deny_time[] = $v->end;
        }
        //date('Y-m-d H:i:s', mktime(date("H"), 0, 0));
        $temp = new \DateTime();
        $x = 0;
        for ($i = 1; $i <= 15;) {
            $temp->setTime($temp->format('H') + $x, '00', '00');
            $x++;
            if (!in_array($temp->format('Y-m-d H:i:s'), $deny_time) &&
                    $temp->getTimestamp() >= $start_hours->getTimestamp()
            ) {
                if ($temp->getTimestamp() <= $end_hours->getTimestamp() && (int)$temp->format('w') != 0 && (int)$temp->format('w') != 6) {
                    //var_dump($end_hours->format('H:i d/m/y'), $temp->format('H:i d/m/y'));
                    $alllow_time[$temp->getTimestamp()] = $temp->format('H:i d/m/y');
                    $i++;
                } else {
                    $start_hours->setDate($start_hours->format('Y'), $start_hours->format('m'), $start_hours->format('d') + 1);
                    $end_hours->setDate($end_hours->format('Y'), $end_hours->format('m'), $end_hours->format('d') + 1);
                    $temp->setDate($temp->format('Y'), $temp->format('m'), $temp->format('d') + 1);
                    $temp->setTime('12', '00', '00');
                    $x = 0;
                }
            }
        }

        return compact('alllow_time');
    }

}

?>