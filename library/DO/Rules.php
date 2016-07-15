<?php
/**
 * Table Definition for rules
 */
require_once 'DB/DataObject.php';

class DO_Rules extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'rules';                           // table name
    var $id;                              // int(10)  not_null primary_key unsigned auto_increment
    var $usergroup_id;                    // int(10)  not_null multiple_key unsigned
    var $schedulegroup_id;                // int(10)  not_null unsigned
    var $maximum;                         // int(10)  not_null unsigned
    var $per;                             // string(20)  not_null
    var $message;                         // string(255)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Rules',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

	// return whether this user can make a reservation for this date and time
	// $date and $time are both unix timestamps
	// right now "per" is always assumed to be week...
	function allows($userId,$date,$time) {
	  if (isset($this->user_reservation_count)) {
	    // we can pass in user_reservation_count for optimization purposes (i.e. rendering schedule)
	    return ($this->user_reservation_count < $this->maximum);
	  }
	  else {
  		// look at current reservations for this user for this schedule group
  		$dbDate = date('Y-m-d',$date);
  		$sql = "SELECT COUNT(DISTINCT reservations.id) as id FROM reservations, schedules_groups ".
  			"WHERE reservations.user_id = {$userId} ".
  			"AND reservations.schedule_id = schedules_groups.schedule_id ".
  			"AND schedules_groups.group_id = {$this->schedulegroup_id} ".
  			"AND YEARWEEK(reservations.date) = YEARWEEK('{$dbDate}')";
  		$reservations = new DO_Reservations();
  		$reservations->query($sql);
  		$reservations->fetch();
  		$count = $reservations->id;

  		// if reservation count is less than maximum, return true, else, false.
  		return ($count < $this->maximum);
	  }
	}

	function getDescription() {
		$description = "{$this->maximum} reservations per {$this->per} ";
		
		$group = new DO_Schedulegroups();
		$group->get($this->schedulegroup_id);
		$schedules =& $group->getSchedules();
		$sNames = array();
		foreach ($schedules as $schedule)
			$sNames[] = $schedule->name;
		if (count($sNames) == 1) {
			$description .= 'for: '.$sNames[0];
		}
		else {
			$description .= 'among: '.implode(', ',$sNames);
		}
		
		return $description;
	}

	// STATIC METHODS

	// get the rules linking a given user and schedule
	function forUserAndSchedule($userId,$scheduleId) {
		$sql = "SELECT rules.* FROM rules, schedules_groups, users_groups ".
			"WHERE rules.usergroup_id = users_groups.group_id ".
			"AND rules.schedulegroup_id = schedules_groups.group_id ".
			"AND users_groups.user_id = {$userId} ".
			"AND schedules_groups.schedule_id = {$scheduleId}";
		$rules = new DO_Rules();
		$rules->query($sql);
		$rArr =& $rules->fetchArray();
		return $rArr;
	}
}
