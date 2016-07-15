<?php
require_once 'config.php';

class ManageUsersController extends Controller {
	
	var $user_form;
	var $edit_user;

	function ManageUsersController() {
		$this->Controller();
		$this->adminOnly();
		
		$this->user_form = array();
		$this->edit_user = new DO_Users();

		$departments =& DO_Users::getAllDepartments('departments',$departments);
		$this->smarty->assign_by_ref('departments',$departments);	

		$this->smarty->assign('adminNavSelected','users');
		
		$this->doAction();
	}

	function index() {
		$name = $_REQUEST['search_name'];
		$department = $_REQUEST['search_department'];
		$role = $_REQUEST['search_role'];
		$active = $_REQUEST['search_active'];

		$users = new DO_Users();
		if ($name) $users->searchAdd($name);
		if ($department) $users->whereAdd("department = '{$department}'");
		if ($active) $users->whereAdd("active = ".($active == 'yes' ? 1 : 0));
		$users->orderBy("last_name ASC");

		$users->find();
		$userArr =& $users->fetchArray();
		$this->smarty->assign_by_ref('search_users',$userArr);

		// display any errors
		if (isset($_REQUEST['error'])) {
			$this->smarty->assign('error',array($_REQUEST['error'] => true));
		}

		// display any messages
		if (isset($_REQUEST['message'])) {
			$this->smarty->assign('message',array($_REQUEST['message'] => true));
		}

		$this->smarty->assign('h1','Manage Accounts');
		$this->smarty->display('manage_users.html');
	}

	function show() {
		$userId = $_REQUEST['id'];
		
		$user = new DO_Users();
		$user->get($userId);
		$this->smarty->assign_by_ref('showUser',$user);
		
		$upcomingReservations =& $user->getUpcomingReservations();
		$this->smarty->assign_by_ref('upcomingReservations',$upcomingReservations);
		$pastReservations =& $user->getPastReservations();
		$this->smarty->assign_by_ref('pastReservations',$pastReservations);
				
		$schedules = new DO_Schedules();
		$schedules->find();
		while ($schedules->fetch())
			$sArr[$schedules->id] = $schedules->name;
		$this->smarty->assign_by_ref('scheduleNames',$sArr);
		
		$this->smarty->assign('h1',$user->getName());
		$this->smarty->display('show_user.html');
	}

	function init() {
		$this->prepareUserForm();
		$this->user_form['action'] = 'create';
		$this->smarty->assign('h1','Add an Account');
		$this->smarty->display('edit_user.html');
	}

	function create() {
		$valid = $this->validateUserForm();

		// only save if there are no errors
		if ($valid) {
			$new_id = $this->edit_user->insert();
			$this->logInfo("created account for {$this->edit_user->username}");
			
			$this->passMessage('account_created','Account information updated.');
			$this->urlAndRedirect('manage_users','show',$new_id);
		}
		else {
			$this->passData('user_form',$this->user_form);
			$this->urlAndRedirect('manage_users','init');
		}
	}

	function edit() {
		$id = $_REQUEST['id'];
		$this->edit_user->get($id);
	
		// fill user form from db data		
		$this->user_form['username'] = $this->edit_user->username;
		$this->user_form['first_name'] = $this->edit_user->first_name;
		$this->user_form['last_name'] = $this->edit_user->last_name;
		$this->user_form['department'] = $this->edit_user->department;
		$this->user_form['active'] = $this->edit_user->active;
		$this->user_form['admin'] = $this->edit_user->admin;		

		$this->user_form['user_id'] = $id;
		
		$this->smarty->assign('h1','Update an Account');

		$this->prepareUserForm();
		$this->user_form['action'] = 'update';
		
		$this->smarty->display('edit_user.html');
	}

	function update() {
		$valid = $this->validateUserForm();
	
		// only save if there are no errors
		if ($valid) {
			$this->edit_user->update();

			$this->logInfo("updated account info for {$this->edit_user->username}");

			$this->passMessage('account_updated','Account information updated.');
			$this->urlAndRedirect('manage_users','show',$this->edit_user->id);
		}
		else {
			$this->passData('user_form',$this->user_form);
			$this->urlAndRedirect('manage_users','edit',$this->edit_user->id);
		}
	}
	
	function delete() {
		// if user is not admin, log and show error
		if (!$this->user->isAdmin()) {
			$this->logNotice("unauthorized account delete attempt");
			$this->passError('non_admin_delete','Only admins may delete accounts');
		}
		// if user id not submitted, show error
		elseif (!isset($_POST['user_id'])) {
			$this->passError('delete_no_id','No account specified for delete.');
		}
		// user can't delete his own account
		elseif ($_POST['user_id'] == $this->user->id) {
			$this->passError('delete_self','You cannot delete your own account.');
		}
		else {
			$this->edit_user->get($_POST['user_id']);
			$username = $this->edit_user->username;
			$name = $this->edit_user->getName();
			$this->edit_user->delete();
			$this->logNotice("account deleted for {$name} ({$username}) ");
			$this->passMessage('deleted','Account deleted.');
		}
		$this->urlAndRedirect('manage_users');
	}

