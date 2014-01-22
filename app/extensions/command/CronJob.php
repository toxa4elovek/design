<?php

namespace app\extensions\command;

class CronJob extends \lithium\console\Command {

    public function _init() {
        echo 'Task';
        die();
    }

}