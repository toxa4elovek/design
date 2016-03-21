<?php

namespace app\extensions\mailers;

class UserMailer extends \li3_mailer\extensions\Mailer {

    public static function verification_mail($user) {
        $subject = 'Активация аккаунта на сайте Godesigner.ru';
        return self::_mail(array('to' => $user->email, 'use-smtp' => true, 'subject' => $subject, 'data' => array('first_name' => $user->first_name, 'token' => $user->token, 'server' => $_SERVER['HTTP_HOST'])));
    }

    public static function hi_mail($user) {
        return self::_mail(array('to' => $user->email, 'use-smtp' => true, 'subject' => 'Добро пожаловать на сайт Godesigner.ru', 'data' => array('first_name' => $user->first_name, 'server' => $_SERVER['HTTP_HOST'])));
    }

    public static function verification_mail_client($user,$posts) {
         return self::_mail(array('to' => $user->email, 'use-smtp' => true, 'subject' => 'Активация аккаунта на сайте Godesigner.ru', 'data' => array('first_name' => $user->first_name, 'token' => $user->token, 'posts' => $posts,'server' => $_SERVER['HTTP_HOST'])));
    }

    public static function forgotpassword_mail($user) {
        return self::_mail(array('to' => $user->email, 'use-smtp' => true, 'subject' => 'Восстановление пароля на сайте Godesigner.ru', 'data' => array('password' => $user->password, 'token' => $user->token, 'server' => $_SERVER['HTTP_HOST'])));
    }

    public static function warn_solution($data) {
        return self::_mail(array('to' => 'devochkina@godesigner.ru', 'reply-to' => $data['user']['email'], 'use-smtp' => true, 'subject' => 'Жалоба на решение', 'data' => array('solution' => $data['solution'], 'text' => $data['text'], 'user' => $data['user'])));
    }

    public static function warn_comment($data) {
        return self::_mail(array('to' => 'devochkina@godesigner.ru', 'reply-to' => $data['user']['email'], 'use-smtp' => true, 'subject' => 'Жалоба на комментарий', 'data' => array('comment' => $data['comment'], 'text' => $data['text'], 'user' => $data['user'])));
    }

    public static function ban($data) {
        return self::_mail(array('to' => $data['user']['email'], 'use-smtp' => true, 'subject' => 'Временный запрет на комментирование', 'data' => array('user' => $data['user'], 'term' => $data['term'])));
    }

    public static function block($data) {
        return self::_mail(array('to' => $data['user']['email'], 'use-smtp' => true, 'subject' => 'Ваш аккаунт заблокирован', 'data' => array('user' => $data['user'])));
    }

    public static function solutiondelete($data) {
        return self::_mail(array('to' => $data['user']['email'], 'use-smtp' => true, 'subject' => 'Удаление решения', 'data' => array('user' => $data['user'], 'solution' => $data['solution'])));
    }

    public static function removecomment($data) {
        return self::_mail(array(
                    'to' => $data['user']['email'],
                    'subject' => 'Удаление комментария',
                    'use-smtp' => true,
                    'data' => array(
                        'user' => $data['user'],
                        'term' => $data['term'],
                        'reason' => $data['reason'],
                        'text' => $data['text'],
                        'explanation' => $data['explanation'],
                        'pitch' => $data['pitch'],
                    ),
        ));
    }

    public static function removesolution($data) {
        return self::_mail(array(
                    'to' => $data['user']['email'],
                    'subject' => 'Удаление решения',
                    'use-smtp' => true,
                    'data' => array(
                        'user' => $data['user'],
                        'term' => $data['term'],
                        'solution_num' => $data['solution_num'],
                        'reason' => $data['reason'],
                        'image' => $data['image'],
                        'explanation' => $data['explanation'],
                        'pitch' => $data['pitch'],
                    ),
        ));
    }

    public static function removeandblock($data) {
        return self::_mail(array(
                    'to' => $data['user']['email'],
                    'subject' => 'Ваш аккаунт заблокирован',
                    'use-smtp' => true,
                    'data' => array(
                        'user' => $data['user'],
                        'reason' => $data['reason'],
                        'text' => $data['text'],
                        'image' => $data['image'],
                        'explanation' => $data['explanation'],
                    ),
        ));
    }

    public static function removeandblock30($data) {
        return self::_mail(array(
            'to' => $data['user']['email'],
            'subject' => 'Ваш аккаунт заблокирован на 30 дней',
            'use-smtp' => true,
            'data' => array(
                'user' => $data['user'],
                'reason' => $data['reason'],
                'solution_num' => $data['solution_num'],
                'text' => $data['text'],
                'image' => $data['image'],
                'explanation' => $data['explanation'],
            ),
        ));
    }

    public static function removeandblockforproject($data) {
        return self::_mail(array(
            'to' => $data['user']['email'],
            'subject' => 'Ваш запрещено участие в проекте',
            'use-smtp' => true,
            'data' => array(
                'user' => $data['user'],
                'reason' => $data['reason'],
                'solution_num' => $data['solution_num'],
                'text' => $data['text'],
                'image' => $data['image'],
                'explanation' => $data['explanation'],
            ),
        ));
    }

}
