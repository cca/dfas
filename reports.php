<?php
require_once 'config.php';
require_once 'library/Date.php';

class ReportsController extends Controller {
	
	var $reports_form;
	var $startDate;
	var $endDate;
	var $scheduleOptions;
	var $scheduleIds;
	var $scheduleIdString;

	function ReportsController() {
		$this->Controller();
		$this->adminOnly();
		
		$this->reports_form = array();

		$schedules = new DO_Schedules();
		$schedules->orderBy("name ASC");
		$schedules->find();
		while ($schedules->fetch())
			$this->scheduleOptions[$schedules->id] = $schedules->name;
		$this->smarty->assign_by_ref('scheduleOptions',$this->scheduleOptions);

		$this->smarty->assign('adminNavSelected','reports');
		$this->doAction();
	}

	function index() {
		$valid = $this->validateReportsForm();

		if ($valid) {
			// fetch reservations by department during this time period
			$deptReservations = new DO_Reservations();
			$q = "SELECT COUNT(reservations.id) as reservations, ".
				"COUNT(DISTINCT users.id) as users, users.department as department ".
				"FROM reservations, users ".
				"WHERE date BETWEEN '{$this->startDate}' AND '{$this->endDate}' ".
				"AND users.id = reservations.user_id ".
				"AND reservations.schedule_id IN (".$this->scheduleIdString.") ".
				"GROUP BY users.department ".
				"ORDER BY reservations DESC";
			$deptReservations->query($q);
			$drArr = $deptReservations->fetchArray();

			// fetch total reservation count during this time period
			$rcRes = new DO_Reservations();
			$q = "SELECT COUNT(reservations.id) as rc ".
				"FROM reservations ".
				"WHERE date BETWEEN '{$this->startDate}' AND '{$this->endDate}'".
				"AND reservations.schedule_id IN (".$this->scheduleIdString.") ";
			$rcRes->query($q);
			$rcRes->fetch();
			$reservationCount = $rcRes->rc;

			// fetch total user count during this time period
			$ucRes = new DO_Reservations();
			$q = "SELECT COUNT(DISTINCT users.id) as uc ".
				"FROM reservations,users ".
				"WHERE date BETWEEN '{$this->startDate}' AND '{$this->endDate}' ".
				"AND users.id = reservations.user_id ".
				"AND reservations.schedule_id IN (".$this->scheduleIdString.") ";
			$ucRes->query($q);
			$ucRes->fetch();
			$userCount = $ucRes->uc;
			
			// fetch total non-dept reservation count during this time period
			$nonDeptReservations = new DO_Reservations();
			$q = "SELECT COUNT(reservations.id) as rc ".
				"FROM reservations ".
				"WHERE date BETWEEN '{$this->startDate}' AND '{$this->endDate}' ".
				"AND reservations.user_id = 0 ".
				"AND reservations.schedule_id IN (".$this->scheduleIdString.") ";
			$nonDeptReservations->query($q);
			$nonDeptReservations->fetch();
			$nonDeptCount = $nonDeptReservations->rc;

			$departments =& DO_Users::getAllDepartments();

			$title = "<b>Schedules:</b> ";
			$scheduleList = array();
			foreach ($this->scheduleIds as $id)
				$scheduleList[] = $this->scheduleOptions[$id];
			$title .= (count($scheduleList) ? implode(', ',$scheduleList) : 'none');
			$title .= "<br />\n<b>Dates:</b> ".date('F j, Y',strtotime($this->startDate)).
				' - '.date('F j, Y',strtotime($this->endDate));
			$this->smarty->assign('title',$title);

			$this->smarty->assign('startDate',$this->startDate);
			$this->smarty->assign('endDate',$this->endDate);
			$this->smarty->assign('scheduleIds',$this->scheduleIds);
			$this->smarty->assign_by_ref('deptReservations',$drArr);
			$this->smarty->assign('reservationCount',$reservationCount);
			$this->smarty->assign('userCount',$userCount);
			$this->smarty->assign('nonDeptCount',$nonDeptCount);
			$this->smarty->assign_by_ref('departments',$departments);
		}
		else {
			$this->passData('reports_form',$this->reports_form);
			$this->redirect('reports.php');
		}
		
		$this->smarty->assign('h1','Reports');
		$this->smarty->display('show_report.html');
	}
	
	function validateReportsForm() {
		// check for input errors
		$this->reports_form =& $_REQUEST;
		$form =& $this->reports_form;

		$this->scheduleIds = (isset($form['schedules']) ? array_keys($form['schedules']) : array());
		$this->scheduleIdString = implode(',',$this->scheduleIds);

		// set common vars
		if (isset($form['startYear']) && isset($form['endYear'])) {
			$this->startDate = $form['startYear'].'-'.$form['startMonth'].'-'.$form['startDay'];
			$this->endDate =  $form['endYear'].'-'.$form['endMonth'].'-'.$form['endDay'];
		}
		else {
			$this->startDate = date('Y-m-d',strtotime('-4 months'));
			$this->endDate = date('Y-m-d');
		}
		
		if (strtotime($this->endDate) < strtotime($this->startDate)) {
			$this->passError('bad_dates','The "from" date must be before the "to" date.');
		}

		return !$this->hasErrors();
	}
}

$controller = new ReportsController();

?>