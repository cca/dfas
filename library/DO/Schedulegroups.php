<?php
/**
 * Table Definition for schedulegroups
 */
require_once 'DB/DataObject.php';

class DO_Schedulegroups extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'schedulegroups';                  // table name
    var $id;                              // int(10)  not_null primary_key unsigned auto_increment
    var $name;                            // string(80)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Schedulegroups',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

	function getSchedules() {
		$sql = "SELECT schedules.* ".
			"FROM schedules, schedules_groups ".
			"WHERE schedules_groups.group_id = {$this->id} ".
			"AND schedules_groups.schedule_id = schedules.id ".
			"ORDER BY schedules.name ASC";
		$schedules = new DO_Schedules();
		$schedules->query($sql);
		$sArr =& $schedules->fetchArray();
		return $sArr;
	}
}
