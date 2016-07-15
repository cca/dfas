<?php
/**
 * Table Definition for users
 */
require_once 'DB/DataObject.php';
require_once 'DO/Users_groups.php';
require_once 'SchedulerMailer.php';

class DO_Users extends DB_DataObject 
{
	var $groupIds = null;
	var $groups = null;
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'users';                           // table name
    var $id;                              // int(10)  not_null primary_key unsigned auto_increment
    var $username;                        // string(40)  not_null unique_key
    var $password;                        // string(40)  not_null
    var $first_name;                      // string(60)  not_null multiple_key
    var $last_name;                       // string(60)  not_null
    var $reservations_left;               // int(10)  not_null unsigned
    var $department;                      // string(40)  not_null
    var $admin;                           // int(4)  not_null multiple_key
    var $active;                          // int(4)  not_null

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DO_Users',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

	function getName() {
		return $this->first_name.' '.$this->last_name;
	}
	function getEmail() {
		return $this->username.'@cca.edu';
	}
	
	function getPastReservations() {
		$reservations = new DO_Reservations();
		$reservations->whereAdd("user_id = {$this->id}");
		$reservations->whereAdd("(date < CURDATE() OR (date = NOW() AND start_time < NOW()))");
		$reservations->orderBy("date DESC");
		$reservations->find();
		$arr = array();
		while ($reservations->fetch())
			$arr[] = clone($reservations);
		return $arr;
	}
	
	function getUpcomingReservations($scheduleId = 0) {
		$reservations = new DO_Reservations();
		$reservations->whereAdd("user_id = {$this->id}");
		$reservations->whereAdd("(date > CURDATE() OR (date = NOW() AND start_time > NOW()))");
		if ($scheduleId)
			$reservations->whereAdd("schedule_id = {$scheduleId}");
		$reservations->orderBy("date ASC");
		$reservations->find();
		$arr = array();
		while ($reservations->fetch())
			$arr[] = clone($reservations);
		return $arr;
	}
	
	function isAdmin() { return $this->admin == 1; }
	function getDepartment() {
		$departments =& $this->getAllDepartments();
		return $departments[$this->department][0];
	}
	
	function getAllDepartments() {
		global $userDepartments;
		return $userDepartments;
	}
	
	function getGroups() {
		if (!$this->groups) {
			$sql = "SELECT usergroups.* FROM usergroups, users_groups ".
				"WHERE users_groups.user_id = {$this->id} ".
				"AND users_groups.group_id = usergroups.id ".
				"ORDER BY name ASC";
			$groups = new DO_Usergroups();
			$groups->query($sql);
			$this->groups =& $groups->fetchArray();
		}
		return $this->groups;
	}
	function getGroupIds() {
		if (!$this->groupIds) {
			$links = new DO_Users_groups();
			$links->user_id = $this->id;
			$links->find();
			$this->groupIds = array();
			while ($links->fetch())
				$this->groupIds[] = $links->group_id;
		}
		return $this->groupIds;
	}
	function inGroup($groupId) {
		// lazy fetch the group ids
		$groupIds =& $this->getGroupIds();
		return in_array($groupId,$groupIds);
	}
	
	function setGroupIds($groupIds) {
		$this->_deleteGroupLinks();
		foreach ($groupIds as $gid) {
			$link = new DO_Users_groups();
			$link->user_id = $this->id;
			$link->group_id = $gid;
			$link->insert();
		}
	}
	
	function searchAdd($s) {
		$terms = explode(' ',$s);
		foreach($terms as $term) {
			$this->whereAdd("(username LIKE '{$term}%' OR ".
				"first_name LIKE '{$term}%' OR last_name LIKE '{$term}%')");
		}
	}
	
	function delete() {
		// get user's past reservations and convert to blocks
		$pastReservations = $this->getPastReservations();
		$name = $this->getName();
		for ($i=0; $i<count($pastReservations); $i++) {
			$pastReservations[$i]->user_id = 0;
			$pastReservations[$i]->label = $name;
			$pastReservations[$i]->update();
		}
		
		// get user's upcoming reservations and delete
		$upcomingReservations = $this->getUpcomingReservations();
		for ($i=0; $i<count($upcomingReservations); $i++) {
			$upcomingReservations[$i]->delete();
		}
		
		$this->_deleteGroupLinks();
		
		return parent::delete();
	}
	function _deleteGroupLinks() {
		$links = new DO_Users_groups();
		$links->whereAdd("user_id = {$this->id}");
		$links->delete(DB_DATAOBJECT_WHEREADD_ONLY);
	}
}

$userDepartments = array(
	'animation' => array('Animation', 'Undergraduate'),
	'barch' => array('Architecture (BA)', 'Undergraduate'),
	'ceramics' => array('Ceramics', 'Undergraduate'),
	'communityarts' => array('Community Arts', 'Undergraduate'),
	'fashion' => array('Fashion Design', 'Undergraduate'),
	'furniture' => array('Furniture', 'Undergraduate'),
	'glass' => array('Glass', 'Undergraduate'),
	'graphicdesign' => array('Graphic Design', 'Undergraduate'),
	'illustration' => array('Illustration', 'Undergraduate'),
	'individualized' => array('Individualized Major', 'Undergraduate'),
	'industrialdesign' => array('Industrial Design', 'Undergraduate'),
	'interiordesign' => array('Interior Design', 'Undergraduate'),
	'metals' => array('Jewelry/Metal Arts', 'Undergraduate'),
	'mediaarts' => array('Media Arts', 'Undergraduate'),
	'painting' => array('Painting/Drawing', 'Undergraduate'),
	'photography' => array('Photography', 'Undergraduate'),
	'printmaking' => array('Printmaking', 'Undergraduate'),
	'sculpture' => array('Sculpture', 'Undergraduate'),
	'textiles' => array('Textiles', 'Undergraduate'),
	'visualstudies' => array('Visual Studies', 'Undergraduate'),
	'writingliterature' => array('Writing &amp; Literature', 'Undergraduate'),
	'march' => array('Architecture (MA)', 'Graduate'),
	'curatorialpractice' => array('Curatorial Practice', 'Graduate'),
	'design' => array('Design', 'Graduate'),
	'film' => array('Film', 'Graduate'),
	'finearts' => array('Fine Arts', 'Graduate'),
	'visualcriticalstudies' => array('Vis. &amp; Crit. Studies', 'Graduate'),
	'mfawriting' => array('Writing', 'Graduate'),
	'criticalstudies' => array('Critical Studies', 'Other'),
	'core' => array('First Year Program', 'Other'),
	'teaching' => array('Teaching', 'Other')
	);

?>