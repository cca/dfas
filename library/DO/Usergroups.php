<?php
/**
 * Table Definition for usergroups
 */
require_once 'DB/DataObject.php';

class DO_Usergroups extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'usergroups';                      // table name
    var $id;                              // int(10)  not_null primary_key unsigned auto_increment
    var $name;                            // string(80)  not_null
    var $description;                     // blob(65535)  not_null blob

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Usergroups',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

	function getMembers() {
		$sql = "SELECT users.* ".
			"FROM users, users_groups ".
			"WHERE users_groups.group_id = {$this->id} ".
			"AND users_groups.user_id = users.id ".
			"ORDER BY users.last_name, users.first_name ASC";
		$users = new DO_Users();
		$users->query($sql);
		$uArr =& $users->fetchArray();
		return $uArr;
	}

	function getRules() {
		$rules = new DO_Rules();
		$rules->whereAdd("usergroup_id = {$this->id}");
		$rules->find();
		$rArr =& $rules->fetchArray();
		return $rArr;
	}
}
