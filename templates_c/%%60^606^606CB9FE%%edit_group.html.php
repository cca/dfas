<?php /* Smarty version 2.6.10, created on 2012-09-28 16:50:22
         compiled from edit_group.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="content">

<?php if (count ( $this->_tpl_vars['error'] )): ?>
<div class="error">
<?php if ($this->_tpl_vars['error']['no_username']): ?>Username is empty.<br /><?php endif;  if ($this->_tpl_vars['error']['no_first_name']): ?>First Name is empty.<br /><?php endif;  if ($this->_tpl_vars['error']['no_last_name']): ?>Last Name is empty.<br /><?php endif;  if ($this->_tpl_vars['error']['no_department']): ?>No Department specified.<br /><?php endif;  if ($this->_tpl_vars['error']['username_exists']): ?>That username is already in use.<br /><?php endif;  if ($this->_tpl_vars['error']['non_user']): ?>You are not allowed to create or edit non-user accounts.<br /><?php endif; ?>
</div>
<?php endif; ?>

<div id="member_list" class="content_wrapper">
	
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'ajax/member_list.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</div><!-- /#member_list -->

</div>

<div id="sidebar">

<h2>Add a Member</h2>

<p>Start typing the name or username of a user, then select it from the dropdown list that appears.</p>

<div class="sidebar_form">

<p><label for="member_search">Name/Username:</label>
<input type="text" name="member_search" value="" id="member_search" /></p>
<div id="member_info" class="choice_box"></div>
</div>

<script type="text/javascript">
//<![CDATA[
var groupId = <?php echo $this->_tpl_vars['group']->id; ?>
;
new Ajax.Autocompleter('member_search','member_info','<?php echo $this->_tpl_vars['ctl']->url('manage_groups','ajax_member_search'); ?>
',<?php echo '
	{
		afterUpdateElement: function(input, item) {
			input.value = \'\';
			'; ?>

			new Ajax.Updater('member_list','<?php echo $this->_tpl_vars['ctl']->url('manage_groups','ajax_add_member'); ?>
',<?php echo '{
				parameters: {
					group_id: groupId,
					user_id: item.getElementsBySelector(\'span.id\')[0].innerHTML
				}
			});
		}
	});

function removeMember(gId, uId) {
	'; ?>

	new Ajax.Updater('member_list','<?php echo $this->_tpl_vars['ctl']->url('manage_groups','ajax_remove_member'); ?>
',<?php echo '{
		parameters: {
			group_id: gId,
			user_id: uId
		}
	});
	return false;
}

'; ?>
	
//]]>
</script>

<h2>Member Access</h2>

<?php if (count ( $this->_tpl_vars['rules'] )): ?>
<ul class="myReservations">
<?php $_from = $this->_tpl_vars['rules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rule']):
?>
<li><?php echo $this->_tpl_vars['rule']->getDescription(); ?>
</li>
<?php endforeach; endif; unset($_from); ?>
</ul>
<?php else: ?>
<p>No access has been set up for this group.</p>
<?php endif; ?>

</div><!-- /#sidebar -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>