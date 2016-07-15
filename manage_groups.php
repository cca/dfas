<?php
require_once 'config.php';

class ManageGroupsController extends Controller {
	
	var $user_form;
	var $edit_user;

	function ManageGroupsController() {
		$this->Controller();
		$this->adminOnly();
		
		$this->smarty->assign('adminNavSelected','groups');
		
		$this->doAction();
	}

	function index() {
		$usergroups = new DO_Usergroups();
		$usergroups->orderBy("name ASC");
		$usergroups->find();
		$ugArr =& $usergroups->fetchArray();
		$this->smarty->assign_by_ref('usergroups',$ugArr);

		$this->smarty->assign('h1','Manage User Groups');
		$this->smarty->display('manage_groups.html');
	}

	function init() {
		$this->user_form['action'] = 'create';
		$this->prepareUserForm();
		$this->smarty->assign('h1','Add an Account');
		$this->smarty->display('edit_user.html');
	}

	function create() {
		$valid = $this->validateUserForm();

		// only save if there are no errors
		if ($valid) {
			$new_id = $this->edit_user->insert();
			$this->log->info("{$this->user->username}: created account for {$this->edit_user->username}");
			
			if (isset($_REQUEST['notify']))
				$this->edit_user->sendAccountNotification();
			
			$this->redirect('manage_users.php');
		}
		else {
			$this->prepareUserForm();
		}
	}

	function edit() {
		$id = $_REQUEST['id'];
		$group = new DO_Usergroups();
		$group->get($id);
		$this->smarty->assign_by_ref('group',$group);
		
		$members =& $group->getMembers();
		$this->smarty->assign_by_ref('members',$members);

		$rules =& $group->getRules();
		$this->smarty->assign_by_ref('rules',$rules);
	
		$this->smarty->assign('h1',"User Group: {$group->name}");
	
		$this->smarty->display('edit_group.html');
	}

	function update() {
		$valid = $this->validateUserForm();
	
		// only save if there are no errors
		if ($valid) {
			$this->edit_user->update();

			$this->log->info("{$this->user->username}: updated account info for {$this->edit_user->username}");

			if (isset($_REQUEST['notify']))
				$this->edit_user->sendAccountNotification();
			
			$this->redirect('manage_users.php');
		}
		else {
			$this->prepareUserForm();
		}
	}

	function ajaxMemberSearch() {
		$search = $_REQUEST['member_search'];
		$users = new DO_Users();
		if ($search) {
			$users->searchAdd($search);
			$users->find();
			$uArr =& $users->fetchArray();
		}
		$this->smarty->assign_by_ref('search_members',$uArr);
		$this->smarty->display('ajax/member_search.html');
	}
	
	function ajaxAddMember() {
		$userId = $_REQUEST['user_id'];
		$groupId = $_REQUEST['group_id'];
		
		$link = new DO_Users_groups();
		$link->user_id = $userId;
		$link->group_id = $groupId;
		if (!$link->find())
			$link->insert();
		
		$group = new DO_Usergroups();
		$group->get($groupId);
		$members =& $group->getMembers();
		$this->smarty->assign_by_ref('group',$group);
		$this->smarty->assign_by_ref('members',$members);
		$this->smarty->assign('addedUserId',$userId);
		
		$this->smarty->display('ajax/member_list.html');
	}
	
	function ajaxRemoveMember() {
		$userId = $_REQUEST['user_id'];
		$groupId = $_REQUEST['group_id'];
		
		$link = new DO_Users_groups();
		$link->whereAdd("user_id = {$userId}");
		$link->whereAdd("group_id = {$groupId}");
		$link->delete(DB_DATAOBJECT_WHEREADD_ONLY);
		
		$group = new DO_Usergroups();
		$group->get($groupId);
		$members =& $group->getMembers();
		$this->smarty->assign_by_ref('group',$group);
		$this->smarty->assign_by_ref('members',$members);
		
		$this->smarty->display('ajax/member_list.html');
	}
}

$controller = new ManageGroupsController();

?>