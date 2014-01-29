<?php

namespace app\controllers;

use app\controllers\AppController;
use \Mail;
use \Mail_mime;

class MailsController extends AppController {

    public $publicActions = array('index');

    public function index() {
        require_once 'Mail.php';
        require_once 'Mail/mime.php';
        $from = 'robot@godesigner.ru';
        $to = 'nyudmitriy@gmail.com';
        $subject = 'test';
        $file = '/var/godesigner/webroot/dogovor.pdf';
        $headers = array(
            'From'    => 'Go Designer <' . $from . '>',
            'To'      => $to,
            'Subject' => $subject,
            'Reply-To' => (isset($options['reply-to']) && !empty($options['reply-to'])) ? $options['reply-to'] : '',
        );
        $html = 'test';
        $message = new Mail_mime();
        $message->setHTMLBody($html);

        $result = $message->addAttachment($file);
        var_dump($result);
        $mimeparams = array(
            'text_charset' => "UTF-8",
            'html_charset' => "UTF-8",
        );
        $body = $message->get($mimeparams);
        $headers = $message->headers($headers);

        //if (isset($options['use-smpt']) && true == $options['use-smtp']) {
        //    $mail = Mail::factory('smtp', self::$smtpMailJet);
        //} else {
            $mail = Mail::factory("mail");
        //}
        $mail->send($to, $headers, $body);

        if (\PEAR::isError($mail)) {
            echo("<p>" . $mail->getMessage() . "</p>");
        } else {
            echo 'Message sent';
        }
        die();
    }


}
