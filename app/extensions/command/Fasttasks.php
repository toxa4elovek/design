<?php

namespace app\extensions\command;

use app\extensions\mailers\CommentsMailer;
use app\models\Solution;
use app\models\SubscriptionPlan;
use \app\models\Task;
use \app\models\News;
use \app\models\User;
use app\extensions\mailers\SolutionsMailer;
use app\extensions\mailers\NotificationsMailer;
use \app\models\Event;
use app\extensions\social\TwitterAPI;
use app\extensions\social\FacebookAPI;
use app\extensions\social\VKAPI;
use app\extensions\social\SocialMediaManager;

class Fasttasks extends CronJob
{

    public function run()
    {
        $this->header('Welcome to the Fasttasks Command');
        set_time_limit(0);
        $tasks = Task::all(['conditions' => [
            'completed' => 0,
            'type' => ['!=' => 'newpitch'],
        ]]);
        $count = 0;
        foreach ($tasks as $task) {
            $methodName = '__' . $task->type;
            if (method_exists('app\extensions\command\Fasttasks', $methodName)) {
                if (strtotime($task->date) < time()) {
                    $task->markAsCompleted();
                    $result = Fasttasks::$methodName($task);
                    $count++;
                }
            }
        }
        if ($count) {
            $this->out($count . ' tasks completed');
        } else {
            $this->out('No tasks are in due.');
        }
    }

    private function __newSolutionNotification($task)
    {
        if ($result = SolutionsMailer::sendNewSolutionNotification($task->model_id)) {
            $this->out('New solution notification sent');
        } else {
            $this->out('User do not want to receive notification for solution ' . $task->model_id);
        }
        return true;
    }

    private function __victoryNotification($task)
    {
        if ($result = SolutionsMailer::sendVictoryNotification($task->model_id)) {
            $this->out('New victory notification sent');
        } else {
            $this->out('Error sending victory notification');
        }
        return true;
    }

    private function __victoryNotificationTwitter($task)
    {
        $solution = Solution::first($task->model_id);
        if ($result = User::sendMessageToSocial($solution)) {
            $this->out('Victory notification sent');
        } else {
            $this->out('Victory notification was not sent');
        }
        return $result;
    }

    private function __newCommentFromAdminNotification($task)
    {
        if ($count = CommentsMailer::sendNewCommentFromAdminNotification($task->model_id)) {
            $this->out($count . ' new comment from admin notifications sent');
        } else {
            $this->out('no new comment from admin notifications sent');
        }
        return true;
    }

    private function __newPersonalCommentNotification($task)
    {
        if ($result = CommentsMailer::sendNewPersonalCommentNotification($task->model_id)) {
            $this->out('New personal comment notifications sent');
        } else {
            $this->out('User do not want to receive this notification');
        }
        return true;
    }

    private function __dvaSpam($task)
    {
        $count = User::sendDvaSpam();
        $this->out('Emails has been set to ' . $count . ' users');
        return true;
    }

    private function __postNewsToSocial($task)
    {
        if ($result = Event::first($task->model_id)) {
            if (($news = News::first($result->news_id)) && ($news->hidden == 0)) {
                $manager = new SocialMediaManager();

                $vkApi = new VKAPI();
                $data = [
                    'message' => $news->short,
                    'picture' => 'https://godesigner.ru/news?event=' . $result->id . $manager->getFeedSharingAnalyticsString('vk')
                ];
                $id = $vkApi->postMessageToPage($data);
                $facebookApi = new FacebookAPI();
                $data = [
                    'message' => $news->title,
                    'link' => 'https://godesigner.ru/news?event=' . $result->id . $manager->getFeedSharingAnalyticsString('facebook'),
                ];
                $facebookApi->postMessageToPage($data);

                $twitterApi = new TwitterAPI();
                $tweetBody = $news->title . ' — ' . $news->short;
                if (mb_strlen($tweetBody, 'UTF-8') > 90) {
                    $tweetBody = mb_strimwidth($tweetBody, 0, 87, '...', 'UTF-8');
                }
                $data = [
                    'message' => $tweetBody . ' https://godesigner.ru/news?event=' . $result->id . $manager->getFeedSharingAnalyticsString('twitter'),
                    'picture' => '/var/godesigner/webroot/' . $news->imageurl
                ];
                $twitterApi->postMessageToPage($data);
            } else {
                var_dump($news->data());
            }
        }
    }

    private function __postNewsToSocialDelayed($task)
    {
        if ($result = Event::first($task->model_id)) {
            if (($news = News::first($result->news_id)) && ($news->hidden == 0)) {
                $manager = new SocialMediaManager();

                $vkApi = new VKAPI();
                $data = [
                    'message' => $news->short,
                    'owner_id' => '-26880133',
                    'picture' => 'https://godesigner.ru/news?event=' . $result->id . $manager->getFeedSharingAnalyticsString('vk')
                ];
                $id = $vkApi->postMessageToPage($data);
                $facebookApi = new FacebookAPI();
                $data = [
                    'message' => $news->title,
                    'page_id' => '112408302103669',
                    'link' => 'https://godesigner.ru/news?event=' . $result->id . $manager->getFeedSharingAnalyticsString('facebook'),
                ];
                $facebookApi->postMessageToPage($data);

                $keys = [
                    'consumer_key' => '8KowPOOLHqbLQPKt8JpwnLpTn',
                    'consumer_secret' => 'Guna29r1BY8gEofz2amAIfPo1XcHJWNGI8Nzn6wiEwNlykAHhy',
                    'user_token' => '76610418-JxUuuxQdUOaxc3uwxRjBUG4rXUdIABjNYAuhKP7uh',
                    'user_secret' => '8qoejI0OTXHq56wp2QKPz16KoiB9w1sQQUncl6ilL20eh'
                ];
                $twitterApi = new TwitterAPI($keys);
                $tweetBody = $news->title . ' — ' . $news->short;
                if (mb_strlen($tweetBody, 'UTF-8') > 90) {
                    $tweetBody = mb_strimwidth($tweetBody, 0, 87, '...', 'UTF-8');
                }
                $data = [
                    'message' => $tweetBody . ' https://godesigner.ru/news?event=' . $result->id  . $manager->getFeedSharingAnalyticsString('twitter'),
                    'picture' => '/var/godesigner/webroot/' . $news->imageurl
                ];
                $twitterApi->postMessageToPage($data);
            }
        }
    }

    /**
     * Метод отправляет уведомление о том, что пользователь пополнил баланс
     *
     * @param $task
     */
    private function __emailFillBalanceSuccessNotification($task)
    {
        $plan = SubscriptionPlan::first($task->model_id);
        $user = User::first($plan->user_id);
        if ($result = NotificationsMailer::sendFillBalanceSuccess($user, $plan)) {
            $this->out('New fill balance notification sent');
        } else {
            $this->out('New fill balance notification failed');
        }
    }
}
