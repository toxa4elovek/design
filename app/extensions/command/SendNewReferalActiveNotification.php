<?php

namespace app\extensions\command;

use app\extensions\mailers\NotificationsMailer;
use app\models\Note;
use app\models\Pitch;
use app\models\Solution;
use app\models\User;

class SendNewReferalActiveNotification extends CronJob
{

    /**
     * Команда отправляет уведомление заказчикам, у которых в периоде через +12-+13 часов начнётся штрафной период
     */
    public function run()
    {
        $this->_renderHeader();
        $arrayOfProjects = [];
        $finishDateDeltaStart = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() - (7 * DAY) + HOUR));
        $finishDateDeltaEnd = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() - 7 * DAY));
        $projects = Pitch::all([
            'conditions' => [
                'Pitch.status' => ['>' => 0],
                'Pitch.published' => 1,
                'Pitch.billed' => 1,
                'AND' => [
                    [sprintf("Pitch.finishDate >=  '%s'", $finishDateDeltaStart->format(MYSQL_DATETIME_FORMAT))],
                    [sprintf("Pitch.finishDate <=  '%s'", $finishDateDeltaEnd->format(MYSQL_DATETIME_FORMAT))],
                ]
            ],
        ]);
        foreach ($projects as $project) {
            $arrayOfProjects[] = $project;
        }

        $arrayOfProjects = array_filter($arrayOfProjects, function ($project) {
            if (!($note = Note::first(['conditions' => ['Note.pitch_id' => $project->id]])) || ((int) $note->status === 2)) {
                return false;
            }
            if (!$solution = Solution::first($project->awarded)) {
                return false;
            }
            if (User::getAwardedSolutionNum($solution->user_id) > 1) {
                return false;
            }
            return true;
        });

        array_walk($arrayOfProjects, function ($project) {
            //NotificationsMailer::sendPenaltyEndsSoonReminder($project);
        });

        $this->_renderFooter(sprintf('%d email sent', count($arrayOfProjects)));
    }
}
