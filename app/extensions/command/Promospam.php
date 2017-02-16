<?php

namespace app\extensions\command;

use app\models\Grade;
use app\models\Pitch;
use app\models\Promocode;
use app\models\User;

/**
 * Class Promospam
 *
 * Команда рассылает промокод не абонентам за каждый завершённый проект
 * @package app\extensions\command
 */
class Promospam extends CronJob
{

    public function run()
    {
        $this->_renderHeader();
        $arrayOfProjects = [];
        $finishDateDeltaStart = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() - DAY));
        $finishDateDeltaEnd = new \DateTime(date(MYSQL_DATETIME_FORMAT, time()));
        $projects = Pitch::all([
            'conditions' => [
                'Pitch.status' => 2,
                'Pitch.published' => 1,
                'Pitch.type' => ['', 'company_project'],
                'AND' => [
                    [sprintf("Pitch.totalFinishDate >=  '%s'", $finishDateDeltaStart->format(MYSQL_DATETIME_FORMAT))],
                    [sprintf("Pitch.totalFinishDate <=  '%s'", $finishDateDeltaEnd->format(MYSQL_DATETIME_FORMAT))],
                ],
                'User.subscription_status' => 0,
            ],
            'with' => ['User']
        ]);
        foreach ($projects as $project) {
            $arrayOfProjects[] = $project;
        }
        $arrayOfProjects = array_filter($arrayOfProjects, function($project) {
            if(User::isSubscriptionActive($project->user_id)) {
                return false;
            }
            if($promocode = Promocode::first(['conditions' => ['pitch_id' => $project->id]])) {
                return false;
            }
            $grade = Grade::first(['conditions' => ['pitch_id' => $project->id, 'user_id' => $project->user_id]]);
            return !((!$grade) || (!in_array((int) $grade->site_rating, [4,5], true)));
        });
        array_walk($arrayOfProjects, function ($project) {
            User::sendPromoCode($project->user_id);
        });
        $this->_renderFooter('emails sent');
    }
}
