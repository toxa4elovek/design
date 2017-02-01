<?php

namespace app\extensions\mailers;

use app\models\Pitch;
use app\models\User;

class SpamMailer extends \li3_mailer\extensions\Mailer
{

    public static function newpitch($data)
    {
        return self::_mail([
            'to' => $data['user']->email,
            'subject' => 'Новый проект!',
            'data' => $data
        ]);
    }

    public static function newPremiumProject($data)
    {
        return self::_mail([
            'to' => $data['user']->email,
            'subject' => 'Новый премиум проект!',
            'data' => $data
        ]);
    }

    public static function promocode($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Подарочный промокод',
            'data' => $data
        ]);
    }

    public static function comeback($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Проект требует вашего внимания',
            'data' => $data
        ]);
    }

    public static function newclientpitch($data)
    {
        return self::_mail([
            'to' => $data['user']->email,
            'subject' => 'Проект успешно оплачен!',
            'data' => $data
        ]);
    }

    public static function sendclientexpertspeaking($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Добавлено экспертное мнение',
            'data' => $data,
        ]);
    }

    public static function newbriefedpitch($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Заполнить бриф: ' . $data['pitch']->{'phone-brief'},
            'data' => $data
        ]);
    }

    /**
     * Метод отсылает админам уведомление о купленным лого с распродажи
     *
     * @param $project
     * @return array
     */
    public static function sendNewLogosaleProject($project)
    {
        $users = User::all(['conditions' => ['id' => [4, 5, 32]]]);
        $userIds = [];
        foreach ($users as $user) {
            $data['pitch'] = $project;
            $data['user'] = $user;
            if (self::newlogosaleproject($data)) {
                $userIds[] = $user->id;
            }
        }
        return $userIds;
    }

    /**
     * Метод отправляет пользователю уведомление о купленном лого с распродажи
     *
     * @param $data
     * @return bool
     */
    public static function newlogosaleproject($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Логотип с распродажи купили!',
            'data' => $data
        ]);
    }

    public static function newmoderatedpitch($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Новый проект на модерацию!',
            'data' => $data
        ]);
    }

    public static function newaddon($data)
    {
        $addonsArray = [];
        if ($data['addon']->experts == 1) {
            $addonsArray[] = 'экспертное мнение';
        }
        if ($data['addon']->prolong == 1) {
            $addonsArray[] = 'продление';
        }
        if ($data['addon']->brief == 1) {
            $addonsArray[] = 'заполнение брифа';
        }
        if ($data['addon']->pinned == 1) {
            $addonsArray[] = 'прокачать бриф';
        }
        if ($data['addon']->guaranteed == 1) {
            $addonsArray[] = 'гарантированный проект';
        }
        if ($data['addon']->private == 1) {
            $addonsArray[] = 'скрытый проект';
        }
        $stringSubject = 'Новая доп. опция!';
        $data['stringAddons'] = implode(', ', $addonsArray);
        $data['stringAction'] = 'куплена дополнительная опция';
        if (count($addonsArray) > 1) {
            $stringSubject = 'Новые доп. опции!';
            $data['stringAction'] = 'куплены дополнительная опции';
        }

        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => $stringSubject,
            'data' => $data
        ]);
    }

    public static function newaddonbrief($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Новая доп. опция "заполнить бриф"!',
            'data' => $data
        ]);
    }

    public static function dailydigest($data)
    {
        return self::_mail([
            'to' => $data['user']->email,
            'subject' => 'Добавлено решение или комментарий',
            'data' => $data
        ]);
    }

    public static function dailypitch($data)
    {
        return self::_mail([
            'to' => $data['user']->email,
            'subject' => 'Новые проекты!',
            'data' => $data
        ]);
    }

    /**
     * @deprecated
     * @param $data
     * @return bool
     */
    public static function choosewinner($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Срок анонсирования победителя',
            'data' => $data
        ]);
    }

    public static function step2($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Доработка макетов',
            'data' => $data
        ]);
    }

    public static function step3($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Получение исходников',
            'data' => $data
        ]);
    }

    public static function step4($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Завершение рабочего процесса, рейтинг.',
            'data' => $data
        ]);
    }

    public static function newcomment($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Новый комментарий к вашему решению!',
            'data' => $data
        ]);
    }

    public static function newwincomment($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Обновление завершающего процесса!',
            'data' => $data
        ]);
    }

    public static function winstep($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Обновление завершающего процесса!',
            'data' => $data
        ]);
    }

    public static function newadminnotification($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['admin'],
            'subject' => 'Оставлен комментарий после завершения проекта',
            'data' => $data
        ]);
    }

    public static function firstsolution($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Добавлено решение',
            'data' => $data
        ]);
    }

    public static function expertselected($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Экспертное мнение',
            'data' => $data
        ]);
    }

    public static function expertneedpostcomment($data)
    {
        return self::_mail([
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Ожидается ваше экспертное мнение',
                    'data' => $data
        ]);
    }

    public static function expertreminder($data)
    {
        return self::_mail([
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Ожидается ваше экспертное мнение',
                    'data' => $data
        ]);
    }

    public static function openletter($data)
    {
        return self::_mail([
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => '5 рекомендаций для улучшения вашего брифа',
                    'data' => $data,
                    'reply-to' => 'devochkina@godesigner.ru',
        ]);
    }

    public static function finishreports($data)
    {
        return self::_mail([
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Закрывающие документы для вашего проекта',
                    'data' => $data,
                    'reply-to' => 'va@godesigner.ru',
        ]);
    }

    public static function duration($data)
    {
        return self::_mail([
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Хотите продлить проект и увеличить бюджет?',
                    'data' => $data,
                    'reply-to' => 'team@godesigner.ru',
        ]);
    }

    public static function expertaddon($data)
    {
        return self::_mail([
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Нужна помощь в выборе?',
                    'data' => $data,
                    'reply-to' => 'team@godesigner.ru',
        ]);
    }

    public static function briefaddon($data)
    {
        return self::_mail([
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Появились сомнения в правильности заполнения брифа?',
                    'data' => $data,
                    'reply-to' => 'devochkina@godesigner.ru',
        ]);
    }

    public static function referalspam($data)
    {
        return self::_mail([
                    'to' => $data['email'],
                    'subject' => $data['subject'],
        ]);
    }

    public static function dvaspam($data)
    {
        return self::_mail([
                    'to' => $data['email'],
                    'subject' => $data['subject'],
        ]);
    }

    public static function referalpayments($data)
    {
        return self::_mail([
                    'use-smtp' => true,
                    'to' => $data['to'],
                    'subject' => 'Выплаты рефералам'
        ]);
    }

    public static function blogdigest($data)
    {
        return self::_mail([
                    'to' => $data['email'],
                    'subject' => $data['subject'],
                    'data' => $data
        ]);
    }

    public static function blognewsdigest($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['email'],
            'subject' => $data['subject'],
            'data' => $data
        ]);
    }

    public static function designerRemind($data)
    {
        return self::_mail([
            'to' => $data['email'],
            'subject' => $data['subject'],
            'data' => $data
        ]);
    }

    public static function discountWeekends($data)
    {
        return self::_mail([
            'to' => $data['email'],
            'subject' => $data['subject'],
            'data' => $data
        ]);
    }

    public static function discountDesigners($data)
    {
        return self::_mail([
            'to' => $data['email'],
            'subject' => $data['subject'],
            'data' => $data
        ]);
    }

    public static function newComission($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => 'Изменение условий выплаты вознаграждения',
            'data' => $data
        ]);
    }
}
