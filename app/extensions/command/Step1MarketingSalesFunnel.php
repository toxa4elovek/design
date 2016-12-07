<?php

namespace app\extensions\command;

use app\extensions\helper\NameInflector;
use app\models\Comment;
use app\models\Pitch;
use app\models\User;

class Step1MarketingSalesFunnel extends CronJob
{

    /**
     * Команда добавляет денег на счёт в рекламных целях и постит комментарий в ленту
     */
    public function run()
    {
        $this->_renderHeader();
        $arrayOfProjects = [];
        $finishDateDeltaStart = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() - (2 * DAY)));
        $finishDateDeltaEnd = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() -  DAY));
        $projects = Pitch::all([
            'conditions' => [
                'Pitch.status' => 0,
                'Pitch.published' => 1,
                'Pitch.billed' => 1,
                'Pitch.category_id' => ['!=' => '20'],
                'AND' => [
                    [sprintf("Pitch.started >=  '%s'", $finishDateDeltaStart->format(MYSQL_DATETIME_FORMAT))],
                    [sprintf("Pitch.started <=  '%s'", $finishDateDeltaEnd->format(MYSQL_DATETIME_FORMAT))],
                ]
            ],
            'with' => ['User']
        ]);
        foreach ($projects as $project) {
            $arrayOfProjects[] = $project;
        }

        $arrayOfProjects = array_filter($arrayOfProjects, function ($project) {
            if (User::isSubscriptionActive($project->user->id)) {
                return false;
            }
            return true;
        });

        array_walk($arrayOfProjects, function ($project) {
            $bonus = (int) $project->price * 0.1;
            $nameInflector = new NameInflector();
            $message = sprintf('%s, мы зачислили на лицевой счет %d рублей, которые вы сможете потратить на гонорары дизайнерам, если станете абонентом (https://www.godesigner.ru/pages/subscribe) до %s.',
                '@' . $nameInflector->renderName($project->user->first_name, $project->user->last_name),
                $bonus,
                date('d.m.Y', strtotime($project->finishDate))
            );
            $data = [
                'pitch_id' => $project->id,
                'reply_to' => $project->user->id,
                'user_id' => User::getAdmin(),
                'text' => $message,
                'public' => 0
            ];
            User::fillBalance($project->user->id, $bonus);
            Comment::createComment($data);
        });

        $this->_renderFooter(sprintf('%d projects processed', count($arrayOfProjects)));
    }
}
