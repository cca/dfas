<?php
require_once 'Smarty/plugins/modifier.markdown.php';
require_once 'Smarty/plugins/modifier.smartypants.php';

function smarty_modifier_mymarkdown($text, $lite = false) {
	if ($text) {
		$temp = smarty_modifier_markdown($text);
		if ($lite) {
			$temp = preg_replace('/^<p>/','',$temp);
			$temp = preg_replace('/<\/p>$/','',$temp);
			$temp = trim($temp);
		}
		return smarty_modifier_smartypants($temp);
	}
}

?>