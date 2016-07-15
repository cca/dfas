<?php /* Smarty version 2.6.10, created on 2012-09-19 11:12:24
         compiled from show_user.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'show_user.html', 10, false),array('modifier', 'default', 'show_user.html', 18, false),array('function', 'mailto', 'show_user.html', 10, false),array('function', 'group_links', 'show_user.html', 30, false),)), $this); ?>
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

<table class="show_user">
<tr>
	<th>Username:</th>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['showUser']->username)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 (<?php echo smarty_function_mailto(array('address' => $this->_tpl_vars['showUser']->getEmail()), $this);?>
)</td>
</tr>
<tr>
	<th>Name:</th>
	<td><?php echo $this->_tpl_vars['showUser']->getName(); ?>
</td>
</tr>
<tr>
	<th>Department:</th>
	<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['showUser']->getDepartment())) ? $this->_run_mod_handler('default', true, $_tmp, 'none') : smarty_modifier_default($_tmp, 'none')); ?>
</td>
</tr>
<tr>
	<th>Active?:</th>
	<td><?php if ($this->_tpl_vars['showUser']->active): ?>yes<?php else: ?>no<?php endif; ?></td>
</tr>
<tr>
	<th>Admin?:</th>
	<td><?php if ($this->_tpl_vars['showUser']->isAdmin()): ?>yes<?php else: ?>no<?php endif; ?></td>
</tr>
<tr>
	<th>Groups:</th>
	<td><?php echo smarty_function_group_links(array('groups' => $this->_tpl_vars['showUser']->getGroups(),'ctl' => $this->_tpl_vars['ctl']), $this);?>
</td>
</tr>
</table>

<form method="post" action="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_users','send_account_notification',$this->_tpl_vars['showUser']->id))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="sidebar_form" onsubmit="return confirm('Are you sure you want to send the notification email?')">
	<p>Click the button below to send this user an email with login instructions.</p>
	<p><input type="submit" value="Send Account Notification" /></p>
</form>

<h2>Upcoming Reservations</h2>

<?php if (count ( $this->_tpl_vars['upcomingReservations'] )):  $this->assign('reservations', $this->_tpl_vars['upcomingReservations']);  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'partials/reservation_table.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>
<p>This user has no upcoming reservations.</p>
<?php endif; ?>

<?php if (count ( $this->_tpl_vars['pastReservations'] )): ?>
<h2>Past Reservations</h2>

<?php $this->assign('reservations', $this->_tpl_vars['pastReservations']);  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'partials/reservation_table.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>
<p>This user has no past reservations.</p>
<?php endif; ?>

<script type="text/javascript">
<?php echo '
//<![CDATA[
function confirmMark() {
	return confirm(\'Are you sure you want to mark this reservation as a no-show? \'+
		\'Doing this will open up the slot on the schedule for reservation.\');
}
//]]>
'; ?>

</script>

</div>

<div id="sidebar">

<p class="create_account"><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_users','edit',$this->_tpl_vars['showUser']->id))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">Edit Account &raquo;</a></p>

<p><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_users'))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">Back to list of Accounts</a></p>

</div><!-- /#sidebar -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>