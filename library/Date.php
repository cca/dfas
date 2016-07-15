<?php

class Date {
	
	function daysInMonth($month, $year) {
		$m = array(31,28,31,30,31,30,31,31,30,31,30,31);
		if ($month != 2) return $m[$month - 1];
		if ($year%4 != 0) return $m[1];
		if ($year%100 == 0 && $year%400 != 0) return $m[1];
		return $m[1] + 1;
	}
	
}

?>