<?php /* Smarty version 2.6.10, created on 2012-09-19 11:40:23
         compiled from ajax/user_for_reservation.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'ajax/user_for_reservation.html', 5, false),array('modifier', 'escape', 'ajax/user_for_reservation.html', 8, false),)), $this); ?>
<?php if (! $this->_tpl_vars['search_user']->id): ?>
<i>No user selected.</i>
<?php else: ?>
<b><?php echo $this->_tpl_vars['search_user']->getName(); ?>
</b> (<?php echo $this->_tpl_vars['search_user']->username; ?>
)<br />
Dept: <?php echo ((is_array($_tmp=@$this->_tpl_vars['search_user']->getDepartment())) ? $this->_run_mod_handler('default', true, $_tmp, '<i>none</i>') : smarty_modifier_default($_tmp, '<i>none</i>')); ?>
<br />

<input type="hidden" id="reserve_user_id" value="<?php echo $this->_tpl_vars['search_user']->id; ?>
" />
<input type="hidden" id="reserve_user_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_user']->getName())) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php endif; ?>