	function prepareUserForm() {
		if ($this->passData['user_form'])
			$this->user_form = $this->passData['user_form'];
		$this->smarty->assign_by_ref('user_form',$this->user_form);
	}

	function validateUserForm() {
		$edit_user =& $this->edit_user;
		
		if ($_POST['user_id']) $edit_user->id = $_POST['user_id'];
		$edit_user->username = trim($_POST['username']);
		$edit_user->first_name = trim($_POST['first_name']);
		$edit_user->last_name = trim($_POST['last_name']);
		$edit_user->department = $_POST['department'];
		$edit_user->active = (isset($_POST['active']) ? 1 : 0);		
		$edit_user->admin = (isset($_POST['admin']) ? 1 : 0);		
	
		// check for input errors
		$this->user_form =& $_POST;
		$error = array();
		if (!$edit_user->username)
			$this->passError('no_username','Username is empty.');
		if (!$edit_user->first_name)
			$this->passError('no_first_name','First Name is empty.');
		if (!$edit_user->last_name)
			$this->passError('no_last_name','Last Name is empty.');
		if ($edit_user->role == 'u' && !$edit_user->department)
			$this->passError('no_department','No Department specified.');

		// check whether username already exists (and account id is different)
		$dupe_user = new DO_Users();
		$dupe_user->whereAdd("username = '{$edit_user->username}'");
		if ($edit_user->id)
			$dupe_user->whereAdd("id != {$edit_user->id}");
		if ($dupe_user->find()) {
			$this->passError('username_exists','That username is already in use.');
		}

		// errors that only apply to work-study's
		if (!$this->user->isAdmin()) {
			// only admins can edit admin accounts
			if ($this->edit_user->isAdmin())
				$this->passError('non_user','You may not edit admin accounts.');
		}

		return !$this->hasErrors();
	}
	
	function ajaxUserSearch() {
		$name = $_REQUEST['search_name'];
		$department = $_REQUEST['search_department'];
		$admin = $_REQUEST['search_admin'];
		$active = $_REQUEST['search_active'];

		$users = new DO_Users();
		if ($name) $users->searchAdd($name);
		if ($department) $users->whereAdd("department = '{$department}'");
		if ($admin) $users->whereAdd("admin = ".($admin == 'yes' ? 1 : 0));
		if ($active) $users->whereAdd("active = ".($active == 'yes' ? 1 : 0));
		$users->orderBy("last_name ASC");
		$users->find();
		$userArr =& $users->fetchArray();
		$this->smarty->assign_by_ref('search_users',$userArr);

		$this->smarty->display('ajax/user_list.html');
	}

	function sendAccountNotification() {
		$id = $_REQUEST['id'];
		$this->edit_user->get($id);
		$user =& $this->edit_user;
		$this->smarty->assign_by_ref('email_user',$user);

		$mailer = new SchedulerMailer();
		$email = $user->getEmail();
		$mailer->AddAddress($email,$user->getName());
		$mailer->Subject = 'CCA DFAS Account Information';
		$mailer->Body = $this->smarty->fetch('emails/account_notification.html');
		$success = $mailer->Send();
		if (!$success) {
			$this->logErr("email error during account notify (to {$email}): {$mailer->ErrorInfo}");
			$this->passError('email_error','There was an error sending the notification email.');
		}
		else {
			$this->passMessage('notified','Account notification email sent.');
		}
		$this->urlAndRedirect('manage_users','show',$this->edit_user->id);
	}
	
	function markNoshow() {
		$reservationId = $_REQUEST['id'];
		$reservation = new DO_Reservations();
		$reservation->get($reservationId);
		$reservation->no_show = 1;
		$reservation->update();
		
		$this->passData('highlightId',$reservationId);
		$this->passMessage('mark_noshow','Reservation marked as no-show.');
		
		$this->urlAndRedirect('manage_users','show',$reservation->user_id);
	}
	
	function undoNoshow() {
		// only allow the undo if there isn't another non-noshow reservation at the same time!
		$reservationId = $_REQUEST['id'];
		$reservation = new DO_Reservations();
		$reservation->get($reservationId);
		
		$others = new DO_Reservations();
		$others->whereAdd("schedule_id = {$reservation->schedule_id}");
		$others->whereAdd("date = '{$reservation->date}'");
		$others->whereAdd("start_time = '{$reservation->start_time}'");
		$others->whereAdd("no_show = 0");
		$others->whereAdd("id <> {$reservation->id}");
		$othersExist = $others->find();
		
		if ($othersExist) {
			$this->passError('existing_reservation',
				"Another reservation for this schedule, date, and time already exists.");
		}
		else {
			$this->passData('highlightId',$reservationId);
			$this->passMessage('undo_noshow','Reservation no longer marked as no-show.');
			$reservation->no_show = 0;
			$reservation->update();
		}
		
		$this->urlAndRedirect('manage_users','show',$reservation->user_id);
	}
}

$controller = new ManageUsersController();

?>