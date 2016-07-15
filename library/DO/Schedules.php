<?php
/**
 * Table Definition for schedules
 */
require_once 'DB/DataObject.php';

class DO_Schedules extends DB_DataObject 
{
	var $day_stamps;
	var $start_time;
	var $end_time;
	var $windows;
	var $previous_stamp;
	var $next_stamp;
	var $defaultWindows = null;
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'schedules';                       // table name
    var $id;                              // int(10)  not_null primary_key unsigned auto_increment
    var $name;                            // string(127)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Schedules',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

	function init($initDate = '') {
		// get the timestamps for the days of this week
		// the sunday at the beginning of this week.
		if (!$initDate) $initDate = 'today';
		$initStamp = strtotime($initDate);
		$sundayStamp = strtotime('-'.date('w',$initStamp).' days',$initStamp);
		$this->day_stamps[] = $sundayStamp;
		for ($i=1; $i<7; $i++) {
			$this->day_stamps[] = strtotime("+$i days",$sundayStamp);
		}

		$this->previous_stamp = strtotime("-1 days", $this->day_stamps[0]);
		$this->next_stamp = strtotime("+1 days", $this->day_stamps[6]);

		// get the time windows for the days of this week
		$dbStartDay = date('Y-m-d',$this->day_stamps[0]);
		$dbEndDay = date('Y-m-d',$this->day_stamps[6]);
		$rangeWindow = new DO_Windows();
		// get all open windows during this week
		$q = "SELECT COUNT(*) as id, MIN(start_time) as start_time, MAX(end_time) as end_time ".
			"FROM windows ".
			"WHERE date BETWEEN '{$dbStartDay}' AND '{$dbEndDay}' ".
			"AND open = 1 ".
			"AND schedule_id = {$this->id}";
		$rangeWindow->query($q);
		$rangeWindow->fetch();

		// fetch default windows
		$defaultWindows = $this->getDefaultWindows();
		
		// find earliest open time and latest close time
		$defaultStart = strtotime('23:59:00');
		$defaultEnd = strtotime('00:00:01');
		for ($i=0; $i<7; $i++) {
			if ($defaultWindows[$i]->open) {
				$defaultStart = min($defaultStart,strtotime($defaultWindows[$i]->start_time));
				$defaultEnd = max($defaultEnd,strtotime($defaultWindows[$i]->end_time));
			}
		}

		if ($rangeWindow->id) {
			$this->start_time = min($defaultStart,strtotime($rangeWindow->start_time));
			$this->end_time = max($defaultEnd,strtotime($rangeWindow->end_time));
		}
		else {
			$this->start_time = $defaultStart;
			$this->end_time = $defaultEnd;
		}
		$this->time_stamps = array();
		for ($i=$this->start_time; $i<$this->end_time; $i += 3600)
			$this->time_stamps[] = $i;

		// get windows for this schedule, this week
		$windows = new DO_Windows();
		$windows->whereAdd("schedule_id = {$this->id}");
		$windows->whereAdd("date BETWEEN '{$dbStartDay}' AND '{$dbEndDay}'");
		$windows->orderBy("date ASC");
		$windows->find();
		$this->windows = array();
		while ($windows->fetch())
			$this->windows[$windows->date] = clone($windows);

		// get the reservations for this schedule, this week
		// ONLY those that are not marked as no-show
		$reservations = new DO_Reservations();
		$q = "SELECT DISTINCT reservations.*, users.first_name, users.last_name ".
			"FROM reservations, users ".
			"WHERE date BETWEEN '{$dbStartDay}' AND '{$dbEndDay}' ".
			"AND (users.id = reservations.user_id OR reservations.user_id = 0) ".
			"AND reservations.schedule_id = {$this->id} ".
			"AND reservations.no_show = 0 ".
			"ORDER BY date ASC, start_time ASC";
		$reservations->query($q);
		$this->reservations = array();
		while ($reservations->fetch())
			$this->reservations[$reservations->date][$reservations->start_time] = clone($reservations);
	}

	// returns whether the schedule is "open" (NOT "available") during a specified time/date
	function isOpen($dayStamp,$timeStamp) {
		$window =& $this->windows[date('Y-m-d',$dayStamp)];
		if ($window && !$window->open)
			return false;
		else {
			if ($window) {
				return ($timeStamp >= strtotime($window->start_time) &&
					$timeStamp < strtotime($window->end_time));
			}
			else {
				// check the defaults
				$defaultWindows = $this->getDefaultWindows();
				$dayOfWeek = date('w',$dayStamp);
				$defaultWindow =& $defaultWindows[$dayOfWeek];
				return ($defaultWindow->open && $timeStamp >= strtotime($defaultWindow->start_time) &&
					$timeStamp < strtotime($defaultWindow->end_time));
			}
		}
	}

