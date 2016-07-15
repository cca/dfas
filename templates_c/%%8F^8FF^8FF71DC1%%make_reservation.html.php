<?php /* Smarty version 2.6.10, created on 2012-09-19 11:40:28
         compiled from emails/make_reservation.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'emails/make_reservation.html', 7, false),)), $this); ?>
<?php $this->assign('schedule', $this->_tpl_vars['email_reservation']->getSchedule()); ?>
Hi <?php echo $this->_tpl_vars['email_user']->first_name; ?>
,

This is an automated message to let you know that you have a reservation at the Digital Fine Art Studio for:

<?php echo $this->_tpl_vars['schedule']->name; ?>

<?php echo ((is_array($_tmp=$this->_tpl_vars['email_reservation']->date)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%A, %B %e, %Y') : smarty_modifier_date_format($_tmp, '%A, %B %e, %Y')); ?>

<?php echo ((is_array($_tmp=$this->_tpl_vars['email_reservation']->start_time)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%l:%M %p') : smarty_modifier_date_format($_tmp, '%l:%M %p')); ?>


This reservation is for **1 hour**.

Thanks,
CCA Scheduler