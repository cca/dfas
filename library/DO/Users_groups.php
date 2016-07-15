<?php
/**
 * Table Definition for users_groups
 */
require_once 'DB/DataObject.php';

class DO_Users_groups extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'users_groups';                    // table name
    var $user_id;                         // int(10)  not_null multiple_key unsigned
    var $group_id;                        // int(10)  not_null unsigned

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Users_groups',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
