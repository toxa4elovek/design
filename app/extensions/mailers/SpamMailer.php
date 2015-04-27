<?php

namespace app\extensions\mailers;

class SpamMailer extends \li3_mailer\extensions\Mailer {

    public static function newpitch($data) {
        return self::_mail(array(
                    'to' => $data['user']->email,
                    'subject' => 'Новый проект!',
                    'data' => $data
        ));
    }

    public static function promocode($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Подарочный промокод',
                    'data' => $data
        ));
    }

    public static function comeback($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Проект требует вашего внимания',
                    'data' => $data
        ));
    }

    public static function newclientpitch($data) {
        return self::_mail(array(
                    'to' => $data['user']->email,
                    'subject' => 'Новый проект!',
                    'data' => $data
        ));
    }

    public static function sendclientexpertspeaking($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Добавлено экспертное мнение',
                    'data' => $data,
        ));
    }

    public static function newbriefedpitch($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Заполнить бриф: ' . $data['pitch']->{'phone-brief'},
                    'data' => $data
        ));
    }

    public static function newmoderatedpitch($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Новый проект на модерацию!',
                    'data' => $data
        ));
    }

    public static function newaddon($data) {
        $addonsCount = 0;
        $addonsList = '<br />';
        if ($data['addon']->experts == 1) {
            $addonsCount++;
            $addonsList .= 'экспертное мнение<br />';
        }
        if ($data['addon']->prolong == 1) {
            $addonsCount++;
            $addonsList .= 'продление<br />';
        }
        if ($data['addon']->brief == 1) {
            $addonsCount++;
            $addonsList .= 'заполнение брифа<br />';
        }
        if ($data['addon']->pinned == 1) {
            $addonsCount++;
            $addonsList .= 'прокачать бриф<br />';
        }
        if ($data['addon']->guaranteed == 1) {
            $addonsCount++;
            $addonsList .= 'гарантированный проект<br />';
        }
        $stringSubject = 'Новая доп. опция!';
        $data['stringAddons'] = 'КУПЛЕНА ДОПОЛНИТЕЛЬНАЯ ОПЦИЯ:' . $addonsList;
        if ($addonsCount > 1) {
            $stringSubject = 'Новые доп. опции!';
            $data['stringAddons'] = 'КУПЛЕНЫ ДОПОЛНИТЕЛЬНЫЕ ОПЦИИ:' . $addonsList;
        }

        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => $stringSubject,
                    'data' => $data
        ));
    }

    public static function newaddonbrief($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Новая доп. опция "заполнить бриф"!',
                    'data' => $data
        ));
    }

    public static function dailydigest($data) {
        return self::_mail(array(
                    'to' => $data['user']->email,
                    'subject' => 'Добавлено решение или комментарий',
                    'data' => $data
        ));
    }

    public static function dailypitch($data) {
        return self::_mail(array(
                    'to' => $data['user']->email,
                    'subject' => 'Новые проекты!',
                    'data' => $data
        ));
    }

    public static function choosewinner($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Срок анонсирования победителя',
                    'data' => $data
        ));
    }

    public static function step2($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Доработка макетов',
                    'data' => $data
        ));
    }

    public static function step3($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Получение исходников',
                    'data' => $data
        ));
    }

    public static function step4($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Завершение рабочего процесса, рейтинг.',
                    'data' => $data
        ));
    }

    public static function newcomment($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Новый комментарий к вашему решению!',
                    'data' => $data
        ));
    }

    public static function newwincomment($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Обновление завершающего процесса!',
                    'data' => $data
        ));
    }

    public static function winstep($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Обновление завершающего процесса!',
                    'data' => $data
        ));
    }

    public static function newadminnotification($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['admin'],
                    'subject' => 'Оставлен комментарий после завершения проекта',
                    'data' => $data
        ));
    }

    public static function firstsolution($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Добавлено решение',
                    'data' => $data
        ));
    }

    public static function expertselected($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Экспертное мнение',
                    'data' => $data
        ));
    }

    public static function expertneedpostcomment($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Ожидается ваше экспертное мнение',
                    'data' => $data
        ));
    }

    public static function expertreminder($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Ожидается ваше экспертное мнение',
                    'data' => $data
        ));
    }

    public static function openletter($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => '5 рекомендаций для улучшения вашего брифа',
                    'data' => $data,
                    'reply-to' => 'devochkina@godesigner.ru',
        ));
    }

    public static function finishreports($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Закрывающие документы для вашего проекта',
                    'data' => $data,
                    'reply-to' => 'va@godesigner.ru',
        ));
    }

    public static function duration($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Хотите продлить проект и увеличить бюджет?',
                    'data' => $data,
                    'reply-to' => 'team@godesigner.ru',
        ));
    }

    public static function expertaddon($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Нужна помощь в выборе?',
                    'data' => $data,
                    'reply-to' => 'team@godesigner.ru',
        ));
    }

    public static function briefaddon($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['user']->email,
                    'subject' => 'Появились сомнения в правильности заполнения брифа?',
                    'data' => $data,
                    'reply-to' => 'devochkina@godesigner.ru',
        ));
    }

    public static function referalspam($data) {
        return self::_mail(array(
                    'to' => $data['email'],
                    'subject' => $data['subject'],
        ));
    }

    public static function dvaspam($data) {
        return self::_mail(array(
                    'to' => $data['email'],
                    'subject' => $data['subject'],
        ));
    }

    public static function referalpayments($data) {
        return self::_mail(array(
                    'use-smtp' => true,
                    'to' => $data['to'],
                    'subject' => 'Выплаты рефералам'
        ));
    }

    public static function blogdigest($data) {
        return self::_mail(array(
                    'to' => $data['email'],
                    'subject' => $data['subject'],
                    'data' => $data
        ));
    }

    public static function designerRemind($data) {
        return self::_mail(array(
                    'to' => $data['email'],
                    'subject' => $data['subject'],
                    'data' => $data
        ));
    }

    public static function discountWeekends($data) {
        return self::_mail(array(
                    'to' => $data['email'],
                    'subject' => $data['subject'],
                    'data' => $data
        ));
    }

    public static function discountDesigners($data) {
        return self::_mail(array(
                    'to' => $data['email'],
                    'subject' => $data['subject'],
                    'data' => $data
        ));
    }

}
