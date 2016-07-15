<?php
require_once 'phpmailer/class.phpmailer.php';

class SchedulerMailer extends PHPMailer {

	function SchedulerMailer() {
		$this->Host = EMAIL_HOST;
		$this->Hostname = EMAIL_HOST;
		$this->SetLanguage('en',SITE_PATH.'/library/phpmailer/language/');
		if (EMAIL_PORT)
			$this->Port = EMAIL_PORT;
		if (EMAIL_PASSWORD) {
			$this->IsSMTP();
			$this->SMTPAuth = true;     // turn on SMTP authentication
			$this->Username = EMAIL_USER;  // SMTP username
			$this->Password = EMAIL_PASSWORD; // SMTP password
		}
		$this->From = EMAIL_FROM;
		$this->FromName = EMAIL_FROM_NAME;
	}

}

?>