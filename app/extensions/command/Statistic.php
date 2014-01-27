<?php

namespace app\extensions\command;

use \app\models\Pitch;
use \app\models\Solution;
use app\extensions\storage\Rcache;

class Statistic extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the Statistic command!');
        $statistic = array(
            'numOfSolutionsPerProject' => array(
                '1' => Pitch::getNumOfSolutionsPerProjectOfCategory(1),
                '3' => Pitch::getNumOfSolutionsPerProjectOfCategory(3),
                '7' => Pitch::getNumOfSolutionsPerProjectOfCategory(7),
            ),
            'numOfCurrentPitches' => Pitch::getNumOfCurrentPitches(),
            'totalAwards' => Pitch::getTotalAwards(),
            'totalWaitingForClaim' => Pitch::getTotalWaitingForClaim(),
            //'totalAwardsValue' => Pitch::getTotalAwardsValue(),
            'totalParticipants' => Solution::getTotalParticipants(),
            'lastDaySolutionNum' => Solution::getNumOfUploadedSolutionInLastDay(),
        );
        Rcache::init();
        Rcache::write('statistic', $statistic, '+2 hour');
        $this->out('Rcache has been updated.');
    }
}