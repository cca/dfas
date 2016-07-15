<?php /* Smarty version 2.6.10, created on 2012-09-19 11:12:24
         compiled from partials/reservation_table.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'partials/reservation_table.html', 5, false),array('modifier', 'escape', 'partials/reservation_table.html', 8, false),)), $this); ?>
<table class="reservations">
<?php $_from = $this->_tpl_vars['reservations']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['r']):
 $this->assign('scheduleId', $this->_tpl_vars['r']->schedule_id); ?>
<tr<?php if ($this->_tpl_vars['r']->id == $this->_tpl_vars['passData']['highlightId']): ?> class="highlight"<?php elseif ($this->_tpl_vars['r']->no_show): ?> class="no_show"<?php endif; ?>>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['r']->date)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%B %e, %Y') : smarty_modifier_date_format($_tmp, '%B %e, %Y')); ?>
</td>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['r']->start_time)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%l:%M %p') : smarty_modifier_date_format($_tmp, '%l:%M %p')); ?>
</td>
	<td><?php echo $this->_tpl_vars['scheduleNames'][$this->_tpl_vars['scheduleId']]; ?>
</td>
	<td><?php if ($this->_tpl_vars['r']->no_show): ?><b>no-show</b> <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_users','undo_noshow',$this->_tpl_vars['r']->id))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="little_button">undo</a><?php else: ?><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_users','mark_noshow',$this->_tpl_vars['r']->id))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="return confirmMark();" class="little_button">mark no-show</a><?php endif; ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>