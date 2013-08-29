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
use \app\extensions\mailers\ContactMailer;

class PagesController extends \app\controllers\AppController {

	public $publicActions = array(
		'view', 'home', 'contacts', 'howitworks', 'experts'
	);

	public function view() {
		$path = func_get_args() ?: array('home');
        if(preg_match('/experts/', $path[0])) {
            return $this->redirect('/experts');
        }
        $questions = $this->popularQuestions();
		return $this->render(array('template' => join('/', $path), 'data' => array('questions' => $questions)));
	}

    public function stats() {
        return $this->render(array('layout' => 'stats'));
    }

    public function home() {
    	$numOfSolutionsPerProject = Pitch::getNumOfSolutionsPerProject();
    	$numOfCurrentPitches = Pitch::getNumOfCurrentPitches();
    	$totalAwards = Pitch::getTotalAwards();
    	$totalWaitingForClaim = Pitch::getTotalWaitingForClaim();
    	$totalAwardsValue = Pitch::getTotalAwardsValue();
    	$pitches = Pitch::all(array(
			'order' => array(
				/*'pinned' => 'desc',
				'started' => 'desc'*/
                'pinned' => 'desc',
                'ideas_count' => 'desc',
                'price' => 'desc'
			),
            'conditions' => array('status' => array('<' => 1), 'published' => 1),
			'limit' => 3,
			'page' => 1,
		));

        $promos = Promo::all(array(
            'limit' => 2,
            'conditions' => array('enabled' => 1),
            'with' => array('Solution'),
            'order' => array('RAND()')
        ));
        foreach($promos as $promo) {
            if($promo->solution->pitch_id == null) {
                $promos = array();
                break;
            }
            $promo->solution->pitch = Pitch::first($promo->solution->pitch_id);
            $promo->solution->pitch->days = ceil((strtotime($promo->solution->pitch->finishDate) - strtotime($promo->solution->pitch->started)) / DAY);
        }
        $grades = Grade::all(array('limit' => 2, 'conditions' => array('enabled' => 1) ,'order' => array('RAND()'), 'with' => array('Pitch')));
        foreach($grades as $grade) {
            $grade->user = User::first(array('conditions' => array('id' => $grade->user_id)));
        }
        $experts = Expert::all();
        return compact('numOfSolutionsPerProject', 'numOfCurrentPitches', 'totalAwards', 'totalWaitingForClaim', 'totalAwardsValue', 'pitches', 'promos', 'experts', 'grades');
    }

    public function cross() {
        $url = $this->request->query['url'];

        var_dump($url);
        die();
    }

    public function contacts() {
        $success = false;
        if($this->request->data) {
            ContactMailer::contact_mail($this->request->data);
            $success = true;
        }
        $questions = $this->popularQuestions();
        return compact('success', 'questions');
    }

    public function howitworks() {

    }
}

?>