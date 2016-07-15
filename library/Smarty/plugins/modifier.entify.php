<?php

function smarty_modifier_entify($string)
{
	return htmlEncodeText($string);
}

function htmlEncodeText ($string) {
  $pattern = '<([a-zA-Z0-9\.\, "\'_\/\-\+~=;:\(\)?&#%![\]@]+)>';
  preg_match_all ('/' . $pattern . '/', $string, $tagMatches, PREG_SET_ORDER);
  $textMatches = preg_split ('/' . $pattern . '/', $string);

  foreach ($textMatches as $key => $value) {
   $textMatches [$key] = htmlentities ($value,ENT_COMPAT,'UTF-8');
  }

  for ($i = 0; $i < count ($textMatches); $i ++) {
   $textMatches [$i] = $textMatches [$i] . $tagMatches [$i] [0];
  }

  return implode ($textMatches);
}

?>