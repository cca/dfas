<?php
function smarty_function_group_links($params, &$smarty) {

		$groups =& $params['groups'];
		$ctl =& $params['ctl'];
		
		if (count($groups)) {
			$links = array();
			foreach ($groups as $g) {
				$links[] = '<a href="'.htmlspecialchars($ctl->url('manage_groups','edit',$g->id)).
					'">'.$g->name.'</a>';
			}
			return implode(", ",$links);
		}
		else {
			return 'none';
		}
}
?>
