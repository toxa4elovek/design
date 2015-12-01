<?php

namespace app\controllers;

class InvitesController extends \lithium\action\Controller {

	public $publicActions = array('index');

	public function index() {
		$success = false;
		$error = '';
		if($this->request->data) {
			include_once 'idna_converter.php';
			$IDN = new \idna_convert();
			$email = $original = $_POST['email'];

			if($email == '') {
				$error = "Email обязателен!";
			}
			$converted = $IDN->encode($email);
			if($email !=  $converted) {
				$email = $converted;
			}
            $login = 'root';
            $password = 'HboJFSaN';
            $database = 'godesigner';

			if(($email) && (filter_var($email, FILTER_VALIDATE_EMAIL))) {
				mysql_connect('localhost', $login, $password);
				mysql_select_db ( $database );
				mysql_set_charset('utf8');

				$result = mysql_query("SELECT `email` FROM `emails` WHERE `email` = '" . mysql_real_escape_string($original) .  "'");
				$row = mysql_fetch_row($result);
				if(!$row) {
					$referrer = $_SESSION['referrer'] ;
					mysql_query('INSERT INTO `emails` (`email`, `created`, `referrer`) VALUES (\'' . mysql_real_escape_string($original) . '\', \' ' . date('Y-m-d H:i:s') . ' \', \'' . mysql_real_escape_string($referrer) . '\')');
				}
				$success = true;
			
			}elseif(!isset($error)) {
				$error = "Email обязателен!";
			}
		}else {
			if(isset($_SERVER['HTTP_REFERER'])) {
				$_SESSION['referrer'] = $_SERVER['HTTP_REFERER'];
			}
		}	
		return $this->render(array('layout' => 'inviteonly', 'data' => array('success' => $success, 'error' => $error)));
	}

	public function to_string() {
		return "Hello World";
	}

	public function to_json() {
		return $this->render(array('json' => 'Hello World'));
	}
}

?>