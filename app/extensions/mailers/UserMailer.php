<?php

namespace app\extensions\mailers;

use app\models\Promocode;

class UserMailer extends \li3_mailer\extensions\Mailer
{
    public static function verification_mail($user)
    {
        $subject = 'Активация аккаунта на сайте Godesigner.ru';
        return self::_mail(['to' => $user->email, 'use-smtp' => true, 'subject' => $subject, 'data' => ['first_name' => $user->first_name, 'token' => $user->token, 'server' => $_SERVER['HTTP_HOST']]]);
    }

    public static function hi_mail($user)
    {
        return self::_mail(['to' => $user->email, 'use-smtp' => true, 'subject' => 'Добро пожаловать на сайт Godesigner.ru', 'data' => ['first_name' => $user->first_name, 'server' => $_SERVER['HTTP_HOST']]]);
    }

    public static function verification_mail_client($user, $posts)
    {
        return self::_mail(['to' => $user->email, 'use-smtp' => true, 'subject' => 'Активация аккаунта на сайте Godesigner.ru', 'data' => ['first_name' => $user->first_name, 'token' => $user->token, 'posts' => $posts, 'server' => $_SERVER['HTTP_HOST']]]);
    }

    public static function forgotpassword_mail($user)
    {
        return self::_mail(['to' => $user->email, 'use-smtp' => true, 'subject' => 'Восстановление пароля на сайте Godesigner.ru', 'data' => ['password' => $user->password, 'token' => $user->token, 'server' => $_SERVER['HTTP_HOST']]]);
    }

    public static function warn_solution($data)
    {
        return self::_mail(['to' => 'team@godesigner.ru', 'reply-to' => $data['user']['email'], 'use-smtp' => true, 'subject' => 'Жалоба на решение', 'data' => ['solution' => $data['solution'], 'text' => $data['text'], 'user' => $data['user']]]);
    }

    public static function warn_comment($data)
    {
        return self::_mail(['to' => 'team@godesigner.ru', 'reply-to' => $data['user']['email'], 'use-smtp' => true, 'subject' => 'Жалоба на комментарий', 'data' => ['comment' => $data['comment'], 'text' => $data['text'], 'user' => $data['user']]]);
    }

    public static function ban($data)
    {
        return self::_mail(['to' => $data['user']['email'], 'use-smtp' => true, 'subject' => 'Временный запрет на комментирование', 'data' => ['user' => $data['user'], 'term' => $data['term']]]);
    }

    public static function block($data)
    {
        return self::_mail(['to' => $data['user']['email'], 'use-smtp' => true, 'subject' => 'Ваш аккаунт заблокирован', 'data' => ['user' => $data['user']]]);
    }

    public static function solutiondelete($data)
    {
        return self::_mail(['to' => $data['user']['email'], 'use-smtp' => true, 'subject' => 'Удаление решения', 'data' => ['user' => $data['user'], 'solution' => $data['solution']]]);
    }

    public static function removecomment($data)
    {
        return self::_mail([
                    'to' => $data['user']['email'],
                    'subject' => 'Удаление комментария',
                    'use-smtp' => true,
                    'data' => [
                        'user' => $data['user'],
                        'term' => $data['term'],
                        'reason' => $data['reason'],
                        'text' => $data['text'],
                        'explanation' => $data['explanation'],
                        'pitch' => $data['pitch'],
                    ],
        ]);
    }

    public static function removesolution($data)
    {
        return self::_mail([
                    'to' => $data['user']['email'],
                    'subject' => 'Удаление решения',
                    'use-smtp' => true,
                    'data' => [
                        'user' => $data['user'],
                        'term' => $data['term'],
                        'solution_num' => $data['solution_num'],
                        'reason' => $data['reason'],
                        'image' => $data['image'],
                        'explanation' => $data['explanation'],
                        'pitch' => $data['pitch'],
                    ],
        ]);
    }

    public static function removeandblock($data)
    {
        return self::_mail([
                    'to' => $data['user']['email'],
                    'subject' => 'Ваш аккаунт заблокирован',
                    'use-smtp' => true,
                    'data' => [
                        'user' => $data['user'],
                        'pitch' => $data['pitch'],
                        'reason' => $data['reason'],
                        'text' => $data['text'],
                        'image' => $data['image'],
                        'explanation' => $data['explanation'],
                    ],
        ]);
    }

