<?php

namespace app\extensions\command;

use \app\models\News;

class ResetNewsViews extends \app\extensions\command\CronJob {

    public function run() {
        $news = News::all();
        foreach ($news as $n) {
            $n->views = 0;
        }
        $news->save();
    }

}
