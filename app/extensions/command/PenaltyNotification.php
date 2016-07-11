<?php

namespace app\extensions\command;

use app\extensions\mailers\NotificationsMailer;
use app\models\Pitch;
use app\models\User;

/**
 * Class TimeoutProjectOnChooseWinnerStage
 *
 * Команда для обновления статусов смс
 * @package app\extensions\command
 */
class PenaltyNotification extends CronJob
{

    /**
     * Отправляем почтовые уведомления заказчикам о том, что будет штраф
     */
    public function run()
    {
        $this->_renderHeader();
        $helper = new \app\extensions\helper\Pitch();
        $projects = Pitch::all(['conditions' => ['category_id' => ['!=' => 20], 'status' => 1, 'awarded' => 0]]);
        $count = 0;
        foreach ($projects as $project) {
            $lowestDelta = ($helper->getChooseWinnerTime($project) - 12 * HOUR);
            $highDelta = ($helper->getChooseWinnerTime($project) - 11 * HOUR);
            if ($lowestDelta <= time() && $highDelta >= time()) {
                $count++;
                $user = User::first($project->user_id);
                if (($project->guaranteed == 0) && ($project->pitchData()['avgNum'] >= 3.0)) {
                    NotificationsMailer::penaltyClientNotificationNonGuarantee($user, $project, $helper->getChooseWinnerTime($project));
                } else {
                    NotificationsMailer::penaltyClientNotificationGuarantee($user, $project, $helper->getChooseWinnerTime($project));
                }
            }

            $penalty = $helper->getPenalty($project);
            if (($penalty > 4500) && ($penalty < 4550)) {
                NotificationsMailer::penaltyNotification($project, $penalty);
            }
        }
        $this->_renderFooter("$count messages send.");
    }
}
