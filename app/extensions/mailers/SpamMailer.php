<?php
namespace app\extensions\mailers;



class SpamMailer extends \li3_mailer\extensions\Mailer {

    public static function newpitch($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Новый питч!',
            'data' => $data
        ));
    }

    public static function promocode($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Подарочный промокод',
            'data' => $data
        ));
    }

    public static function comeback($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Питч требует вашего внимания',
            'data' => $data
        ));
    }

    public static function newclientpitch($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Новый питч!',
            'data' => $data
        ));
    }

    public static function newbriefedpitch($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Новый питч c опцией "заполнить бриф"!',
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
            'subject' => 'Новые питчи!',
            'data' => $data
        ));
    }

    public static function choosewinner($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Срок анонсирования победителя',
            'data' => $data
        ));
    }

    public static function step2($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Доработка макетов',
            'data' => $data
        ));
    }

    public static function step3($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Получение исходников',
            'data' => $data
        ));
    }

    public static function step4($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Завершение рабочего процесса, рейтинг.',
            'data' => $data
        ));
    }

    public static function newcomment($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Новый комментарий к вашему решению!',
            'data' => $data
        ));
    }

    public static function newsolution($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Добавлено новое решение!',
            'data' => $data
        ));
    }

    public static function newwincomment($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Обновление завершающего процесса!',
            'data' => $data
        ));
    }

    public static function winstep($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Обновление завершающего процесса!',
            'data' => $data
        ));
    }

    public static function newpersonalcomment($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Вам оставлен новый комментарий!',
            'data' => $data
        ));
    }

    public static function newadmincomment($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Go Designer оставил комментарий',
            'data' => $data
        ));
    }

    public static function newadminnotification($data) {
        return self::_mail(array(
            'to' => $data['admin'],
            'subject' => 'Оставлен комментарий после завершения питча',
            'data' => $data
        ));
    }

    public static function firstsolution($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Добавлено решение',
            'data' => $data
        ));
    }

    public static function solutionselected($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Ваше решение стало победителем!',
            'data' => $data
        ));
    }

    public static function expertselected($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Экспертное мнение',
            'data' => $data
        ));
    }

    public static function expertneedpostcomment($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => 'Ожидается ваше экспертное мнение',
            'data' => $data
        ));
    }

    public static function openletter($data) {
        return self::_mail(array(
            'to' => $data['user']->email,
            'subject' => '5 рекомендаций для улучшения вашего брифа',
            'data' => $data,
            'reply-to' => 'devochkina@godesigner.ru',
            'use-smtp' => true,
        ));
    }

}