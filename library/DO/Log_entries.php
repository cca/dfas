<?php
/**
 * Table Definition for log_entries
 */
require_once 'DB/DataObject.php';

class DO_Log_entries extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'log_entries';                     // table name
    var $id;                              // int(11)  not_null primary_key auto_increment
    var $logtime;                         // timestamp(19)  not_null multiple_key unsigned zerofill binary timestamp
    var $ident;                           // string(16)  not_null
    var $priority;                        // int(11)  not_null
    var $message;                         // string(250)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Log_entries',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
