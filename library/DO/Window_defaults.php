<?php
/**
 * Table Definition for window_defaults
 */
require_once 'DB/DataObject.php';

class DO_Window_defaults extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'window_defaults';                 // table name
    var $id;                              // int(10)  not_null primary_key unsigned auto_increment
    var $schedule_id;                     // int(10)  not_null multiple_key unsigned
    var $day_of_week;                     // int(3)  not_null multiple_key unsigned
    var $start_time;                      // time(8)  not_null binary
    var $end_time;                        // time(8)  not_null binary
    var $open;                            // int(4)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Window_defaults',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
