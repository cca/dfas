<?php
/**
 * Table Definition for schedules_groups
 */
require_once 'DB/DataObject.php';

class DO_Schedules_groups extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'schedules_groups';                // table name
    var $schedule_id;                     // int(10)  not_null multiple_key unsigned
    var $group_id;                        // int(10)  not_null unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Schedules_groups',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
