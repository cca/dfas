<?php /* Smarty version 2.6.10, created on 2012-09-18 12:43:37
         compiled from show_report.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'string_format', 'show_report.html', 20, false),array('modifier', 'escape', 'show_report.html', 71, false),array('function', 'html_select_date', 'show_report.html', 63, false),)), $this); ?>
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

<?php if ($this->_tpl_vars['title']): ?>
<p><?php echo $this->_tpl_vars['title']; ?>
</p>
<?php endif; ?>

<?php if ($this->_tpl_vars['reservationCount']): ?>
<table class="report" cellspacing="0">
<tr>
	<th>Department</th>
	<th>Reservations</th>
	<th>Users</th>
</tr>
<?php $_from = $this->_tpl_vars['deptReservations']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['r']):
 $this->assign('deptNick', $this->_tpl_vars['r']->department);  $this->assign('percent', ((is_array($_tmp=$this->_tpl_vars['r']->reservations/$this->_tpl_vars['reservationCount']*100)) ? $this->_run_mod_handler('string_format', true, $_tmp, '%d') : smarty_modifier_string_format($_tmp, '%d')));  if ($this->_tpl_vars['percent'] == 0):  $this->assign('percent', '&lt;1');  endif; ?>
<tr>
	<td><?php echo $this->_tpl_vars['departments'][$this->_tpl_vars['deptNick']]['0']; ?>
</td>
	<td><?php echo $this->_tpl_vars['r']->reservations; ?>
 <span class="small">(<?php echo $this->_tpl_vars['percent']; ?>
%)</span></td>
	<td><?php echo $this->_tpl_vars['r']->users; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from);  $this->assign('percent', ((is_array($_tmp=$this->_tpl_vars['nonDeptCount']/$this->_tpl_vars['reservationCount']*100)) ? $this->_run_mod_handler('string_format', true, $_tmp, '%d') : smarty_modifier_string_format($_tmp, '%d'))); ?>
<tr>
	<td><i>no department</i></td>
	<td><?php echo $this->_tpl_vars['nonDeptCount']; ?>
 <span class="small">(<?php echo $this->_tpl_vars['percent']; ?>
%)</span></td>
</tr>
<tr>
	<td><b>Total</b></td>
	<td><b><?php echo $this->_tpl_vars['reservationCount']; ?>
</b></td>
	<td><b><?php echo $this->_tpl_vars['userCount']; ?>
</b></td>
</table>

<h2>Legend</h2>

<div class="legend">

<dl>
	<dt>Reservations</dt><dd>The number of reservations made by users in a department during the specified time period.</dd>
	<dt>Users</dt><dd>The number of users in a department that made reservations during the specified time period.</dd>
</div>

<?php else: ?>
<p>No reservations for the values selected.</p>
<?php endif; ?>

</div>

<div id="sidebar">

<h2>Generate a Report</h2>

<form action="reports.php" method="get" class="sidebar_form">

<p>From:<br />
<?php echo smarty_function_html_select_date(array('prefix' => 'start','month_format' => '%b','day_value_format' => '%02d','time' => $this->_tpl_vars['startDate']), $this);?>
</p>

<p>To:<br />
<?php echo smarty_function_html_select_date(array('prefix' => 'end','month_format' => '%b','day_value_format' => '%02d','time' => $this->_tpl_vars['endDate']), $this);?>

</p>

<ul>
<?php $_from = $this->_tpl_vars['scheduleOptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sid'] => $this->_tpl_vars['sname']):
?>
<li><input type="checkbox" name="schedules[<?php echo $this->_tpl_vars['sid']; ?>
]" id="schedules_<?php echo $this->_tpl_vars['sid']; ?>
" <?php if (in_array ( $this->_tpl_vars['sid'] , $this->_tpl_vars['scheduleIds'] )): ?> checked="checked"<?php endif; ?>/> <label for="schedules_<?php echo $this->_tpl_vars['sid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['sname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label></li>
<?php endforeach; endif; unset($_from); ?>
</ul>

<p><input type="submit" name="submit" value="Generate" id="submit" /></p>

</form>

<p>Use the form above to generate a report that shows the breakdown of reservations by department, given for the date range you specify.</p>

</div><!-- /#sidebar -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>