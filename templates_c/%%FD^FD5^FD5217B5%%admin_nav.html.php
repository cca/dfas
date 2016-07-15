<?php /* Smarty version 2.6.10, created on 2012-09-18 12:42:55
         compiled from layout/admin_nav.html */ ?>
<ul id="adminNav">
	<li><a href="index.php"<?php if ($this->_tpl_vars['adminNavSelected'] == 'reservations'): ?> class="selected"<?php endif; ?>>Reservations</a></li>
	<li><a href="manage_users.php"<?php if ($this->_tpl_vars['adminNavSelected'] == 'users'): ?> class="selected"<?php endif; ?>>Accounts</a></li>
	<li><a href="manage_groups.php" <?php if ($this->_tpl_vars['adminNavSelected'] == 'groups'): ?> class="selected"<?php endif; ?>>Groups</a></li>
<?php if ($this->_tpl_vars['user']->isAdmin()): ?>
	<li><a href="manage_schedule.php"<?php if ($this->_tpl_vars['adminNavSelected'] == 'schedule'): ?> class="selected"<?php endif; ?>>Schedules</a></li>
	<li><a href="reports.php"<?php if ($this->_tpl_vars['adminNavSelected'] == 'reports'): ?> class="selected"<?php endif; ?>>Reports</a></li>
	<li><a href="logs.php"<?php if ($this->_tpl_vars['adminNavSelected'] == 'log'): ?> class="selected"<?php endif; ?>>Log</a></li>
<?php endif; ?>
	<li class="logout"><a href="<?php echo $this->_tpl_vars['ctl']->url('authentication','logout'); ?>
">Logout</a></li>
</ul>