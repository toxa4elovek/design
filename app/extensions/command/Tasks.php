<?php

namespace app\extensions\command;

use app\models\Category;
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
            'type' => 'newpitch',
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
