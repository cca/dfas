<?php /* Smarty version 2.6.10, created on 2012-10-03 09:17:34
         compiled from make_reservation.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'make_reservation.html', 8, false),array('modifier', 'date_format', 'make_reservation.html', 9, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="content">

<form action="<?php echo $this->_tpl_vars['ctl']->url('reservation','confirm_make'); ?>
" method="post">

<p>Are you sure you want to make a reservation for:<br />
<b><?php echo ((is_array($_tmp=$this->_tpl_vars['schedule']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</b><br />
<?php echo ((is_array($_tmp=$this->_tpl_vars['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%A, %B %e, %Y') : smarty_modifier_date_format($_tmp, '%A, %B %e, %Y')); ?>
 at <?php echo ((is_array($_tmp=$this->_tpl_vars['time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%l:%M %p') : smarty_modifier_date_format($_tmp, '%l:%M %p')); ?>
?</p>

<p><input type="submit" value="Yes, reserve it." style="margin-right: 20px;" />
<a href="index.php?date=<?php echo $this->_tpl_vars['date']; ?>
&amp;schedule=<?php echo $this->_tpl_vars['schedule']->id; ?>
">No, cancel.</a>
</p>

<input type="hidden" name="schedule" value="<?php echo $this->_tpl_vars['schedule']->id; ?>
" />
<input type="hidden" name="date" value="<?php echo $this->_tpl_vars['date']; ?>
" />
<input type="hidden" name="time" value="<?php echo $this->_tpl_vars['time']; ?>
" />
</form>

</div>

<div id="sidebar">

</div><!-- /#sidebar -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>