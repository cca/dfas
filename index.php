<?php
require_once 'config.php';
#DB_DataObject::debugLevel(5);
class IndexController extends Controller {
	
	var $schedule = null;
	
	function IndexController() {
		$this->Controller();
		$this->doAction();
	}
	
	function index() {
		$currentScheduleId = isset($_GET['schedule']) ? $_GET['schedule'] : 0;
		
		$schedules = new DO_Schedules();
		$schedules->orderBy("name ASC");
		$schedules->find();
		while ($schedules->fetch()) {
			$sArr[$schedules->id] = $schedules->name;
			if ($schedules->id == $currentScheduleId || (!$currentScheduleId && !$this->schedule))
				$this->schedule = clone($schedules);
		}
		$this->smarty->assign_by_ref('scheduleOptions',$sArr);

		$date = (isset($_GET['date']) ? date('Y-m-d',$_GET['date']) : '');
		$this->schedule->init($date);
		$this->smarty->assign_by_ref('schedule',$this->schedule);
		$this->smarty->assign('h1',$this->schedule->name.' Schedule');

		$this->initScheduleTable();

		$this->smarty->assign('adminNavSelected','reservations');

		if (isset($_GET['error'])) {
			$error = array($_GET['error'] => true);
			$this->smarty->assign_by_ref('error',$error);
		}

		if (isset($_GET['message'])) {
			$message = array($_GET['message'] => true);
			$messageDate = $_GET['date'];
			$messageTime = $_GET['time'];
			$this->smarty->assign_by_ref('message',$message);
			$this->smarty->assign('highlightDate',$messageDate);
			$this->smarty->assign('highlightTime',$messageTime);
		}

		$this->smarty->display('index.html');
	}
	
	function initScheduleTable() {
		$schedule =& $this->schedule;
		$cells = array();
		$nowStamp = time();
		
		if ($this->loggedIn && !$this->user->isAdmin()) {
  		// pull rules for logged in user
  		$rules = DO_Rules::forUserAndSchedule($this->user->id,$schedule->id);

      // for each rule, find out how many applicable reservations this user has
		  $dbDate = date('Y-m-d',$schedule->day_stamps[0]);
  		foreach ($rules as $rule) {
    		$sql = "SELECT COUNT(DISTINCT reservations.id) as id FROM reservations, schedules_groups ".
    			"WHERE reservations.user_id = {$this->user->id} ".
    			"AND reservations.schedule_id = schedules_groups.schedule_id ".
    			"AND schedules_groups.group_id = {$rule->schedulegroup_id} ".
    			"AND YEARWEEK(reservations.date) = YEARWEEK('{$dbDate}')";
    		$temp_res = new DO_Reservations();
    		$temp_res->query($sql);
    		$temp_res->fetch();
    		$count = $temp_res->id;
    		$rule->user_reservation_count = $count;
  		}
		}
		else {
		  $rules = array();
		}
		
		
		foreach ($schedule->time_stamps as $time) {
			foreach ($schedule->day_stamps as $day) {
				$cell =& $cells[$time][$day];
				$reservation = $schedule->isReserved($day,$time);
				$thisStamp = strtotime(date('Y-m-d',$day).' '.date('H:i:s',$time));
				$cell['stamp'] = $thisStamp;
				
				if ($schedule->isOpen($day,$time)) {					
					// determine inner cell info
					if ($reservation) {
						// if reserved
						$cell['reserved'] = true;
						$cell['div_class'] = 'reserved';
						if ($this->user->id == $reservation->user_id && $this->loggedIn)
							$cell['td_class'] = 'mine';
						$cell['label'] = ($reservation->user_id ?
							$reservation->first_name.' '.$reservation->last_name : 
							$reservation->label);
					}
					elseif ($thisStamp < $nowStamp) {
						// if in the past
						$cell['div_class'] = 'unused';
						$cell['label'] = 'Unused';
					}
					else {
						// if available
						$cell['div_class'] = 'available';
						$cell['label'] = 'Available';
						if ($this->loggedIn &&
							$schedule->reservationAllowed($rules,$this->user,$this->user,$day,$time,false))
							$cell['can_reserve'] = true;
					}
				}
				else {
					$cell['td_class'] = 'blackout';
				}
			}
		}
		
		$this->smarty->assign_by_ref('cells',$cells);
	}
	
	function ajaxShowSchedule() {
		$date = ($_REQUEST['date'] ? date('Y-m-d',$_REQUEST['date']) : '');
		
		$schedules = new DO_Schedules();
		$schedules->orderBy("name ASC");
		$schedules->find();
		while ($schedules->fetch()) {
			$sArr[$schedules->id] = $schedules->name;
		}
		$this->smarty->assign_by_ref('scheduleOptions',$sArr);

		$this->schedule = new DO_Schedules();
		$this->schedule->get($_REQUEST['schedule']);
		$this->schedule->init($date);
		$this->smarty->assign_by_ref('schedule',$this->schedule);

		$this->initScheduleTable();

		$this->smarty->display('schedule.html');
	}
	
	function ajaxUserForReservation() {
		$name = $_REQUEST['user_input'];

		$users = new DO_Users();
		if ($name) {
			$users->searchAdd($name);
			$users->whereAdd("active = 1"); // only return active users!
			$users->find(true);
		}
		$this->smarty->assign_by_ref('search_user',$users);

		$this->smarty->display('ajax/user_for_reservation.html');
	}
}

$controller = new IndexController();
?>
