<?php

namespace app\extensions\command;
use app\extensions\storage\Rcache;

class CronJob extends \lithium\console\Command {

    public function _init() {
        parent::_init();
        // Mark cron job as background task for newrelic
        if (extension_loaded('newrelic')) {
            newrelic_background_job(true);
        }
        Rcache::init();
        $this->out('Initialization complete');
    }

}