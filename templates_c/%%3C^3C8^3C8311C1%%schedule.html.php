<?php /* Smarty version 2.6.10, created on 2012-09-10 16:44:43
         compiled from schedule.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'schedule.html', 30, false),array('function', 'reservation_link', 'schedule.html', 61, false),array('function', 'cancel_link', 'schedule.html', 64, false),array('modifier', 'date_format', 'schedule.html', 46, false),)), $this); ?>
<?php echo '
<script type="text/javascript">
//<![CDATA[
toggleScheduleControls = function() {
	$(\'schedule_controls\').hide();
	$(\'schedule_loading\').show();
};
showSchedule = function(date,scheduleId) {
	new Ajax.Updater(\'schedule_wrapper\',\'index.php\',{parameters: {
		action: \'ajax_show_schedule\',
		date: date,
		schedule: scheduleId
	}});
	toggleScheduleControls();
	return false;
}
//]]>
</script>
'; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/notify.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="schedule_loading" style="display: none;"><img src="images/spinner.gif" alt="Loading..." /> Loading...</div>

<div id="schedule_controls">

<form method="get" action="index.php" id="schedule_switcher">
&nbsp;<select name="schedule" onchange="$('schedule_switcher').submit();">
<option value="">Switch schedule...</option>
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['scheduleOptions']), $this);?>

</select>
</form>

<div id="time_nav">
<a href="index.php?date=<?php echo $this->_tpl_vars['schedule']->previous_stamp; ?>
&amp;schedule=<?php echo $this->_tpl_vars['schedule']->id; ?>
" onclick="return showSchedule('<?php echo $this->_tpl_vars['schedule']->previous_stamp; ?>
',<?php echo $this->_tpl_vars['schedule']->id; ?>
);">&laquo; previous</a> | 
<a href="index.php?schedule=<?php echo $this->_tpl_vars['schedule']->id; ?>
" onclick="return showSchedule('',<?php echo $this->_tpl_vars['schedule']->id; ?>
);">this week</a> |
<a href="index.php?date=<?php echo $this->_tpl_vars['schedule']->next_stamp; ?>
&amp;schedule=<?php echo $this->_tpl_vars['schedule']->id; ?>
" onclick="return showSchedule('<?php echo $this->_tpl_vars['schedule']->next_stamp; ?>
',<?php echo $this->_tpl_vars['schedule']->id; ?>
);">next &raquo;</a>
</div><!-- /#time_nav -->

</div>


<table class="schedule" cellpadding="0" cellspacing="0">
<tr>
<?php $_from = $this->_tpl_vars['schedule']->day_stamps; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['stamp']):
?>
	<th><?php echo ((is_array($_tmp=$this->_tpl_vars['stamp'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%A') : smarty_modifier_date_format($_tmp, '%A')); ?>
<br />
		<?php echo ((is_array($_tmp=$this->_tpl_vars['stamp'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%b %e %y') : smarty_modifier_date_format($_tmp, '%b %e %y')); ?>
</th>
<?php endforeach; endif; unset($_from); ?>
</tr>

<?php $this->assign('nowStamp', time());  $_from = $this->_tpl_vars['schedule']->time_stamps; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['time']):
?>
<tr>
<?php $_from = $this->_tpl_vars['schedule']->day_stamps; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['day']):
 $this->assign('cell', $this->_tpl_vars['cells'][$this->_tpl_vars['time']][$this->_tpl_vars['day']]); ?>
<td<?php if ($this->_tpl_vars['cell']['td_class']): ?> class="<?php echo $this->_tpl_vars['cell']['td_class']; ?>
"<?php endif; ?>>
	<div class="time"><?php echo ((is_array($_tmp=$this->_tpl_vars['time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%l:%M %p') : smarty_modifier_date_format($_tmp, '%l:%M %p')); ?>
</div>
<?php if ($this->_tpl_vars['cell']['div_class']): ?>
	<div class="<?php echo $this->_tpl_vars['cell']['div_class']; ?>
">
<?php if ($this->_tpl_vars['cell']['can_reserve']):  echo smarty_function_reservation_link(array('date' => $this->_tpl_vars['day'],'time' => $this->_tpl_vars['time'],'schedule' => $this->_tpl_vars['schedule']->id,'user' => $this->_tpl_vars['user']), $this);?>

<?php else: ?>
		<?php echo $this->_tpl_vars['cell']['label'];  if ($this->_tpl_vars['cell']['reserved'] && $this->_tpl_vars['user']->isAdmin() && $this->_tpl_vars['cell']['stamp'] > $this->_tpl_vars['nowStamp']): ?><br />
<?php echo smarty_function_cancel_link(array('date' => $this->_tpl_vars['day'],'time' => $this->_tpl_vars['time'],'schedule' => $this->_tpl_vars['schedule']->id), $this); endif;  endif; ?>
	</div>
<?php endif; ?>
</td>
<?php endforeach; endif; unset($_from); ?>
</tr>
<?php endforeach; endif; unset($_from); ?>

<tr>
<?php $_from = $this->_tpl_vars['schedule']->day_stamps; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['stamp']):
?>
	<th><?php echo ((is_array($_tmp=$this->_tpl_vars['stamp'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%A') : smarty_modifier_date_format($_tmp, '%A')); ?>
<br />
		<?php echo ((is_array($_tmp=$this->_tpl_vars['stamp'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%b %e %y') : smarty_modifier_date_format($_tmp, '%b %e %y')); ?>
</th>
<?php endforeach; endif; unset($_from); ?>
</tr>
</table>
