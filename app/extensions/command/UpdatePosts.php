<?php

namespace app\extensions\command;

use app\models\Post;

class UpdatePosts extends CronJob
{

    public function run()
    {
        $this->_renderHeader();
        $posts = Post::all(['conditions' => [
            'published' => 1
        ]]);
        $count = 0;
        foreach ($posts as $project) {
            $fix = false;
            if (preg_match('@http://(.*)$@', $project->imageurl)) {
                $project->imageurl = preg_replace('@http://(.*)$@', 'https://$1', $project->imageurl);
                $fix = true;
            }
            if (preg_match('@http://(godesigner.ru)@', $project->full)) {
                $project->full = preg_replace('@http://(godesigner.ru)@', 'https://$1', $project->full);
                $fix = true;
            }
            if ($fix) {
                $project->save();
                $count++;
            }
        }
        $this->_renderFooter("$count posts fixed");
    }
}
