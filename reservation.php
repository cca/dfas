<?php
require_once 'config.php';

class ReservationController extends Controller {
	
	function ReservationController() {
		$this->Controller();
		$this->requireActiveUser();
		
		$this->doAction();
	}
	
	function make() {
		$scheduleId = $_REQUEST['schedule'];
		$date = $_REQUEST['date'];
		$time = $_REQUEST['time'];
		
		$schedule = new DO_Schedules();
		$schedule->get($scheduleId);
		
		$this->smarty->assign_by_ref('schedule',$schedule);
		$this->smarty->assign('date',$date);
		$this->smarty->assign('time',$time);
		$this->smarty->assign('h1',$schedule->name.' Schedule');
		$this->smarty->display('make_reservation.html');
	}
	
	function confirmMake() {
		$scheduleId = $_REQUEST['schedule'];
		$date = $_REQUEST['date'];
		$time = $_REQUEST['time'];
		$mode = (isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'user');
		$userId = (isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0);
		$label = (isset($_REQUEST['label']) ? $_REQUEST['label'] : '');

		$this->_makeProcess($scheduleId,$date,$time,$mode,$userId,$label);

		// redirect the entire page
		$this->redirect("index.php?date={$date}&schedule={$scheduleId}");
	}
	
	function ajaxMake() {
			$scheduleId = $_REQUEST['schedule'];
			$date = $_REQUEST['date'];
			$time = $_REQUEST['time'];
			$mode = (isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 'user');
			$userId = (isset($_REQUEST['user']) ? $_REQUEST['user'] : 0);
			$label = (isset($_REQUEST['label']) ? $_REQUEST['label'] : '');
			
			$this->_makeProcess($scheduleId,$date,$time,$mode,$userId,$label);
			
			// redirect just for the ajax portion
			$this->redirect("index.php?action=ajax_show_schedule&".
				"date={$date}&schedule={$scheduleId}");
	}
	
	function _makeProcess($scheduleId,$date,$time,$mode,$userId,$label) {
		$dbTimestamp = date('Y-m-d',$date).' '.date('H:i:s',$time);

		// grab the optional querystring vars
		if ($mode == 'user') {
			if ($this->user->isAdmin()) {
				$reserve_user = new DO_Users();
				$reserve_user->get($userId);		
			}
			else
				$reserve_user =& $this->user;
		}
		else
			$reserve_user =& $this->user;

		// bit of a HACK ... wait a random small amount of time to try to avoid race condition
		// ... if current time is on the hour!
		//if (date('i') == '00') {
		//	$sleepTime = rand(0,7);
		//	sleep($sleepTime);
		//}

		// look at schedule for week of reservation!
		$schedule = new DO_Schedules();
		$schedule->get($scheduleId);
		$schedule->init($dbTimestamp);

		if ($this->loggedIn && !$this->user->isAdmin()) {
  		// pull rules for logged in user
  		$rules = DO_Rules::forUserAndSchedule($this->user->id,$schedule->id);
		}
		else {
		  $rules = array();
		}

		$allowed = $schedule->reservationAllowed($rules,$this->user,$reserve_user,
			$date,$time);
		if ($allowed !== true) {
			$logError = $allowed['log_error'];
			$this->passError($allowed['pass_error'],$allowed['error_data']);
		}

		if ($this->hasErrors()) {
			$this->logNotice("reservation failure".
				($this->user->isAdmin() ? " for {$reserve_user->username}" : '').
				" for {$dbTimestamp} ({$logError})");
		}
		else {
			// SUCCESS! - make the reservation
			if ($mode == 'user' || !$this->user->isAdmin()) {
				$reservation =& $schedule->makeReservation($reserve_user,$date,$time);
				$this->logInfo("reservation made".
					($this->user->isAdmin() ? " for {$reserve_user->username}" : '').
					" for {$dbTimestamp}");
				// turn off email notifications til I can fix it.

				// send email notification
				$this->smarty->assign_by_ref('email_user',$reserve_user);
				$this->smarty->assign_by_ref('email_reservation',$reservation);
				$mailer = new SchedulerMailer();
				$email = $reserve_user->getEmail();
				$mailer->AddAddress($email,$reserve_user->getName());
				$mailer->Subject = 'CCA DFAS Reservation: '.
					date('l, F j',$date).', '.date('g a',$time);
				$mailer->Body = $this->smarty->fetch('emails/make_reservation.html');
				$success = $mailer->Send();
				if (!$success) {
					$this->logErr("email error during reservation create (to {$email}): ".
						"{$mailer->ErrorInfo}");
					$this->passError('email_error',"Email notification unsuccessful (to {$email}): ".
						"{$mailer->ErrorInfo}");
				}
			}
			else {
				$schedule->makeReservation($label,$date,$time);
				$this->logInfo("reservation made (label: {$label})".
					" for {$dbTimestamp}");
			}

			$this->passMessage('reserved',"Reservation made.");
			$this->passData('reserved',
				array('date'=>$date, 'time'=>$time, 'schedule'=>$schedule->id));
		}
	}
	