	function isReserved($dayStamp,$timeStamp) {
		$reservation =& 
			$this->reservations[date('Y-m-d',$dayStamp)][date('H:i:s',$timeStamp)];
		return $reservation;
	}
	
	function getDefaultWindows() {
		if (!$this->defaultWindows) {
			$defaultWindows = new DO_Window_defaults();
			$defaultWindows->whereAdd("schedule_id = {$this->id}");
			$defaultWindows->orderBy("day_of_week ASC");
			$defaultWindows->find();
			$this->defaultWindows =& $defaultWindows->fetchArray();
		}
		return $this->defaultWindows;
	}
	
	/*
	 * $from - user making reservation
	 * $to - who reservation is for (or label for reservation, if "other" mode)
	 * $returnData - bool specifying whether to return extended error data
	 */
	function reservationAllowed($rules,$from,$to,$date,$time,$returnData = true) {
		$userMode = is_object($to);
		$dbTimestamp = date('Y-m-d',$date).' '.date('H:i:s',$time);
		
		// rules barring any reservation:
		// - studio closed
		// - reservation already exists
		// - time not on the hour
		// - time in the past
		
		// if the schedule is not open then
		if (!$this->isOpen($date,$time)) {
			$logError = 'studio closed';
			$passError = 'not_open';
			$errorData = 'The studio is not open at the request time.';
		}
		// if it's already reserved
		elseif ($this->isReserved($date,$time)) {
			$logError = 'existing reservation';
			$passError = 'reserved';
			$errorData = 'A reservation already exists at the requested time.';
		}
		// if they somehow put in a time that's not on the hour
		elseif (date('i',$time) != '00') {
			$logError = 'time not on the hour';
			$passError = 'bad_time';
			$errorData = 'You must request a reservation that starts on the hour.';
		}
		// if they picked a time that's in the past
		elseif (strtotime($dbTimestamp) < time()) {
			$logError = 'time in the past';
			$passError = 'old_time';
			$errorData = 'The requested time is in the past.';
		}

		// rules barring a user (non-admin) reservation:
		// - rules stored in the database
		if ($from->isAdmin()) {
			// admin
			if (!$userMode && !$label) {
				$logError = 'empty label';
				$passError = 'empty_label';
				$errorData = 'You must provide a label for the blocked-off time.';
			}
		}
		else {
			// non-admin user
			if (!$userMode) {
				$logError = 'non-admin user attempt at block-off';
				$passError = 'non_admin_block_off';
				$errorData = 'Only admins can block-off schedule time.';
			}
			else {
				// user mode, non-admin user
				if (!count($rules)) {
					$logError = 'user cannot access schedule';
					$passError = 'no_schedule_access';
					$errorData = 'You do not have access to the request schedule.';
				}
				else {
					foreach ($rules as $rule) {
						if (!$rule->allows($to->id,$date,$time)) {
							$logError = 'reservation breaks rule';
							$passError = 'rule_broken';
							$errorData = $rule->message;
						}
					}
				}
			}
		}
		
		if ($logError) {
			if ($returnData) {
				$ret = array('log_error' => $logError,
					'pass_error' => $passError,
					'error_data' => (isset($errorData) ? $errorData : true));
				return $ret;
			}
			else {
				return false;
			}
		}
		else {
			return true;
		}
	}
	
	function makeReservation($user,$date,$time) {
		$userMode = is_object($user);
		$reservation = new DO_Reservations();
		if ($userMode) {
			// 'user' mode
			$reservation->user_id = $user->id;
		}
		else {
			// 'other' mode
			$reservation->label = $user;
		}
		$reservation->schedule_id = $this->id;
		$reservation->date = date('Y-m-d',$date);
		$reservation->start_time = date('H:i:s',$time);
		$reservation->end_time = date('H:i:s',$time+(60*59));	// 1-hour reservation
		$reservation->insert();
		
		return $reservation;
	}
	
	function cancelReservation($date,$time) {
		$reservation = new DO_Reservations();
		$reservation->whereAdd("schedule_id = {$this->id}");
		$reservation->whereAdd("date = '".date('Y-m-d',$date)."'");
		$reservation->whereAdd("start_time = '".date('H:i:s',$time)."'");
		// for now we don't need to search based on end_time
		$found = $reservation->find(true);

		$reservation->delete();
	}
}
