<?php

function smarty_modifier_utfescape($string)
{
	return htmlspecialchars($string,ENT_COMPAT,'UTF-8');
}

?>