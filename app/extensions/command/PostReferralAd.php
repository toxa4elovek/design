<?php

namespace app\extensions\command;

use app\models\Comment;
use \app\models\Pitch;

/**
 * Class PostReferralAd
 *
 * Команда размещяет комментарий в проект семидневной давности с рекламой реферальной программы
 * Запуск - раз в час
 *
 * @package app\extensions\command
 */
class PostReferralAd extends CronJob
{

    /**
     * Основной метод команды
     */
    public function run()
    {
        $this->header('Welcome to the PostReferalAd command!');
        $projects = Pitch::all(['conditions' => [
            'published' => 1,
            'started' => [
                '>=' => date('Y-m-d H:i:s', time() - WEEK - HOUR),
                '<=' => date('Y-m-d H:i:s', time() - WEEK),
            ],
        ]]);
        $count = count($projects);
        $this->out("Found $count projects for ad");
        foreach ($projects as $project) {
            $message = 'Вы получите 500 рублей на&nbsp;телефон, когда ваши друзья создадут проект на&nbsp;GoDesigner. Узнать подробности и&nbsp;получить ссылку тут: https://www.godesigner.ru/pages/referal';
            $data = [
                'pitch_id' => $project->id,
                'user_id' => 108,
                'text' => $message,
                'public' => 1
            ];
            Comment::createComment($data);
            $this->out("Posted comment for project # $project->id");
        }
        $this->out('Command finished');
    }
}