    public static function removeandblock30($data)
    {
        return self::_mail([
            'to' => $data['user']['email'],
            'subject' => 'Ваш аккаунт заблокирован на 30 дней',
            'use-smtp' => true,
            'data' => [
                'user' => $data['user'],
                'pitch' => $data['pitch'],
                'reason' => $data['reason'],
                'solution_num' => $data['solution_num'],
                'text' => $data['text'],
                'image' => $data['image'],
                'explanation' => $data['explanation'],
            ],
        ]);
    }

    public static function removeandblockforproject($data)
    {
        return self::_mail([
            'to' => $data['user']['email'],
            'subject' => 'Ваш запрещено участие в проекте',
            'use-smtp' => true,
            'data' => [
                'user' => $data['user'],
                'pitch' => $data['pitch'],
                'reason' => $data['reason'],
                'solution_num' => $data['solution_num'],
                'text' => $data['text'],
                'image' => $data['image'],
                'explanation' => $data['explanation'],
            ],
        ]);
    }

    /**
     * Метод отправляет утешительное письмо заказчку при низкой оценки сайта
     */
    public static function sendEmailAfterGrade($gradeRecord, $user)
    {
        if (((int) $gradeRecord->site_rating <= 3) && ($gradeRecord->text === '')) {
            if ((int) $gradeRecord->work_rating > 3) {
                $promocode = Promocode::create([
                    'code' => Promocode::generateToken(),
                    'type' => 'custom_discount',
                    'starts' => date(MYSQL_DATETIME_FORMAT),
                    'expires' => date(MYSQL_DATETIME_FORMAT, time() + (MONTH)),
                    'user_id' => $user->id,
                    'data' => 20
                ]);
                $promocode->save();
                return self::_mail([
                    'use-smtp' => true,
                    'to' => $user->email,
                    'subject' => 'Спасибо за отзыв: как нам стать лучше?',
                    'data' => compact('user', 'gradeRecord', 'promocode')
                ]);
            } elseif ((int) $gradeRecord->work_rating === 3) {
                $promocode = Promocode::create([
                    'code' => Promocode::generateToken(),
                    'type' => 'custom_discount',
                    'starts' => date(MYSQL_DATETIME_FORMAT),
                    'expires' => date(MYSQL_DATETIME_FORMAT, time() + (MONTH)),
                    'user_id' => $user->id,
                    'data' => 25
                ]);
                $promocode->save();
                return self::_mail([
                    'use-smtp' => true,
                    'to' => $user->email,
                    'subject' => 'Благодарность за отзыв о GoDesigner: 25% скидка на сервисный сбор',
                    'data' => compact('user', 'gradeRecord', 'promocode')
                ]);
            } else {
                $promocode = Promocode::create([
                    'code' => Promocode::generateToken(),
                    'type' => 'custom_discount',
                    'starts' => date(MYSQL_DATETIME_FORMAT),
                    'expires' => date(MYSQL_DATETIME_FORMAT, time() + (MONTH)),
                    'user_id' => $user->id,
                    'data' => 50
                ]);
                $promocode->save();
                return self::_mail([
                    'use-smtp' => true,
                    'to' => $user->email,
                    'subject' => 'Благодарность за отзыв о GoDesigner: 50% скидка на сервисный сбор',
                    'data' => compact('user', 'gradeRecord', 'promocode')
                ]);
            }
        }
    }

    /**
     * Метод отправляет приглашение в проект, требуется
     * $user и $pitch
     *
     * @param $data
     * @return bool|mixed|string
     */
    public static function newInvite($data)
    {
        return self::_mail([
            'to' => $data['user']->email,
            'subject' => 'Вас пригласили к участию в проекте!',
            'data' => $data
        ]);
    }

    /**
     * Метод отправляет уведомление в проект 1на1, требуется
     * $user и $pitch
     *
     * @param $data
     * @return bool|mixed|string
     */
    public static function new1on1Project($data)
    {
        return self::_mail([
            'to' => $data['user']->email,
            'subject' => 'Приглашение в проект',
            'data' => $data
        ]);
    }
}
