<?php

class Controller {
	var $smarty;	// the Smarty instance
	var $user;		// the logged in user (if any)
	var $log;			// the logger instance
	var $action;	// the action being called
	var $actionMap;	// map of action strings to method names
	var $passErrors;	// holds errors to pass between pages
	var $passData;
	
	function Controller() {
		// initialize the templating engine
		$this->smarty = new Smarty();
		$this->smarty->template_dir = SMARTY_PATH.'templates';
		$this->smarty->compile_dir = SMARTY_PATH.'templates_c';
		$this->smarty->plugins_dir[] = SMARTY_PATH.'plugins';

		// initialize our logging utility
		$this->log =& Log::singleton('sql','log_entries','',array('dsn' => DSN));

		// initialize our Auth instance
		/*
		$this->auth = new Auth("DB",
			array('dsn' => DSN,
				'table' => 'users',
				'usernamecol' => 'username',
				'passwordcol' => 'password'),
			'',false);
		*/
		//$this->auth = new Auth("POP3",array('host' => 'mail.cca.edu'),'',false);
		$this->auth = new Auth("LDAP", array(
      'host' => 'directory.cca.edu',
      'port' => '389',
      'version' => 3,
      'userattr' => 'uid',
      'binddn' => 'uid=email,ou=Administrators,ou=TopologyManagement,o=NetscapeRoot',
      'bindpw' => 'Fiatdiwyds8'
  	),'',false);
		$this->auth->setIdle(86400);
		$this->auth->start();

		$this->user = new DO_Users();
		if ($this->auth->getAuth()) {
			$this->user->whereAdd("username = '".$this->auth->getUsername()."'");
			$this->user->find(true);

			// need to check for account in db, as well as whether account is active
			if ($_REQUEST['do_login']) {
				if ($this->user->id) {
					if (!$this->user->active) {
						$this->logNotice("failed login (account not active)");
						$this->auth->logout();
						$this->passError('inactive_account','That account has been marked inactive.');
					}
					else {
						$this->logInfo("logged in");
					}
				}
				else {	// auth passed, but no account in db
					$this->logNotice("{$_REQUEST['username']}: ".
						"failed login (auth ok but account not in db)");
					$this->auth->logout();
					$this->passError('no_account',"That username does not have an account");
				}
				$this->urlAndRedirect();
			}
			
			$this->loggedIn = true;
		}
		else {
			if (isset($_REQUEST['do_login'])) {
				$this->logNotice("{$_REQUEST['username']}: failed login");
				$this->passError('failed_login','Bad username/password combination');
				$this->urlAndRedirect();
			}
			$this->loggedIn = false;
		}
		$this->smarty->assign('loggedIn',$this->loggedIn);
		$this->smarty->assign_by_ref('user',$this->user);

		$this->action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index');

    if (!isset($_SESSION['passErrors'])) {
      $_SESSION['passErrors'] = array();
    }
		$this->passErrors = $_SESSION['passErrors']; // copy previous errors
		$_SESSION['passErrors'] = array();	// clear previous errors from session data
		$this->smarty->assign_by_ref('passErrors',$this->passErrors);

    if (!isset($_SESSION['passMessages'])) {
      $_SESSION['passMessages'] = array();
    }
		$this->passMessages = $_SESSION['passMessages'];
		$_SESSION['passMessages'] = array();
		$this->smarty->assign_by_ref('passMessages',$this->passMessages);

    if (!isset($_SESSION['passData'])) {
      $_SESSION['passData'] = array();
    }
		$this->passData = $_SESSION['passData'];
		$_SESSION['passData'] = array();
		$this->smarty->assign_by_ref('passData',$this->passData);

		$this->smarty->assign('action',$this->action);

		if (!isset($this->adminNavSelected)) {
		  $this->adminNavSelected = '';
		}
		$this->smarty->assign('adminNavSelected',$this->adminNavSelected);
		
		$this->smarty->assign_by_ref('ctl',$this);
	}
	
	function doAction() {
		$methodName = $this->actionMap[$this->action];
		if (!$methodName) {
			$methodName = preg_replace('@_(\w)@','$1',$this->action);
			if (!method_exists($this,$methodName))
				$methodName = $this->actionMap['default'];
		}
		$this->$methodName();
	}
	
	function redirect($location) {
		header("Location: $location");
		exit();
	}
	
	// convenience method to combine url() and redirect()
	function urlAndRedirect($controller = false, $action = false, $params = false) {
		$this->redirect($this->url($controller,$action,$params));
	}

	function redirectToReferrer() {
		$referrer = $_SERVER['HTTP_REFERER'];
		$this->redirect($referrer);
	}

	function url($controller = false, $action = false, $params = false) {
		if (!$controller) return SITE_URL.'/';

		$url = SITE_URL."/{$controller}.php";
		if ($action)
			$url .= "?action={$action}";
		if ($params === false)
			return $url;
		if (!is_array($params))
			$url .= "&id={$params}";
		else {
			foreach ($params as $k => $v)
				$url .= "&{$k}={$v}";
		}
		return $url;
	}

	function markdownInputFilter($s) {
		$s = preg_replace("/(\s*?)\n/","  \n",$s);
		$s = trim($s);
		return $s;
	}
	
	function logInfo($msg) {
		$this->writeToLog('info',$msg);
	}
	function logNotice($msg) {
		$this->writeToLog('notice',$msg);
	}
	function logWarning($msg) {
		$this->writeToLog('warning',$msg);
	}
	function logErr($msg) {
		$this->writeToLog('err',$msg);
	}
	function writeToLog($type,$msg) {
		$actionUser = ($this->user->id ? $this->user->username : '(unknown)');
		$txt = "{$actionUser}: {$msg}";
		switch($type) {
			case 'info':
				$this->log->info($txt);
				break;
			case 'notice':
				$this->log->notice($txt);
				break;
			case 'warning':
				$this->log->warning($txt);
				break;
			case 'err':
				$this->log->err($txt);
				break;
		}
	}
	
	// used to pass errors between pages (e.g. form validation)
	function passError($name,$value = true) {
		$_SESSION['passErrors'][$name] = $value;
	}
	// NOTE: this is for checking whether the form that was just submitted contains errors
	//       i.e. used to determine whether to redirect, not to check for errors from a previous page
	function hasErrors() {
		return count($_SESSION['passErrors']);
	}
	
	function passMessage($name,$value = true) {
		$_SESSION['passMessages'][$name] = $value;
	}

	function passData($name,$value = true) {
		$_SESSION['passData'][$name] = $value;
	}
	
	function adminOnly() {
		if (!$this->user->isAdmin() || !$this->user->active) {
			$this->logNotice("unauthorized access attempt");
			$this->passError('no_access','You do not have access to the requested page.');
			$this->urlAndRedirect();
		}
	}
	
	function requireActiveUser() {
		if (!$this->user->id) {
			$this->logNotice("unauthorized access attempt");
			$this->passError('no_access','Access denied.');
			$this->urlAndRedirect();
		}
		elseif (!$this->user->active) {
			$this->logNotice("unauthorized access attempt");
			$this->passError('inactive_account','Your account is not active.');
			$this->urlAndRedirect();
		}
	}
}

?>