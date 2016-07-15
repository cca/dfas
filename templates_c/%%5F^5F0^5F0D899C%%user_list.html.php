<?php /* Smarty version 2.6.10, created on 2012-09-18 12:43:34
         compiled from ajax/user_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'ajax/user_list.html', 13, false),)), $this); ?>
<?php if (count ( $this->_tpl_vars['search_users'] )): ?>
<table class="user_list">
<tr>
	<th>Name</th>
	<th>Username</th>
	<th>Department</th>
	<th>Admin?</th>
</tr>
<?php $_from = $this->_tpl_vars['search_users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['suser']):
?>
<tr<?php if (! $this->_tpl_vars['suser']->active): ?> class="inactive"<?php endif; ?>>
	<td>
<?php if ($this->_tpl_vars['user']->isAdmin() || $this->_tpl_vars['suser']->isUser()): ?>
		<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_users','show',$this->_tpl_vars['suser']->id))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo $this->_tpl_vars['suser']->getName(); ?>
</a>
<?php else: ?>
		<?php echo $this->_tpl_vars['suser']->getName(); ?>

<?php endif; ?>
	</td>
	<td><?php echo $this->_tpl_vars['suser']->username; ?>
</td>
	<td><?php echo $this->_tpl_vars['suser']->getDepartment(); ?>
</td>
	<td><?php if ($this->_tpl_vars['suser']->isAdmin()): ?>Admin<?php endif; ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<?php else: ?>
No records matched your search.
<?php endif; ?>