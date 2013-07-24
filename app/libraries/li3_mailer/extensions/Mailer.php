<?php
namespace li3_mailer\extensions;

use \app\models\Sendemail;
use \Mail;

class Mailer extends \lithium\core\StaticObject {

	protected $_config = array();
	protected static $_classes = array(
		'view' => 'lithium\template\View',
	);
	protected static $_instances = array();

	/**
	 * @var array Google SMTP server access details
	 */
	protected static $smtpGoogle = array(
	    'host' => 'ssl://smtp.gmail.com',
	    'port' => '465',
	    'auth' => true,
	    'username' => '',
	    'password' => '',
	);

	/**
	 * @var array MailJet SMTP server access details
	 */
	protected static $smtpMailJet = array(
	    'host' => 'ssl://in.mailjet.com',
	    'port' => '465',
	    'auth' => true,
	    'username' => 'c6ab29424f78c91ab2e52ed29e43f3c8',
	    'password' => '1407d95eb0992a1d1e84e50e3333d4ee',
	);

	public static function __init() {
		static::config();
	}

	public static function config(array $options = array()) {
		$self = static::_object();
	}

	protected static function _mail(array $options = array()) {
		$backtrace = debug_backtrace();
		$prevMethod = $backtrace[1];
		$defaults['subject'] = 'godesigner.ru';
		//$defaults['to'] = 'nyudmitriy@google.ru';
		$defaults['from'] = 'robot@godesigner.ru';
		$defaults['template'] = $prevMethod['function'];
		$defaults['data'] = array();
		$options += $defaults;
		$from = $options['from'];
		$to = $options['to'];
		$subject = '=?UTF-8?B?'.base64_encode($options['subject']).'?=';
		$hash = $options['data']['hash'] = sha1(uniqid());
		$hash = $options['data']['hash'] = sha1(uniqid());
		$body = static::render(array('data' => $options['data'],'template' => $options['template']));
		$html = $body;
        echo $html; die();
		$crlf = "\n";

		self::logemail(array(
		    'email' => $to,
		    'subject' => $options['subject'],
		    'text' => $html,
		    'hash' => $hash
		));

		if (true == $options['use-smtp']) {
		    require_once "Mail.php";
            $headers = array(
                'Content-type' => 'text/html; charset=utf-8',
                'From'    => 'Go Designer <' . $from . '>',
                'To'      => $to,
                'Subject' => $subject,
                'Reply-To' => (isset($options['reply-to']) && !empty($options['reply-to'])) ? $options['reply-to'] : '',
            );

            $smtp = Mail::factory('smtp', self::$smtpMailJet);

            $mail = $smtp->send($to, $headers, $body);

            if (\PEAR::isError($mail)) {
                echo("<p>" . $mail->getMessage() . "</p>");
                return false;
            } else {
                return true;
            }
		}


        $headerString  = 'MIME-Version: 1.0' . "\r\n";
		$headerString .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headerString .= 'To: ' . $to . "\r\n";
		$headerString .= 'From: Go Designer <' . $from . '>' . "\r\n";
		if (isset($options['reply-to']) && !empty($options['reply-to'])) {
            $headerString .= 'Reply-To: ' . $options['reply-to'] . "\r\n";
		}

		return mail($to, $subject, $html, $headerString);
        die();
        //return mail($to, $subject, $mime->get(), $mime->headers($headers));
		if (\PEAR::isError($mail)) {
			return true;
		} else {
			return false;
		}
	}

	protected static function logemail($data) {
    	$unit = Sendemail::create();
    	$unit->email = $data['email'];
    	$unit->subject = $data['subject'];
    	$unit->text = $data['text'];
    	$unit->created = date('Y-m-d H:i:s');
    	$unit->hash = $data['hash'];
    	return $unit->save();
    }


	protected static function &_object() {
		$class = get_called_class();

		if (!isset(static::$_instances[$class])) {
			static::$_instances[$class] = new $class();
		}
		return static::$_instances[$class];
	}

	public static function render(array $options = array()) {
		$view = new static::$_classes['view'](array(
		    'paths' => array(
		        'template' => '{:library}/views/mails/{:template}.{:type}.php',
		    )
		));
		return $view->render('template', $options['data'], array('template' => $options['template']));
	}



}

?>