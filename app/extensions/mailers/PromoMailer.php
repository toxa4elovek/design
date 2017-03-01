<?php
namespace app\extensions\mailers;

use DrewM\MailChimp\MailChimp;

class PromoMailer extends \li3_mailer\extensions\Mailer
{

    /**
     * Метод отсылает письмо с новым промокодом автору проекта
     *
     * @param $data
     * @return bool
     */
    public static function sendPromoCodeFollowUp($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => $data['user']->email,
            'subject' => '1000 руб. от GoDesigner!',
            'data' => $data,
            'reply-to' => 'm.elenevskaya@godesigner.ru',
        ]);
    }

    /**
     * Метод отсылается письмо админу о прибыльным проекте
     *
     * @param $data
     * @return bool
     */
    public static function sendGoodProfitFollowUp($data)
    {
        return self::_mail([
            'use-smtp' => true,
            'to' => 'm.elenevskaya@godesigner.ru',
            'subject' => 'Неоплаченный проект',
            'data' => $data,
        ]);
    }

    public static function sendStep3MarketingSalesFunnelEmail($data)
    {
        $mailchimp = new MailChimp('02887cbd428f1ae0b9849eb586382ea7-us13');
        $mailchimp->post('automations/20e72f4836/emails/833ded9402/queue', [
            'email_address' => $data['email'],
        ]);
        /*return self::_mail([
            'use-smtp' => true,
            'to' => $data['email'],
            'subject' => '20% скидка!',
            'headers' => [
                'X-MC-Template' => '20% скидка',
                'X-MC-MergeVars' => json_encode([
                    'FNAME' => $data['first_name'],
                    'LIST:COMPANY' => 'OOO CROWD MEDIA',
                    'UPDATE_PROFILE' => 'https://godesigner.ru/users/profile'
                ]),
            ],
            'data' => $data,
        ]);*/
        return true;
    }
}
