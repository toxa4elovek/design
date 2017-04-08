<?php

namespace app\extensions\command;


use app\extensions\billing\Payture;
use app\models\Pitch;

class PaymentTest extends CronJob
{

    public function run()
    {
        $this->header('Welcome to the Sync Bounces command!');
        $project = Pitch::first(123323);
        $payture = Payture::unblock($project->payture_id, (int) $project->total * 100);
        var_dump($payture);
        $this->out(' end');
    }
}
