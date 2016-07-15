<?php /* Smarty version 2.6.10, created on 2012-09-18 12:43:34
         compiled from manage_users.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manage_users.html', 17, false),)), $this); ?>
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

<div id="search_results">

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'ajax/user_list.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	

</div>

</div>

<div id="sidebar">

<p class="create_account"><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('manage_users','init'))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">Add an Account &raquo;</a></p>

<h2>Search Accounts</h2>

<form id="search_form" onsubmit="return searchUsers();" action="">

<p><label for="search_name">Name/Username:</label><br />
<input type="text" name="search_name" value="" id="search_name" /></p>

<p><label for="search_department">Department:</label><br />
<select name="search_department" id="search_department">
<option value="">&#8211; any department &#8211;</option>
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
"><?php echo $this->_tpl_vars['dept']['0']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</optgroup>
</select></p>

<p><label for="search_admin">Admin?</label><br />
<select name="search_admin" id="search_admin">
<option value="">&#8211; either &#8211;</option>
<option value="yes">yes</option>
<option value="no">no</option>
</select></p>

<p><label for="search_active">Active?</label><br />
<select name="search_active" id="search_active">
<option value="">&#8211; either &#8211;</option>
<option value="yes">yes</option>
<option value="no">no</option>
</select></p>

<p><input type="submit" value="Search" /></p>

</form>

<script type="text/javascript">
//<![CDATA[
<?php echo '
function searchUsers() {
	new Ajax.Updater(\'search_results\',
		'; ?>
'<?php echo $this->_tpl_vars['ctl']->url('manage_users','ajax_user_search'); ?>
'<?php echo ',
		{
			parameters: Form.serialize(\'search_form\')
		}); 
	return false;
}
'; ?>

//]]>
</script>
</div><!-- /#sidebar -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>