<?php
require_once 'config.php';
require_once 'library/Date.php';

class LogController extends Controller {
	
	var $log_form;
	var $startDate;
	var $endDate;

	function LogController() {
		$this->Controller();
		$this->adminOnly();
		
		$this->log_form = array();

		// set common vars
		if (isset($_GET['startYear']) && isset($_GET['endYear'])) {
			$this->startDate = $_GET['startYear'].'-'.$_GET['startMonth'].'-'.$_GET['startDay'];
			$this->endDate =  $_GET['endYear'].'-'.$_GET['endMonth'].'-'.$_GET['endDay'];
		}
		else {
			$this->startDate = date('Y-m-d',strtotime('-7 days'));
			$this->endDate = date('Y-m-d');
		}

		$this->log_form = array();

		$this->smarty->assign('adminNavSelected','log');

		$this->doAction();
	}

	function index() {
		$valid = $this->validateLogForm();

		if ($valid) {
			$this->smarty->assign('startDate',$this->startDate);
			$this->smarty->assign('endDate',$this->endDate);

			$entries = new DO_Log_entries();
			$entries->whereAdd("(DATE(logtime) BETWEEN '{$this->startDate}' AND '{$this->endDate}')");
			if ($this->log_form['search']) {
				$entries->whereAdd("message LIKE '%".addslashes($this->log_form['search'])."%'");
			}
			$entries->orderBy("logtime DESC");
			$entries->find();
			
			$entryArr = array();
			while ($entries->fetch())
				$entryArr[] = clone($entries);
			$this->smarty->assign_by_ref('entries',$entryArr);
		}
		$this->smarty->assign('h1','Log: '.date('F j',strtotime($this->startDate)).' &ndash; '. 
			date('F j, Y',strtotime($this->endDate)));
			
		$this->smarty->display('show_log.html');
	}
	
	function validateLogForm() {
		
		// check for input errors
		$this->log_form =& $_REQUEST;
		$form =& $this->log_form;
				
		$error = array();
		if (strtotime($this->endDate) < strtotime($this->startDate)) {
			$error['bad_dates'] = true;
		}

		$this->smarty->assign_by_ref('logForm',$this->log_form);
		if (!count($error)) {
			return true;
		}
		else {
			$this->smarty->assign_by_ref('error',$error);
			return false;
		}
	}
}

$controller = new LogController();

?>