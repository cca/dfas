<?php /* Smarty version 2.6.10, created on 2012-09-18 12:43:35
         compiled from manage_groups.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manage_groups.html', 16, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="content">

<div id="search_results">

<?php if (count ( $this->_tpl_vars['usergroups'] )): ?>
<table class="user_list">
<tr>
	<th>Group</th>
	<th>Member Access</th>
</tr>
<?php $_from = $this->_tpl_vars['usergroups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group']):
 $this->assign('rules', $this->_tpl_vars['group']->getRules()); ?>
<tr>
	<td><a href="manage_groups.php?action=edit&amp;id=<?php echo $this->_tpl_vars['group']->id; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['group']->name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
	<td>
<?php $_from = $this->_tpl_vars['rules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rule']):
?>
		<?php echo $this->_tpl_vars['rule']->getDescription(); ?>
.
<?php endforeach; endif; unset($_from); ?>
	</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<?php else: ?>
No records matched your search.
<?php endif; ?>

</div>

</div>

<div id="sidebar">

</div><!-- /#sidebar -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>