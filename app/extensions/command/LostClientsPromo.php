<?php

namespace app\extensions\command;

use app\extensions\mailers\PromoMailer;
use app\models\Pitch;
use app\models\Promocode;
use app\models\Receipt;
use app\models\User;

class LostClientsPromo extends CronJob {

    public function run() {
        $this->header('Welcome to the Lost Clients command!');
        $clientCount = $adminCount = 0;
        $projects = Pitch::all(['conditions' => [
            'published' => 0,
            'billed' => 0,
            'type' => '',
            'started' => [
                '>=' => date('Y-m-d H:i:s', time() - WEEK - DAY),
                '<=' => date('Y-m-d H:i:s', time() - WEEK),
            ],
        ]]);
        foreach($projects as $project) {
            if($project->user_id == 0) {
                continue;
            }
            $profit = Receipt::getProfitForProject($project->id);
            $this->out('Project found, id ' . $project->id . ', profit - ' . $profit);
            if(($profit >= 2500) && ($profit < 8000)) {
                $this->out('Sending email with promocode...');
                $client = User::first($project->user_id);
                $promocode = Promocode::createPromocode($client->id);
                $data = ['user' => $client, 'project' => $project, 'promocode' => $promocode];
                PromoMailer::sendPromoCodeFollowUp($data);
                $clientCount ++;
            }elseif($profit >= 8000) {
                $this->out('Sending email to admin...');
                $client = User::first($project->user_id);
                $data = ['user' => $client, 'project' => $project];
                PromoMailer::sendGoodProfitFollowUp($data);
                $adminCount ++;
            }
        }
        $this->out("$clientCount emails been sent to clients");
        $this->out("$adminCount emails been sent to admin");

    }
}