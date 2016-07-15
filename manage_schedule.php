<?php
require_once 'config.php';
require_once 'library/Date.php';

class ManageScheduleController extends Controller {
	
	var $schedule;
	var $schedule_form;
	var $defaults_form;
	var $month;
	var $year;
	var $daysInMonth;
	var $firstDate;
	var $firstDay;

	function ManageScheduleController() {
		$this->Controller();
		$this->adminOnly();
		
		// set common vars
		if (isset($_REQUEST['month']) && isset($_REQUEST['year'])) {
			$this->month = $_REQUEST['month']+0;
			$this->year = $_REQUEST['year']+0;
		}
		else {
			$this->month = date('m')+0;
			$this->year = date('Y')+0;
		}
		$this->daysInMonth = Date::daysInMonth($this->month,$this->year);
		$this->firstDate = "{$this->year}-".($this->month < 10 ? '0' : '')."{$this->month}-01";
		$this->firstDay = date('w',strtotime($this->firstDate));
		$this->schedule_form = array();
		$this->defaults_form = array();

		$this->smarty->assign('adminNavSelected','schedule');

		$this->doAction();
	}

	function index() {
		$currentScheduleId = $_REQUEST['schedule'];
		$schedules = new DO_Schedules();
		$schedules->orderBy("name ASC");
		$schedules->find();
		while ($schedules->fetch()) {
			$sArr[$schedules->id] = $schedules->name;
			if ($schedules->id == $currentScheduleId || (!$currentScheduleId && !$this->schedule))
				$this->schedule = clone($schedules);
		}
		$this->smarty->assign_by_ref('schedule',$this->schedule);
		$this->smarty->assign_by_ref('scheduleOptions',$sArr);

		// if data was passed, fill form with it.  else, fill from db
		if ($this->passData['schedule_form'])
			$this->schedule_form = $this->passData['schedule_form'];
		else
			$this->fillFormFromWindows();
		$this->smarty->assign_by_ref('scheduleForm',$this->schedule_form);

		$previousMonth = ($this->month+10) % 12 + 1;
		$previousYear = ($this->month == 1 ? $this->year-1 : $this->year);
		$nextMonth = $this->month % 12 + 1;
		$nextYear = ($this->month == 12 ? $this->year+1 : $this->year);
		
		$this->smarty->assign('month',$this->month);
		$this->smarty->assign('year',$this->year);
		$this->smarty->assign('previousMonth',$previousMonth);
		$this->smarty->assign('previousYear',$previousYear);
		$this->smarty->assign('nextMonth',$nextMonth);
		$this->smarty->assign('nextYear',$nextYear);

		$this->smarty->assign('daysInMonth',$this->daysInMonth);
		$this->smarty->assign('firstDate',$this->firstDate);
		$this->smarty->assign('firstDay',$this->firstDay);
		
		// if data was passed, fill form with it.  else, fill from db
		if ($this->passData['defaults_form'])
			$this->defaults_form = $this->passData['defaults_form'];
		else
			$this->fillDefaultsFromWindows();
		$this->smarty->assign_by_ref('defaultsForm',$this->defaults_form);
		
		$timeValues = array();
		for ($i=0; $i<24; $i++) {
			$value = ($i<10 ? '0' : '').$i.':00:00';
			$timeValues[$value] = date('g a',strtotime($value));
		}
		$this->smarty->assign_by_ref('timeValues',$timeValues);
		
		$dayNames = array('Sunday','Monday','Tuesday','Wednesday',
			'Thursday','Friday','Saturday');
		$this->smarty->assign_by_ref('dayNames',$dayNames);
		$this->smarty->assign('h1',$this->schedule->name);
		
		$this->smarty->display('manage_schedule.html');
	}
	
