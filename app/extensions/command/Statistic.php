<?php

namespace app\extensions\command;

use \app\models\Pitch;
use \app\models\Solution;
use \lithium\storage\Cache;

class Statistic extends \lithium\console\Command {

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

        $default = array('adapter' => 'Apc');
        Cache::config(array(
            'files' => array('adapter' => 'File', 'strategies' => array('Serializer')),
            'default' => array('adapter' => 'Apc')
        ));
        Cache::write('files', 'statistic', $statistic, '+2 hour');
        $this->out('Cache has been updated.');
    }
}