	function cancel() {
		$scheduleId = $_REQUEST['schedule'];
		$date = $_REQUEST['date'];
		$time = $_REQUEST['time'];
		
		$schedule = new DO_Schedules();
		$schedule->get($scheduleId);
		
		$this->smarty->assign_by_ref('schedule',$schedule);
		$this->smarty->assign('date',$date);
		$this->smarty->assign('time',$time);
		$this->smarty->assign('h1',$schedule->name.' Schedule');
		$this->smarty->display('cancel_reservation.html');
	}
	
	function confirmCancel() {
		$scheduleId = $_REQUEST['schedule'];
		$date = $_REQUEST['date'];
		$time = $_REQUEST['time'];

		$this->_cancelProcess($scheduleId,$date,$time);

		// redirect the whole page
		$this->redirect("index.php?date={$date}&schedule={$schedule->id}");
	}

	function ajaxCancel() {
		$scheduleId = $_REQUEST['schedule'];
		$date = $_REQUEST['date'];
		$time = $_REQUEST['time'];
		
		$this->_cancelProcess($scheduleId,$date,$time);
		
		// redirect just for the ajax portion
		$this->redirect("index.php?action=ajax_show_schedule&".
			"date={$date}&schedule={$scheduleId}");
	}
	
	function _cancelProcess($scheduleId,$date,$time) {
		$dbTimestamp = date('Y-m-d',$date).' '.date('H:i:s',$time);

		// first, look for this reservation
		$reservation = new DO_Reservations();
		$reservation->whereAdd("schedule_id = {$scheduleId}");
		$reservation->whereAdd("date = '".date('Y-m-d',$date)."'");
		$reservation->whereAdd("start_time = '".date('H:i:s',$time)."'");
		// for now we don't need to search based on end_time
		$found = $reservation->find(true);

		// validate first
		if (!$found) {
			$logError = 'not found';
			$this->passError('not_found','The reservation was not found.');
		}
		elseif (!$this->user->isAdmin()) {
			if ($reservation->user_id != $this->user->id) {
				$logError = 'not owner';
				$this->passError('not_owner','You cannot cancel other people\'s reservations.');
			}
			// if cancel doesn't have enough notice
			elseif (strtotime($dbTimestamp) - time() < CANCEL_AHEAD_OFFSET) {
				$logError = 'not enough notice';
				$this->passError('short_time',
					'Reservations must be canceled more than 24 hours in advance.');
			}
		}

		$schedule = new DO_Schedules();
		$schedule->get($scheduleId);

		// don't allow cancel of past reservations? 
		// (what about the current timeslot that already began?)

		// log what just happened
		if ($this->hasErrors()) {
			$this->logNotice("reservation cancel failure".
				" for {$dbTimestamp} ({$logError})");
		}
		else {
			$schedule->cancelReservation($date,$time);

			$this->logInfo("reservation canceled".
				($this->user->isAdmin() && $reservation->user_id ?
					" for {$cancel_user->username}" : '').
				" for {$dbTimestamp}");

			// turning off email notification til I can fix it
			if ($reservation->user_id) {
				// send notification email
				$cancel_user = new DO_Users();
				$cancel_user->get($reservation->user_id);

				$this->smarty->assign_by_ref('email_user',$cancel_user);
				$this->smarty->assign_by_ref('email_reservation',$reservation);

				$mailer = new SchedulerMailer();
				$email = $cancel_user->getEmail();
				$mailer->AddAddress($email,$cancel_user->getName());
				$mailer->Subject = 'CCA DFAS Reservation Canceled: '.
					date('l, F j',$date).', '.date('g a',$time);
				$mailer->Body = $this->smarty->fetch('emails/cancel_reservation.html');
				$success = $mailer->Send();
				if (!$success) {
					$this->passError('email_error',"Email notification unsuccessful (to {$email}): ".
						"{$mailer->ErrorInfo}");
					$this->logErr("email error during reservation cancel (to {$email}): ".
						"{$mailer->ErrorInfo}");
				}
			}

			$this->passMessage('canceled','Reservation canceled.');
		}
	}
}

$controller = new ReservationController();

?>