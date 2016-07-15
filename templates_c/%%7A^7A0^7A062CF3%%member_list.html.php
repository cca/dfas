<?php /* Smarty version 2.6.10, created on 2012-09-28 16:50:22
         compiled from ajax/member_list.html */ ?>
<table class="user_list">
<tr>
	<th>Member Name</th>
	<th>Department</th>
	<th>&nbsp;</th>
</tr>
<?php $_from = $this->_tpl_vars['members']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
<tr<?php if ($this->_tpl_vars['m']->id == $this->_tpl_vars['addedUserId']): ?> class="highlight"<?php endif; ?>>
	<td><?php echo $this->_tpl_vars['m']->getName(); ?>
</td>
	<td><?php echo $this->_tpl_vars['m']->getDepartment(); ?>
</td>
	<td><a href="#" onclick="return removeMember(<?php echo $this->_tpl_vars['group']->id; ?>
,<?php echo $this->_tpl_vars['m']->id; ?>
);">remove</a></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>