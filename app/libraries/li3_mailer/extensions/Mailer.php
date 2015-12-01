<?php
namespace li3_mailer\extensions;

use \app\models\Sendemail;
use \Mail;
use \Mail_mime;
use lithium\core\Environment;
use lithium\util\Inflector;

class Mailer extends \lithium\core\StaticObject {

	protected $_config = array();
	protected static $_classes = array(
		'view' => 'lithium\template\View',
	);
	protected static $_instances = array();

    protected static $saveMailPath = '/resources/emails/';

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

    /**
     * @var array Mandrill SMTP server access details
     */
    protected static $smtpMandrill = array(
        'host' => 'ssl://smtp.mandrillapp.com',
        'port' => '465',
        'auth' => true,
        'username' => 'nyudmitriy@godesigner.ru',
        'password' => 'hqzTB-srJK45y2tsSl1VaQ',
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
		$defaults['from'] = 'robot@godesigner.ru';
		$defaults['template'] = $prevMethod['function'];
		$defaults['data'] = array();
		$options += $defaults;
		$from = $options['from'];
		$to = $options['to'];
		$subject = '=?UTF-8?B?'.base64_encode($options['subject']).'?=';
		$hash = $options['data']['hash'] = sha1(uniqid());
		$body = static::render(array('data' => $options['data'],'template' => $options['template']));
		$html = $body;
		self::logemail(array(
		    'email' => $to,
		    'subject' => $options['subject'],
		    'text' => $html,
		    'hash' => $hash
		));
        if(Environment::is('production')) {
            require_once 'Mail.php';
            require_once 'Mail/mime.php';
            $headers = array(
                'From'    => 'Go Designer <' . $from . '>',
                'To'      => $to,
                'Subject' => $subject,
                'Reply-To' => (isset($options['reply-to']) && !empty($options['reply-to'])) ? $options['reply-to'] : '',
            );
            $message = new Mail_mime();
            $message->setHTMLBody($html);
            if (isset($options['data']['files']) && count($options['data']['files']) > 0) {
                foreach ($options['data']['files'] as $file) {
                    $message->addAttachment($file);
                }
            }
            $mimeparams = array(
                'text_charset' => "UTF-8",
                'html_charset' => "UTF-8",
            );
            $body = $message->get($mimeparams);
            $headers = $message->headers($headers);

            if (isset($options['use-smtp']) && true == $options['use-smtp']) {
                $mail = Mail::factory('smtp', self::$smtpMandrill);
                //$mail = Mail::factory("mail");
            } else {
                $mail = Mail::factory("mail");
            }
            $mail->send($to, $headers, $body);

            if (\PEAR::isError($mail)) {
                return false;
            } else {
                return true;
            }
        }else {
            $fullpath = LITHIUM_APP_PATH . '/resources/tmp/emails/' . $options['template'] . '_' . time() . '.html';
            file_put_contents($fullpath, $html);
            return $html;
        }
	}

	protected static function logemail($data) {
		if(!empty($data['email'])) {
			$unit = Sendemail::create();
			$unit->email = $data['email'];
			$unit->subject = $data['subject'];
			$unit->text = $data['text'];
			$unit->created = date('Y-m-d H:i:s');
			$unit->hash = $data['hash'];
			return $unit->save();
		}
		return false;
    }


	protected static function &_object() {
		$class = get_called_class();

		if (!isset(static::$_instances[$class])) {
			static::$_instances[$class] = new $class();
		}
		return static::$_instances[$class];
	}

	public static function render(array $options = array()) {
        $directory = self::__getMailerDirectory();
		$view = new static::$_classes['view'](array(
		    'paths' => array(
		        'template' => '{:library}/views/mails/' . $directory . '/{:template}.{:type}.php',
		    )
		));
		return $view->render('template', $options['data'], array('template' => $options['template']));
	}

    private static function __getMailerDirectory() {
        $fullClassPath = get_called_class();
        $explodedArray = explode('\\', $fullClassPath);
        $className = end($explodedArray);
        preg_match('/(.*)Mailer/', $className, $matches);
        return Inflector::underscore(end($matches));
    }
}
?>