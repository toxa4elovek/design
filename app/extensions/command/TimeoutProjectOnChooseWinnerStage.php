<?php

namespace app\extensions\command;

use app\models\Pitch;
use app\models\Solution;

/**
 * Class TimeoutProjectOnChooseWinnerStage
 *
 * Команда для обновления статусов смс
 * @package app\extensions\command
 */
class TimeoutProjectOnChooseWinnerStage extends CronJob
{

    /**
     * Обновляем статус смс-сообщениям со статусом ожидания
     */
    public function run()
    {
        $this->_renderHeader();
        $helper = new \app\extensions\helper\Pitch();
        $projects = Pitch::all(['conditions' => ['status' => 1, 'awarded' => 0]]);
        $currentDateTime = new \DateTime();
        $count = 0;
        foreach($projects as $project) {
            if($project->isCopyrighting() && ($project->guaranteed == 0)) {
                $chooseWinnerTime = $helper->getChooseWinnerTime($project) + (10 * DAY);
                $beyondPenaltyDate = new \DateTime();
                $beyondPenaltyDate->setTimestamp($chooseWinnerTime);
                if($currentDateTime > $beyondPenaltyDate) {
                    $this->out('Need to perform split reward');
                    $this->out($project->id);
                    $winner = Solution::first([
                        'conditions' => ['pitch_id' => $project->id],
                        'order' => ['RAND()'],
                        'with' => ['Pitch']
                    ]);
                    Solution::selectSolution($winner);
                    $updatedWinner = Solution::first(array('conditions' => array('id' => $winner->id)));
                    $updatedWinner->step = 4;
                    $updatedWinner->awarded = 1;
                    $updatedWinner->nominated = 0;
                    $updatedWinner->save();
                    $project->awarded = $updatedWinner->id;
                    if($project->pitchData()['avgNum'] >= 3.0) {
                        $project->split = 1;
                    }else {
                        $project->split = 0;
                    }
                    $project->status = 2;
                    $project->totalFinishDate = date('Y-m-d H:i:s');
                    $project->save();
                }
            }
        }
        $this->_renderFooter("$count messages updated.");
    }
}
