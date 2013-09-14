<?php

namespace app\extensions\command;

use \app\models\Pitch;

class SendReports extends \lithium\console\Command {

    public function run() {
        $this->header('Welcome to the SendReports command!');
        $count = Pitch::sendReports();
        $reports = ($count == 1) ? ' report' : ' reports';
        $have = ($count == 1) ? ' has' : ' have';
        $this->out($count . $reports . $have . ' been sent.');
    }
}