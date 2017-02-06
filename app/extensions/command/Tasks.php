<?php

namespace app\extensions\command;

use app\extensions\mailers\SpamMailer;
use app\models\Category;
use app\models\News;
use \app\models\Task;
use \app\models\Pitch;
use \app\models\User;
use OneSignal\Config;
use OneSignal\OneSignal;

class Tasks extends CronJob
{

    public function run()
    {
        $this->header('Welcome to the Task Command');
        set_time_limit(0);
        $tasks = Task::all(['conditions' => [
            'completed' => 0,
            'type' => ['newpitch', 'newsDigest'],
        ]]);
        $count = count($tasks);
        foreach ($tasks as $task) {
            $methodName = '__' . $task->type;
            if (method_exists('app\extensions\command\Tasks', $methodName)) {
                $task->markAsCompleted();
                Tasks::$methodName($task);
            }
        }
        if ($count) {
            $this->out($count . ' tasks completed');
        } else {
            $this->out('No tasks are in due.');
        }
    }

    private function __newsdigest($task) {
        $unserialized = unserialize($task->serialized_data);
        $ids = $unserialized['ids'];
        $subject = $unserialized['subject'];
        $useExternalService = $unserialized['useExternalService'];
        $posts = News::all(['conditions' => ['id' => array_values($ids)], 'order' => ['created' => 'desc']]);
        foreach ($posts as $post) {
            if (preg_match('@^/events@', $post->imageurl)) {
                $post->imageurl = 'https://godesigner.ru' . $post->imageurl;
            }
        }
        function susbcribersGenerator($total) {
            $totalPages = ceil($total / 100);
            for ($i = 1; $i <= $totalPages; $i++) {
                $users = User::all(
                    [
                        'fields' => ['User.id', 'User.email', 'User.created'],
                        'conditions' => [
                            'User.email' => ['!=' => ''],
                            'User.email_digest' => 1,
                            'User.confirmed_email' => 1,
                            'User.active' => 1
                        ],
                        'page' => $i,
                        'limit' => 100
                    ]);
                yield $users;
            }
        }
        if (count($posts) > 0) {
            if($subject === '') {
                $subject = $posts->first()->title;
            }
            $total = User::count(['fields' => ['User.id', 'User.email'], 'conditions' => ['User.email' => ['!=' => ''], 'User.email_digest' => 1, 'User.confirmed_email' => 1, 'User.active' => 1]]);
            foreach(susbcribersGenerator($total) as $users) {
                foreach($users as $user) {
                    $data = [
                        'email' => $user->email,
                        'subject' => $subject,
                        'posts' => $posts,
                        'user' => $user,
                        'useExternalService' => $useExternalService
                    ];
                    $this->out($user->email);
                    SpamMailer::blognewsdigest($data);
                }
            }
        }
        $this->out('End of sending news digest');
    }

    private function __newpitch($task)
    {
        $pitch = Pitch::first(['conditions' =>['id' => $task->model_id]]);
        $category = Category::first($pitch->category_id);
        $config = new Config();
        $config->setApplicationId('46001cba-49be-4cc5-945a-bac990a6d995');
        $config->setApplicationAuthKey('YTRkYWE2OWMtNjQ4OS00ZjI1LThiZjItZjVlMzdlMWM2Mzc2');
        $config->setUserAuthKey('YmFjYWI1MTQtYjgzOS00NDFhLTg2YjAtY2IzZjc4OWFjNGVm');
        $api = new OneSignal($config);
        $api->notifications->add([
            'contents' => [
                'en' => $pitch->title,
                'ru' => $pitch->title
            ],
            'headings' => [
                'en' => "Новый проект! ($category->title)",
                'ru' => "Новый проект! ($category->title)"
            ],
            'included_segments' => ['All'],
            'url' => "https://godesigner.ru/pitches/details/$pitch->id",
            'isChromeWeb' => true,
        ]);
        $api->notifications->add([
            'contents' => [
                'en' => "Новый проект! ($category->title) " . $pitch->title,
                'ru' => "Новый проект! ($category->title) " . $pitch->title
            ],
            'included_segments' => ['All'],
            'url' => "https://godesigner.ru/pitches/details/$pitch->id",
            'isSafari' => true,
        ]);
        $params = ['pitch' => $pitch];
        User::sendSpamNewPitch($params);
        $this->out('New pitch email has been sent');
    }
}
