<?php
/**
 * Table Definition for windows
 */
require_once 'DB/DataObject.php';

class DO_Windows extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'windows';                         // table name
    var $id;                              // int(10)  not_null primary_key unsigned auto_increment
    var $schedule_id;                     // int(10)  not_null multiple_key unsigned
    var $date;                            // date(10)  not_null multiple_key binary
    var $start_time;                      // time(8)  not_null binary
    var $end_time;                        // time(8)  not_null binary
    var $open;                            // int(4)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Windows',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}

?>