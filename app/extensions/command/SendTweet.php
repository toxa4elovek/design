<?php

namespace app\extensions\command;

use \app\models\Pitch;
use app\extensions\social\TwitterAPI;

class SendTweet extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Welcome to the SendTweet command!');
        TwitterAPI::sendTweet('http://www.godesigner.ru/posts/view/269');
        $this->out('finish.');
    }
}