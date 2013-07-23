<?php
namespace app\extensions\mailers;



class ContactMailer extends \li3_mailer\extensions\Mailer {

    public static function contact_mail($data) {
        return self::_mail(array(
            'to' => 'team@godesigner.ru',
            'subject' => $data['subject'],
            'data' => $data,
            'reply-to' => $data['email'],
        ));
    }

    public static function contact_mail2($data) {
        return self::_mail(array(
            'to' => $data['target'],
            'subject' => $data['subject'],
            'data' => $data,
            'reply-to' => $data['email'],
        ));
    }


}