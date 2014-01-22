<?php

namespace app\extensions\command;

class CronJob extends \lithium\console\Command {

    public function _init() {
        parent::_init();
        // Mark cron job as background task for newrelic
        if (extension_loaded('newrelic')) {
            newrelic_background_job(true);
        }
        $this->out('Initialization complete');
    }

}