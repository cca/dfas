<?php /* Smarty version 2.6.10, created on 2012-09-28 16:49:47
         compiled from edit_user.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'edit_user.html', 7, false),)), $this); ?>
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

<form method="post" action="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_users',$this->_tpl_vars['user_form']['action']))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="user_form">

<p><label for="username">Username:</label>
<input type="text" name="username" id="username" value="<?php echo $this->_tpl_vars['user_form']['username']; ?>
" size="16" /> @cca.edu</p>

<p><label for="first_name">Name (first, last):</label>
<input type="text" name="first_name" value="<?php echo $this->_tpl_vars['user_form']['first_name']; ?>
" id="first_name" />
<input type="text" name="last_name" value="<?php echo $this->_tpl_vars['user_form']['last_name']; ?>
" id="last_name" /></p>

<p><label for="department">Department:</label>
<select name="department" id="department">
<option value=""></option>
<?php $this->assign('lastGroup', 'Undergraduate'); ?>
<optgroup label="<?php echo $this->_tpl_vars['lastGroup']; ?>
">
<?php $_from = $this->_tpl_vars['departments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['nick'] => $this->_tpl_vars['dept']):
 if ($this->_tpl_vars['dept']['1'] != $this->_tpl_vars['lastGroup']):  $this->assign('lastGroup', $this->_tpl_vars['dept']['1']); ?>
</optgroup>
<optgroup label="<?php echo $this->_tpl_vars['dept']['1']; ?>
">
<?php endif; ?>
<option value="<?php echo $this->_tpl_vars['nick']; ?>
"<?php if ($this->_tpl_vars['nick'] == $this->_tpl_vars['user_form']['department']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['dept']['0']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</optgroup>
</select></p>

<p><label for="active">Active?</label>
<input type="checkbox" name="active" id="active" <?php if ($this->_tpl_vars['user_form']['active']): ?> checked="checked"<?php endif; ?>/> Accounts must be active in order to log in and make reservations.</p>

<p><label for="admin">Admin?</label>
<input type="checkbox" name="admin" id="admin" class="checkbox" <?php if ($this->_tpl_vars['user_form']['admin']): ?> checked="checked"<?php endif; ?>/> Admins have <b>full control</b> over all accounts and schedules.</p>

<p><input type="submit" name="submit" value="Save Changes" id="submit" />
	<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_users','show',$this->_tpl_vars['user_form']['user_id']))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="cancel">cancel</a></p>

<?php if ($this->_tpl_vars['user_form']['user_id']): ?>
<input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['user_form']['user_id']; ?>
" />
<?php endif; ?>
</form>

<?php if ($this->_tpl_vars['user_form']['user_id'] && $this->_tpl_vars['user']->isAdmin() && $this->_tpl_vars['user_form']['user_id'] != $this->_tpl_vars['user']->id): ?>
<form method="post" action="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_users','delete'))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="delete_form" onsubmit="return confirm('Are you sure you want to delete this account?  This cannot be undone.  Deleting this account will also delete any upcoming reservations for this account.');">

<hr />

<p><input type="submit" name="submit" value="Delete this Account" id="submit_delete" /></p>

<input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['user_form']['user_id']; ?>
" />
</form>
<?php endif; ?>

</div>

<div id="sidebar">

</div><!-- /#sidebar -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>