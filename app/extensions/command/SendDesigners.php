<?php

namespace app\extensions\command;


use app\extensions\mailers\SpamMailer;
use app\models\Pitch;
use app\models\User;

class SendDesigners extends CronJob
{

    public function run()
    {
        $this->header('Welcome to the Send Designers command!');
        function susbcribersGenerator($total, $solutionsUserIds) {
            $totalPages = ceil($total / 100);
            for ($i = 1; $i <= $totalPages; $i++) {
                $users = User::all(
                    [
                        'fields' => ['User.id', 'User.email', 'User.created', 'User.first_name'],
                        'conditions' => [
                            'User.id' => $solutionsUserIds,
                            'User.email' => ['!=' => ''],
                            'User.email_digest' => 1,
                            'User.confirmed_email' => 1,
                            'User.active' => 1,
                            'User.isDesigner' => 1
                        ],
                        'page' => $i,
                        'limit' => 100
                    ]);
                yield $users;
            }
        }

        $activeProject = Pitch::all([
            'fields' => ['Pitch.id', 'Solution.user_id'],
            'conditions' => [
                'Pitch.billed' => 1,
                'Pitch.published' => 1,
                'Pitch.status' => [0, 1],
                'Pitch.awarded' => 0,
            ],
            'with' => ['Solution']
        ]);

        $solutionsUserIds = [];

        foreach($activeProject as $project) {
            foreach($project->solutions as $solution) {
                $solutionsUserIds[] = $solution->user_id;
            }
        }

        $total = User::count([
            'conditions' => [
                'User.id' => $solutionsUserIds,
                'User.email' => ['!=' => ''],
                'User.email_digest' => 1,
                'User.confirmed_email' => 1,
                'User.active' => 1,
                'User.isDesigner' => 1
            ]
        ]);

        $count = 0;
        foreach(susbcribersGenerator($total, $solutionsUserIds) as $users) {
            foreach($users as $user) {
                $this->out($user->email);
                SpamMailer::newComission(['user' => $user]);
                $count++;
            }
        }

        $this->out($total . ' potential receivers.');
        $this->out($count . ' real receivers.');
    }
}
