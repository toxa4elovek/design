<?php

namespace app\extensions\command;

use app\models\Comment;
use \app\models\Pitch;
use app\models\User;

/**
 * Class PostReferralAd
 *
 * Команда размещяет комментарий в проект однодневной давности с предупреждением
 * Запуск - раз в час
 *
 * @package app\extensions\command
 */
class PostWarningComment extends CronJob
{

    /**
     * Основной метод команды
     */
    public function run()
    {
        $this->header('Welcome to the PostWarningComment command!');
        $projects = Pitch::all(['conditions' => [
            'published' => 1,
            'started' => [
                '>=' => date(MYSQL_DATETIME_FORMAT, time() - DAY - HOUR),
                '<=' => date(MYSQL_DATETIME_FORMAT, time() - DAY),
            ],
        ]]);
        $projectArray = [];
        foreach ($projects as $project) {
            $projectArray[] = $project;
        }
        $projectArray = array_filter($projectArray, function ($project) {
           if ((int) $project->category_id === 20) {
               $client = User::first($project->user_id);
               if ((User::isSubscriptionActive($client->id, $client))
                   && (in_array((int) $client->subscription_status, [2, 3]))) {
                   return false;
               }
           }
           return true;
        });
        $count = count($projectArray);
        $this->out("Found $count projects for comment after filters");
        array_walk($projectArray, function ($project) {
            $message = 'Нельзя также размещать ссылки на&nbsp;работы в&nbsp;100% разрешении. Те, кто нарушили правила сервиса, будут блокированы для участи в&nbsp;этом проекте. Подробнее тут:
https://www.godesigner.ru/answers/view/72';
            $data = [
                'pitch_id' => $project->id,
                'user_id' => 108,
                'text' => $message,
                'public' => 1
            ];
            Comment::createComment($data);
            $this->out("Posted comment for project # $project->id");
        });
        $this->out('Command finished');
    }
}
