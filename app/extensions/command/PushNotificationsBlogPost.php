<?php

namespace app\extensions\command;

use app\models\Post;
use OneSignal\Config;
use OneSignal\OneSignal;

/**
 * Class PushNotificationsBlogPost
 *
 * Команда для отправки пуш уведомлений о новом посте
 * @package app\extensions\command
 */
class PushNotificationsBlogPost extends CronJob
{

    /**
     * Отправляем уведомления, если есть посты за последние пять минут
     */
    public function run()
    {
        $config = new Config();
        $config->setApplicationId('46001cba-49be-4cc5-945a-bac990a6d995');
        $config->setApplicationAuthKey('YTRkYWE2OWMtNjQ4OS00ZjI1LThiZjItZjVlMzdlMWM2Mzc2');
        $config->setUserAuthKey('YmFjYWI1MTQtYjgzOS00NDFhLTg2YjAtY2IzZjc4OWFjNGVm');
        $api = new OneSignal($config);
        $this->_renderHeader();
        $posts = Post::all([
            'conditions' => [
                'published' => 1,
                'created' => [
                    '>=' => date('Y-m-d H:i:s', time() - 5 * MINUTE),
                    '<=' => date('Y-m-d H:i:s', time()),
                ],
            ],
        ]);
        $count = 0;
        foreach ($posts as $post) {
            $count++;
            $url = "https://godesigner.ru/posts/view/$post->id";
            $api->notifications->add([
                'contents' => [
                    'en' => $post->title,
                    'ru' => $post->title
                ],
                'headings' => [
                    'en' => 'Блог GoDesigner.ru',
                    'ru' => 'Блог GoDesigner.ru'
                ],
                'included_segments' => ['All'],
                'url' => $url,
                'isChromeWeb' => true,
            ]);
            $api->notifications->add([
                'contents' => [
                    'en' => 'Блог GoDesigner.ru ' . $post->title,
                    'ru' => 'Блог GoDesigner.ru ' . $post->title
                ],
                'included_segments' => ['All'],
                'url' => $url,
                'isSafari' => true,
            ]);
        }
        $this->_renderFooter("$count push notifications sent.");
    }
}
