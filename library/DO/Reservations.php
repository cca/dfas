<?php
/**
 * Table Definition for reservations
 */

class DO_Reservations extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'reservations';                    // table name
    var $id;                              // int(10)  not_null primary_key unsigned auto_increment
    var $schedule_id;                     // int(10)  not_null multiple_key unsigned
    var $user_id;                         // int(10)  not_null multiple_key unsigned
    var $date;                            // date(10)  not_null binary
    var $start_time;                      // time(8)  not_null binary
    var $end_time;                        // time(8)  not_null binary
    var $label;                           // string(127)  not_null
    var $no_show;                         // int(4)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Reservations',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

	function getSchedule() {
		$schedule = new DO_Schedules();
		$schedule->get($this->schedule_id);
		return $schedule;
	}
}

?>