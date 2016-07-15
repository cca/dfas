<?php
/* make a timestamp from 2 timestamps: one for date and one for time */
function smarty_function_reservation_link($params, &$smarty) {

    if (empty($params['date']) || empty($params['time']) ||
			empty($params['schedule']) || empty($params['user'])) {
			$smarty->trigger_error("make_timestamp: missing parameter");
			return;
		}
		else {
			$date = $params['date'];
			$time = $params['time'];
			$schedule = $params['schedule'];
			$user =& $params['user'];
    }

		if ($user->isAdmin()) {
			return "<a href=\"#\" onclick=\"return adminMakeReservation(".
				"'{$date}','{$time}','{$schedule}');\">Available</a>";
		}
		else {
			return "<a href=\"reservation.php?action=make&amp;date={$date}&amp;".
				"time={$time}&amp;schedule={$schedule}\">Available</a>";
		}
}
?>
