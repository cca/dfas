<?php
/* make a timestamp from 2 timestamps: one for date and one for time */
function smarty_function_make_timestamp($params, &$smarty) {
    $extra = '';

    if (empty($params['date'])) {
			$smarty->trigger_error("make_timestamp: missing 'date' parameter");
			return;
    }
		elseif (empty($params['time'])) {
			$smarty->trigger_error("make_timestamp: missing 'time' parameter");
			return;
		}
		else {
			$date = $params['date'];
			$time = $params['time'];
    }

		$stamp = strtotime(date('Y-m-d',$date).' '.date('H:i:s',$time));

    if (empty($params['assign'])) {
        return $stamp;
    } else {
        $smarty->assign($params['assign'],$stamp);
    }
}
?>