	function fillFormFromWindows() {
		$form =& $this->schedule_form;

		$lastDate = "{$this->year}-".
			($this->month < 10 ? '0' : '').
			"{$this->month}-{$this->daysInMonth}";
		
		// get all windows for current schedule
		$windows = new DO_Windows();
		$windows->whereAdd("schedule_id = {$this->schedule->id}");
		$windows->whereAdd("(date BETWEEN '{$this->firstDate}' AND '{$lastDate}')");
		$windows->orderBy("date ASC");
		$windows->find();
		while ($windows->fetch()) {
			// get the day part of the date from the window
			$i = date('d',strtotime($windows->date))+0;
			$form['open'][$i] = $windows->open;
			$form['start_time'][$i] = $windows->start_time;
			$form['end_time'][$i] = $windows->end_time;
		}
	}

	function update() {
		$scheduleId = $_REQUEST['id'];
		$this->schedule = new DO_Schedules();
		$this->schedule->get($scheduleId);
		
		$valid = $this->validateScheduleForm();
		$form =& $this->schedule_form;

		$defaultWindows =& $this->schedule->getDefaultWindows();

		// only save if there are no errors
		if ($valid) {
			// delete all windows for this schedule, this month
			$oldWindows = new DO_Windows();
			$oldWindows->whereAdd("schedule_id = {$this->schedule->id}");
			$oldWindows->whereAdd("MONTH(date) = '{$this->month}'");
			$oldWindows->whereAdd("YEAR(date) = '{$this->year}'");
			$oldWindows->delete(DB_DATAOBJECT_WHEREADD_ONLY);
			
			// add new windows for all values that are non-default
			$dow = $this->firstDay;
			for ($i=1; $i<=$this->daysInMonth; $i++) {
				$date = "{$this->year}-".($this->month < 10 ? '0' : '')."{$this->month}-".
					($i < 10 ? '0' : '')."{$i}";
				if (
					$defaultWindows[$dow]->open != isset($form['open'][$i]) || 
					$defaultWindows[$dow]->start_time != $form['start_time'][$i] ||
					$defaultWindows[$dow]->end_time != $form['end_time'][$i] ) {
						$window = new DO_Windows();
						$window->schedule_id = $this->schedule->id;
						$window->date = $date;
						$window->start_time = $form['start_time'][$i];
						$window->end_time = $form['end_time'][$i];
						$window->open = (isset($form['open'][$i]) ? 1 : 0);
						$window->insert();
					}
				$dow = ($dow+1) % 7;
			}
			
			$this->passMessage('updated','The schedule has been updated.');
		}
		else {
			$this->passData('schedule_form',$this->schedule_form);
		}

		$location = 'manage_schedule.php?month='.$this->month.
			'&year='.$this->year.'&schedule='.$this->schedule->id;
		$this->redirect($location);
	}

	function validateScheduleForm() {
		// check for input errors
		$this->schedule_form =& $_POST;
		$form =& $this->schedule_form;
		
		$badScheduleDays = array();
		// iterate through each day of the month
		for ($i=1; $i<=$this->daysInMonth; $i++) {
			$startTS = strtotime($form['start_time'][$i]);
			$endTS = strtotime($form['end_time'][$i]);
			if (isset($form['open'][$i]) && $startTS >= $endTS) {
				// check that every start time is less than corresponding end time
				$badWindows = true;
				$badScheduleDays[$i] = true;
			}
			else {
				// construct the date for this day
				$date = $this->year.'-'.str_pad($this->month,2,'0',STR_PAD_LEFT).'-'.
					str_pad($i,2,'0',STR_PAD_LEFT);
				// look for any reservations on this day
				$reservations = new DO_Reservations();
				$reservations->whereAdd("schedule_id = {$this->schedule->id}");
				$reservations->whereAdd("date = '{$date}'");
				if (isset($form['open'][$i])) {
					// if this day is open, just look for reservations outside of open times
					$reservations->whereAdd("(start_time < '".date('H:i:s',$startTS).
						"' OR end_time > '".date('H:i:s',$endTS)."')");
				}
				if ($reservations->find()) {
					$reservationConflict = true;
					$badScheduleDays[$i] = true;
				}
			}
		}
		
		if ($badWindows) {
			$this->passError('bad_time_windows',
				'You entered a start time that is the same or later than a corresponding end time.');
			$this->passData('bad_schedule_days',$badScheduleDays);
		}
		if ($reservationConflict) {
			$this->passError('reservations',
				'The schedule you have requested conflicts with existing reservations.');
			$this->passData('bad_schedule_days',$badScheduleDays);
		}

		return !$this->hasErrors();
	}
	
