<?php /* Smarty version 2.6.10, created on 2012-10-02 16:50:04
         compiled from sidebars/user_sidebar.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'sidebars/user_sidebar.html', 11, false),array('modifier', 'strtotime', 'sidebars/user_sidebar.html', 13, false),array('modifier', 'escape', 'sidebars/user_sidebar.html', 29, false),array('function', 'make_timestamp', 'sidebars/user_sidebar.html', 13, false),)), $this); ?>
<h2>Make a Reservation</h2>

<p>Click a link that reads "Available" on the schedule for the day and time that you would like to reserve.</p>

<?php $this->assign('upcomingReservations', $this->_tpl_vars['user']->getUpcomingReservations($this->_tpl_vars['schedule']->id));  if (count ( $this->_tpl_vars['upcomingReservations'] )): ?>
<h2>Your Upcoming Reservations</h2>

<ul class="myReservations">
<?php $_from = $this->_tpl_vars['upcomingReservations']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ur']):
?>
<li><?php echo ((is_array($_tmp=$this->_tpl_vars['ur']->date)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%A, %B %e, %Y') : smarty_modifier_date_format($_tmp, '%A, %B %e, %Y')); ?>
<br />
	<?php echo ((is_array($_tmp=$this->_tpl_vars['ur']->start_time)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%l:%M %p') : smarty_modifier_date_format($_tmp, '%l:%M %p')); ?>
 
<?php echo smarty_function_make_timestamp(array('date' => ((is_array($_tmp=$this->_tpl_vars['ur']->date)) ? $this->_run_mod_handler('strtotime', true, $_tmp) : strtotime($_tmp)),'time' => ((is_array($_tmp=$this->_tpl_vars['ur']->start_time)) ? $this->_run_mod_handler('strtotime', true, $_tmp) : strtotime($_tmp)),'assign' => 'thisStamp'), $this);?>

<?php if ($this->_tpl_vars['thisStamp']-time() >= @CANCEL_AHEAD_OFFSET): ?>
	<a href="reservation.php?action=cancel&amp;date=<?php echo ((is_array($_tmp=$this->_tpl_vars['ur']->date)) ? $this->_run_mod_handler('strtotime', true, $_tmp) : strtotime($_tmp)); ?>
&amp;time=<?php echo ((is_array($_tmp=$this->_tpl_vars['ur']->start_time)) ? $this->_run_mod_handler('strtotime', true, $_tmp) : strtotime($_tmp)); ?>
&amp;schedule=<?php echo $this->_tpl_vars['ur']->schedule_id; ?>
">cancel</a>
<?php endif; ?>
</li>
<?php endforeach; endif; unset($_from); ?>
</ul>

<p class="note">Reservations cannot be cancelled with less than 24 hours notice.</p>
<?php endif; ?>

<h2>About You</h2>

<p>Your department is listed as <b><?php echo $this->_tpl_vars['user']->getDepartment(); ?>
</b>. If this is incorrect, please <a href="mailto:<?php echo @HELP_EMAIL; ?>
">let us know</a>.</p>

<?php if (! $this->_tpl_vars['user']->isAdmin()): ?>
<p class="logout"><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('authentication','logout'))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">Log out &raquo;</a></p>
<?php endif; ?>