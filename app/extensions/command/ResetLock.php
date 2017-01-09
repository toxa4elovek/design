<?php

namespace app\extensions\command;

use app\models\Post;

class ResetLock extends CronJob
{

    public function run()
    {
        $this->out(date('Y-m-d H:i:s', time() - 3 * MINUTE));
        $this->out('Looking for outdated locks...');
        $posts = Post::all([
            'conditions' => [
                '`lock`' => ['!=' => ''],
                'lastEditTime' => ['<' => date('Y-m-d H:i:s', time() - 1 * MINUTE)]
            ]
        ]);
        if (count($posts)) {
            $this->out(count($posts) . ' outdated locks found, unlocking...');
            foreach ($posts as $post) {
                Post::unlock($post->id);
            }
        } else {
            $this->out('No outdated locks found!');
        }
    }
}
