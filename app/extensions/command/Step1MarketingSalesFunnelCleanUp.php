<?php

namespace app\extensions\command;

use app\extensions\helper\NameInflector;
use app\models\Comment;
use app\models\Pitch;
use app\models\SubscriptionPlan;
use app\models\User;

class Step1MarketingSalesFunnelCleanUp extends CronJob
{

    /**
     * Команда добавляет денег на счёт в рекламных целях и постит комментарий в ленту
     */
    public function run()
    {
        $this->_renderHeader();
        $arrayOfProjects = [];
        $projects = Pitch::all([
            'conditions' => [
                'Pitch.type' => 'fund-balance',
                'Pitch.billed' => 1,
                'Pitch.title' => ['LIKE' => [sprintf('%%сгорает %s%%', date('d.m.Y'))]],
                'User.subscription_status' => 0
            ],
            'with' => ['User']
        ]);
        foreach ($projects as $project) {
            $arrayOfProjects[] = $project;
        }
        array_walk($arrayOfProjects, function ($project) {
            $balanceChange = SubscriptionPlan::getFundBalanceForPayment($project->id);
            User::reduceBalance($project->user_id, $balanceChange);
            $project->delete();
        });

        $this->_renderFooter(sprintf('%d projects processed', count($arrayOfProjects)));
    }
}