	function fillDefaultsFromWindows() {
		$form =& $this->defaults_form;
		
		// fetch default windows for this schedule
		$defaults = new DO_Window_defaults();
		$defaults->whereAdd("schedule_id = {$this->schedule->id}");
		$defaults->orderBy("day_of_week ASC");
		$defaults->find();
		while ($defaults->fetch()) {
			$i = $defaults->day_of_week;
			$form['default_open'][$i] = $defaults->open;
			$form['default_start_time'][$i] = $defaults->start_time;
			$form['default_end_time'][$i] = $defaults->end_time;
		}
	}
	
	function updateDefaults() {
		$scheduleId = $_REQUEST['id'];
		$this->schedule = new DO_Schedules();
		$this->schedule->get($scheduleId);

		$valid = $this->validateDefaultsForm();
		$form =& $this->defaults_form;

		// only save if there are no errors
		if ($valid) {
			// delete all defaults for this schedule
			$oldDefaults = new DO_Window_defaults();
			$oldDefaults->whereAdd("schedule_id = {$scheduleId}");
			$oldDefaults->delete(DB_DATAOBJECT_WHEREADD_ONLY);
			
			// add new defaults
			for ($i=0; $i<7; $i++) {
				$default = new DO_Window_defaults();
				$default->schedule_id = $scheduleId;
				$default->day_of_week = $i;
				$default->start_time = $form['default_start_time'][$i];
				$default->end_time = $form['default_end_time'][$i];
				$default->open = (isset($form['default_open'][$i]) ? 1 : 0);
				$default->insert();
			}
			$this->passMessage('updated','The schedule has been updated.');
		}
		else {
			$this->passData('defaults_form',$this->defaults_form);
		}

		$location = 'manage_schedule.php?month='.$this->month.
			'&year='.$this->year.'&schedule='.$scheduleId;
		$this->redirect($location);
	}

	function validateDefaultsForm() {
		// check for input errors
		$this->defaults_form =& $_POST;
		$form =& $this->defaults_form;
		
		$badDefaults = array();
		$reservedDefaults = array();
		for ($i=0; $i<7; $i++) {
			// check that every start time is less than corresponding end time
			$startTS = strtotime($form['default_start_time'][$i]);
			$endTS = strtotime($form['default_end_time'][$i]);
			if (isset($form['default_open'][$i]) && $startTS >= $endTS) {
				$badWindows = true;
				$badDefaults[$i] = true;
			}
			else {
				// check for reservations in the future that conflict with the new defaults
				$reservations = new DO_Reservations();
				// reservations for this schedule only
				$reservations->whereAdd("schedule_id = {$this->schedule->id}");
				// reservations in the future
				$reservations->whereAdd("(date > CURDATE() OR (date = NOW() AND start_time > NOW()))");
				// reservations that fall on the given day of the week (in mysql, 1 = sunday)
				$reservations->whereAdd("DAYOFWEEK(date) = ".($i+1));
				if (isset($form['default_open'][$i])) {
					// if this day is open, just look for reservations outside of open times
					$reservations->whereAdd("(start_time < '".date('H:i:s',$startTS).
						"' OR end_time > '".date('H:i:s',$endTS)."')");
				}
				if ($reservations->find()) {
					$reservationConflict = true;
					$badDefaults[$i] = true;
				}
			}
		}
		
		if ($badWindows) {
			$this->passError('bad_defaults',
				'You entered a default start time that is the same or later than a corresponding default end time.');
			$this->passData('bad_defaults',$badDefaults);
		}
		if ($reservationConflict) {
			$this->passError('reserved_defaults',
				'You entered a default that conflicts with one or more upcoming reservations.');
			$this->passData('bad_defaults',$badDefaults);
		}

		return !$this->hasErrors();
	}
}

$controller = new ManageScheduleController();

?>