<?php /* Smarty version 2.6.10, created on 2012-09-18 12:43:36
         compiled from manage_schedule.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manage_schedule.html', 9, false),array('modifier', 'date_format', 'manage_schedule.html', 17, false),array('function', 'html_options', 'manage_schedule.html', 12, false),array('function', 'math', 'manage_schedule.html', 73, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="content">

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/notify.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="schedule_controls">

<form method="get" action="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_schedule'))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="schedule_switcher">
&nbsp;<select name="schedule" onchange="$('schedule_switcher').submit();">
<option value="">Switch schedule...</option>
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['scheduleOptions']), $this);?>

</select>
</form>

<div id="time_nav">
<span class="thisMonth"><?php echo ((is_array($_tmp=$this->_tpl_vars['firstDate'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%B %Y') : smarty_modifier_date_format($_tmp, '%B %Y')); ?>
</span> |
<a href="manage_schedule.php?month=<?php echo $this->_tpl_vars['previousMonth']; ?>
&amp;year=<?php echo $this->_tpl_vars['previousYear']; ?>
&amp;schedule=<?php echo $this->_tpl_vars['schedule']->id; ?>
">&laquo; previous</a> | 
<a href="manage_schedule.php?month=<?php echo $this->_tpl_vars['nextMonth']; ?>
&amp;year=<?php echo $this->_tpl_vars['nextYear']; ?>
&amp;schedule=<?php echo $this->_tpl_vars['schedule']->id; ?>
">next &raquo;</a>
</div><!-- /#time_nav -->

</div>


<form action="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_schedule','update',$this->_tpl_vars['schedule']->id))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" method="post" id="schedule_form">

<table class="schedule" cellpadding="0" cellspacing="0">
<tr>
<?php $_from = $this->_tpl_vars['dayNames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dayName']):
?>
	<th><?php echo $this->_tpl_vars['dayName']; ?>
</th>
<?php endforeach; endif; unset($_from); ?>
</tr>

<?php $this->assign('dow', 0); ?>
<tr>
<?php unset($this->_sections['startBlanks']);
$this->_sections['startBlanks']['name'] = 'startBlanks';
$this->_sections['startBlanks']['loop'] = is_array($_loop=$this->_tpl_vars['firstDay']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['startBlanks']['show'] = true;
$this->_sections['startBlanks']['max'] = $this->_sections['startBlanks']['loop'];
$this->_sections['startBlanks']['step'] = 1;
$this->_sections['startBlanks']['start'] = $this->_sections['startBlanks']['step'] > 0 ? 0 : $this->_sections['startBlanks']['loop']-1;
if ($this->_sections['startBlanks']['show']) {
    $this->_sections['startBlanks']['total'] = $this->_sections['startBlanks']['loop'];
    if ($this->_sections['startBlanks']['total'] == 0)
        $this->_sections['startBlanks']['show'] = false;
} else
    $this->_sections['startBlanks']['total'] = 0;
if ($this->_sections['startBlanks']['show']):

            for ($this->_sections['startBlanks']['index'] = $this->_sections['startBlanks']['start'], $this->_sections['startBlanks']['iteration'] = 1;
                 $this->_sections['startBlanks']['iteration'] <= $this->_sections['startBlanks']['total'];
                 $this->_sections['startBlanks']['index'] += $this->_sections['startBlanks']['step'], $this->_sections['startBlanks']['iteration']++):
$this->_sections['startBlanks']['rownum'] = $this->_sections['startBlanks']['iteration'];
$this->_sections['startBlanks']['index_prev'] = $this->_sections['startBlanks']['index'] - $this->_sections['startBlanks']['step'];
$this->_sections['startBlanks']['index_next'] = $this->_sections['startBlanks']['index'] + $this->_sections['startBlanks']['step'];
$this->_sections['startBlanks']['first']      = ($this->_sections['startBlanks']['iteration'] == 1);
$this->_sections['startBlanks']['last']       = ($this->_sections['startBlanks']['iteration'] == $this->_sections['startBlanks']['total']);
?>
	<td>&nbsp;</td>
<?php $this->assign('dow', $this->_tpl_vars['dow']+1);  endfor; endif; ?>

<?php unset($this->_sections['dayLoop']);
$this->_sections['dayLoop']['name'] = 'dayLoop';
$this->_sections['dayLoop']['loop'] = is_array($_loop=$this->_tpl_vars['daysInMonth']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dayLoop']['show'] = true;
$this->_sections['dayLoop']['max'] = $this->_sections['dayLoop']['loop'];
$this->_sections['dayLoop']['step'] = 1;
$this->_sections['dayLoop']['start'] = $this->_sections['dayLoop']['step'] > 0 ? 0 : $this->_sections['dayLoop']['loop']-1;
if ($this->_sections['dayLoop']['show']) {
    $this->_sections['dayLoop']['total'] = $this->_sections['dayLoop']['loop'];
    if ($this->_sections['dayLoop']['total'] == 0)
        $this->_sections['dayLoop']['show'] = false;
} else
    $this->_sections['dayLoop']['total'] = 0;
if ($this->_sections['dayLoop']['show']):

            for ($this->_sections['dayLoop']['index'] = $this->_sections['dayLoop']['start'], $this->_sections['dayLoop']['iteration'] = 1;
                 $this->_sections['dayLoop']['iteration'] <= $this->_sections['dayLoop']['total'];
                 $this->_sections['dayLoop']['index'] += $this->_sections['dayLoop']['step'], $this->_sections['dayLoop']['iteration']++):
$this->_sections['dayLoop']['rownum'] = $this->_sections['dayLoop']['iteration'];
$this->_sections['dayLoop']['index_prev'] = $this->_sections['dayLoop']['index'] - $this->_sections['dayLoop']['step'];
$this->_sections['dayLoop']['index_next'] = $this->_sections['dayLoop']['index'] + $this->_sections['dayLoop']['step'];
$this->_sections['dayLoop']['first']      = ($this->_sections['dayLoop']['iteration'] == 1);
$this->_sections['dayLoop']['last']       = ($this->_sections['dayLoop']['iteration'] == $this->_sections['dayLoop']['total']);
 $this->assign('dayOfMonth', $this->_sections['dayLoop']['iteration']);  if ($this->_tpl_vars['scheduleForm']['start_time'][$this->_tpl_vars['dayOfMonth']]):  $this->assign('open', ($this->_tpl_vars['scheduleForm']['open'][$this->_tpl_vars['dayOfMonth']]));  $this->assign('start_time', ($this->_tpl_vars['scheduleForm']['start_time'][$this->_tpl_vars['dayOfMonth']]));  $this->assign('end_time', ($this->_tpl_vars['scheduleForm']['end_time'][$this->_tpl_vars['dayOfMonth']]));  else:  $this->assign('open', ($this->_tpl_vars['defaultsForm']['default_open'][$this->_tpl_vars['dow']]));  $this->assign('start_time', ($this->_tpl_vars['defaultsForm']['default_start_time'][$this->_tpl_vars['dow']]));  $this->assign('end_time', ($this->_tpl_vars['defaultsForm']['default_end_time'][$this->_tpl_vars['dow']]));  endif; ?>
	<td class="<?php if (! $this->_tpl_vars['open']): ?>closed<?php endif;  if ($this->_tpl_vars['passData']['bad_schedule_days'][$this->_tpl_vars['dayOfMonth']]): ?> windowError<?php endif; ?>" id="day_<?php echo $this->_tpl_vars['dayOfMonth']; ?>
"><div class="dayOfMonth">
			<span class="dayOfMonth"><?php echo $this->_tpl_vars['dayOfMonth']; ?>
</span>
			<input type="checkbox" name="open[<?php echo $this->_tpl_vars['dayOfMonth']; ?>
]" id="open_<?php echo $this->_tpl_vars['dayOfMonth']; ?>
" class="checkbox" onclick="toggleScheduleDay(<?php echo $this->_tpl_vars['dayOfMonth']; ?>
);" <?php if ($this->_tpl_vars['open']): ?>checked="checked" <?php endif; ?>/>
		</div>
		<div class="times">
			<select name="start_time[<?php echo $this->_tpl_vars['dayOfMonth']; ?>
]">
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['timeValues'],'selected' => $this->_tpl_vars['start_time']), $this);?>

			</select>
			<select name="end_time[<?php echo $this->_tpl_vars['dayOfMonth']; ?>
]">
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['timeValues'],'selected' => $this->_tpl_vars['end_time']), $this);?>

			</select>
		</div>
		</td>
<?php $this->assign('dow', $this->_tpl_vars['dow']+1);  if ($this->_tpl_vars['dow'] == 7 && $this->_tpl_vars['dayOfMonth'] < $this->_tpl_vars['daysInMonth']):  $this->assign('dow', 0); ?>
</tr>
<tr>
<?php endif;  endfor; endif; ?>

<?php echo smarty_function_math(array('equation' => "7-x",'x' => $this->_tpl_vars['dow'],'assign' => 'endBlankCount'), $this);?>

<?php unset($this->_sections['endBlanks']);
$this->_sections['endBlanks']['name'] = 'endBlanks';
$this->_sections['endBlanks']['loop'] = is_array($_loop=$this->_tpl_vars['endBlankCount']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['endBlanks']['show'] = true;
$this->_sections['endBlanks']['max'] = $this->_sections['endBlanks']['loop'];
$this->_sections['endBlanks']['step'] = 1;
$this->_sections['endBlanks']['start'] = $this->_sections['endBlanks']['step'] > 0 ? 0 : $this->_sections['endBlanks']['loop']-1;
if ($this->_sections['endBlanks']['show']) {
    $this->_sections['endBlanks']['total'] = $this->_sections['endBlanks']['loop'];
    if ($this->_sections['endBlanks']['total'] == 0)
        $this->_sections['endBlanks']['show'] = false;
} else
    $this->_sections['endBlanks']['total'] = 0;
if ($this->_sections['endBlanks']['show']):

            for ($this->_sections['endBlanks']['index'] = $this->_sections['endBlanks']['start'], $this->_sections['endBlanks']['iteration'] = 1;
                 $this->_sections['endBlanks']['iteration'] <= $this->_sections['endBlanks']['total'];
                 $this->_sections['endBlanks']['index'] += $this->_sections['endBlanks']['step'], $this->_sections['endBlanks']['iteration']++):
$this->_sections['endBlanks']['rownum'] = $this->_sections['endBlanks']['iteration'];
$this->_sections['endBlanks']['index_prev'] = $this->_sections['endBlanks']['index'] - $this->_sections['endBlanks']['step'];
$this->_sections['endBlanks']['index_next'] = $this->_sections['endBlanks']['index'] + $this->_sections['endBlanks']['step'];
$this->_sections['endBlanks']['first']      = ($this->_sections['endBlanks']['iteration'] == 1);
$this->_sections['endBlanks']['last']       = ($this->_sections['endBlanks']['iteration'] == $this->_sections['endBlanks']['total']);
?>
	<td>&nbsp;</td>
<?php endfor; endif; ?>
</tr>
</table>

<input type="hidden" name="month" value="<?php echo $this->_tpl_vars['month']; ?>
" />
<input type="hidden" name="year" value="<?php echo $this->_tpl_vars['year']; ?>
" />

<p style="text-align: right;"><input type="submit" value="Save Changes" id="submit" /></p>

</form>

<h2>Schedule Defaults (applies to all months)</h2>

<form action="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_schedule','update_defaults',$this->_tpl_vars['schedule']->id))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" method="post">

<table class="schedule" cellpadding="0" cellspacing="0">
<tr>
<?php $_from = $this->_tpl_vars['dayNames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dayName']):
?>
	<th><?php echo $this->_tpl_vars['dayName']; ?>
</th>
<?php endforeach; endif; unset($_from); ?>
</tr>
<tr>
<?php unset($this->_sections['defaults']);
$this->_sections['defaults']['name'] = 'defaults';
$this->_sections['defaults']['loop'] = is_array($_loop=7) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['defaults']['show'] = true;
$this->_sections['defaults']['max'] = $this->_sections['defaults']['loop'];
$this->_sections['defaults']['step'] = 1;
$this->_sections['defaults']['start'] = $this->_sections['defaults']['step'] > 0 ? 0 : $this->_sections['defaults']['loop']-1;
if ($this->_sections['defaults']['show']) {
    $this->_sections['defaults']['total'] = $this->_sections['defaults']['loop'];
    if ($this->_sections['defaults']['total'] == 0)
        $this->_sections['defaults']['show'] = false;
} else
    $this->_sections['defaults']['total'] = 0;
if ($this->_sections['defaults']['show']):

            for ($this->_sections['defaults']['index'] = $this->_sections['defaults']['start'], $this->_sections['defaults']['iteration'] = 1;
                 $this->_sections['defaults']['iteration'] <= $this->_sections['defaults']['total'];
                 $this->_sections['defaults']['index'] += $this->_sections['defaults']['step'], $this->_sections['defaults']['iteration']++):
$this->_sections['defaults']['rownum'] = $this->_sections['defaults']['iteration'];
$this->_sections['defaults']['index_prev'] = $this->_sections['defaults']['index'] - $this->_sections['defaults']['step'];
$this->_sections['defaults']['index_next'] = $this->_sections['defaults']['index'] + $this->_sections['defaults']['step'];
$this->_sections['defaults']['first']      = ($this->_sections['defaults']['iteration'] == 1);
$this->_sections['defaults']['last']       = ($this->_sections['defaults']['iteration'] == $this->_sections['defaults']['total']);
 $this->assign('dow', $this->_sections['defaults']['index']);  $this->assign('open', ($this->_tpl_vars['defaultsForm']['default_open'][$this->_tpl_vars['dow']]));  $this->assign('start_time', ($this->_tpl_vars['defaultsForm']['default_start_time'][$this->_tpl_vars['dow']]));  $this->assign('end_time', ($this->_tpl_vars['defaultsForm']['default_end_time'][$this->_tpl_vars['dow']])); ?>
	<td class="<?php if (! $this->_tpl_vars['open']): ?>closed<?php endif;  if ($this->_tpl_vars['passData']['bad_defaults'][$this->_tpl_vars['dow']] || $this->_tpl_vars['reservedDefaults'][$this->_tpl_vars['dow']]): ?> windowError<?php endif; ?>" id="default_day_<?php echo $this->_tpl_vars['dow']; ?>
"><div class="dayOfMonth">
		<span class="dayOfMonth">Default</span>
			<input type="checkbox" name="default_open[<?php echo $this->_tpl_vars['dow']; ?>
]" id="default_open_<?php echo $this->_tpl_vars['dow']; ?>
" class="checkbox" onclick="toggleDefaultDay(<?php echo $this->_tpl_vars['dow']; ?>
);" <?php if ($this->_tpl_vars['open']): ?>checked="checked" <?php endif; ?>/>
		</div>
		<div class="times">
			<select name="default_start_time[<?php echo $this->_tpl_vars['dow']; ?>
]">
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['timeValues'],'selected' => $this->_tpl_vars['start_time']), $this);?>

			</select>
			<select name="default_end_time[<?php echo $this->_tpl_vars['dow']; ?>
]">
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['timeValues'],'selected' => $this->_tpl_vars['end_time']), $this);?>

			</select>
		</div>
		</td>
<?php endfor; endif; ?>
</tr>

</table>

<p style="text-align: right;"><input type="submit" value="Save Defaults" id="submit_defaults" /></p>

</form>

</div><!-- /#content -->

<div id="sidebar">

<h2>Manage the Schedule</h2>

<p>For each day, select the opening time from the first dropdown, and the closing time from the second dropdown.</p>

<p>If the studio is closed on a given day, uncheck the checkbox for that day.</p>

<p>When you are finished, click "Save Changes".</p>

</div><!-- /#sidebar -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>