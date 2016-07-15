<?php
/* make a timestamp from 2 timestamps: one for date and one for time */
function smarty_function_cancel_link($params, &$smarty) {

    if (empty($params['date']) || empty($params['time']) ||
			empty($params['schedule'])) {
			$smarty->trigger_error("cancel_link: missing parameter");
			return;
		}
		else {
			$date = $params['date'];
			$time = $params['time'];
			$schedule = $params['schedule'];
    }

		return "<a href=\"#\" onclick=\"return adminCancelReservation(".
			"'{$date}','{$time}','{$schedule}');\">cancel</a>";
}
?>
