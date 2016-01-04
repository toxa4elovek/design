<?php

namespace app\extensions\command;

use app\extensions\storage\Rcache;
use ByteUnits\Binary;
use Symfony\Component\Stopwatch\Stopwatch;

class CronJob extends \lithium\console\Command
{

    protected $stopWatch = null;

    public function _init()
    {
        parent::_init();
        if (extension_loaded('newrelic')) {
            newrelic_background_job(true);
        }
        Rcache::init();
    }

    protected function _renderHeader($message = null)
    {
        $this->stopWatch = new Stopwatch();
        $this->stopWatch->start('command');
        if ($message === null) {
            $reflect = new \ReflectionClass($this);
            $message = $reflect->getShortName();
        }
        $this->header("{:purple}Starting $message command{:end}");
    }

    protected function _renderFooter($message = 'Finishing command', $durationWarning = 5000)
    {
        $period = $this->stopWatch->stop('command');
        $duration = $period->getDuration();
        $memoryUsage = $period->getMemory();
        $memoryUsageString = Binary::bytes($memoryUsage)->format('MB', ' ');
        $this->out("{:yellow}$message{:end}");
        $this->hr();
        $color = 'green';
        if ($duration > $durationWarning) {
            $color = 'red';
        }
        $this->out("{:$color}Took $duration ms, $memoryUsageString{:end}");
        $this->hr();
    }
}
