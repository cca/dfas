<?php

require_once 'config.php';

class AuthenticationController extends Controller {
	
	var $schedule = null;
	
	function AuthenticationController() {
		$this->Controller();
		$this->doAction();
	}
	
	function index() {
	}
	
	function logout() {
		$this->auth->logout();

		if ($this->user->id) {
			$this->logInfo("logged out");
		}
		
		$this->urlAndRedirect();
	}
}

$controller = new AuthenticationController();

?>