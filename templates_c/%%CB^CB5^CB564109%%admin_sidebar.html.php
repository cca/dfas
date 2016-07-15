<?php /* Smarty version 2.6.10, created on 2012-09-18 12:42:55
         compiled from sidebars/admin_sidebar.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'sidebars/admin_sidebar.html', 20, false),)), $this); ?>
<h2>Make a Reservation</h2>

<p>Fill out the form, then click an "Available" link.</p>

<p><label for="reserve_mode"><b>Reservation for:</b></label><br />
	<select id="reserve_mode" onchange="changeReserveMode();">
	<option value="user">Account holder</option>
	<option value="other">Orientation, class, other</option>
</select></p>

<div id="user_reserve_mode">
	
<p><label for="user_input">Name/Username:</label><br />
	<input type="text" name="user_input" value="" id="user_input" /></p>

<div id="user_info"><i>No user selected.</i></div>

<script type="text/javascript">
//<![CDATA[
var ajaxURL = '<?php echo ((is_array($_tmp=$this->_tpl_vars['ctl']->url('index','ajax_user_for_reservation'))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
';
<?php echo '
changeReserveMode = function() {
	if ($(\'reserve_mode\').value == \'user\') {
		$(\'user_reserve_mode\').style.display = \'block\';
		$(\'other_reserve_mode\').style.display = \'none\';
	}
	else {
		$(\'user_reserve_mode\').style.display = \'none\';
		$(\'other_reserve_mode\').style.display = \'block\';
	}
}

new Form.Element.Observer(\'user_input\',0.2,
	function(el,value) {
		new Ajax.Updater(\'user_info\',ajaxURL,
			{parameters: {user_input: value}});
	});
'; ?>

//]]>
</script>

</div><!-- /#user_reserve_mode -->


<div id="other_reserve_mode" style="display: none;">
<p><label for="other_input">Label:</label><br />
	<input type="text" name="other_input" value="" id="other_input" /></p>
</div><!-- /#other_reserve_mode -->
	
	
<p><b>Account holder:</b> Make a reservation for someone with an account.  The account holder will receive an email notification.</p>

<p><b>Orientation, class, other:</b> Block off time on the schedule, for any purpose.  No notifications are sent.</p>