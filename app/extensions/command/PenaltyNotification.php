<?php

namespace app\extensions\command;

use app\extensions\mailers\NotificationsMailer;
use app\models\Pitch;

/**
 * Class TimeoutProjectOnChooseWinnerStage
 *
 * Команда для обновления статусов смс
 * @package app\extensions\command
 */
class PenaltyNotification extends CronJob
{

    /**
     * Обновляем статус смс-сообщениям со статусом ожидания
     */
    public function run()
    {
        $this->_renderHeader();
        $helper = new \app\extensions\helper\Pitch();
        $projects = Pitch::all(['conditions' => ['category_id' => ['!=' => 20], 'status' => 1, 'awarded' => 0]]);
        $count = 0;
        foreach($projects as $project) {
            $penalty = $helper->getPenalty($project);
            if($penalty > 4500) {
                NotificationsMailer::penaltyNotification($project, $penalty);
            }
        }
        $this->_renderFooter("$count messages updated.");
    }
}
