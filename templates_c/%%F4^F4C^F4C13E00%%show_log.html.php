<?php /* Smarty version 2.6.10, created on 2012-09-18 12:43:38
         compiled from show_log.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'show_log.html', 8, false),array('function', 'html_select_date', 'show_log.html', 40, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="content">

<?php if (count ( $this->_tpl_vars['entries'] )): ?>
<table class="log" cellspacing="0">
<?php $_from = $this->_tpl_vars['entries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['entry']):
 $this->assign('thisDate', ((is_array($_tmp=$this->_tpl_vars['entry']->logtime)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%B %e,<br />%Y') : smarty_modifier_date_format($_tmp, '%B %e,<br />%Y')));  if ($this->_tpl_vars['thisDate'] != $this->_tpl_vars['lastDate']):  $this->assign('lastDate', $this->_tpl_vars['thisDate']); ?>
<tr class="newDate">
	<td class="date"><?php echo $this->_tpl_vars['thisDate']; ?>
</td>
<?php else: ?>
<tr>
	<td></td>
<?php endif; ?>
	<td class="time"><?php echo ((is_array($_tmp=$this->_tpl_vars['entry']->logtime)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%l:%M&nbsp;%p') : smarty_modifier_date_format($_tmp, '%l:%M&nbsp;%p')); ?>
</td>
<?php if ($this->_tpl_vars['entry']->priority == @PEAR_LOG_INFO):  $this->assign('messageClass', 'info');  elseif ($this->_tpl_vars['entry']->priority == @PEAR_LOG_NOTICE):  $this->assign('messageClass', 'notice');  else:  $this->assign('messageClass', 'warning');  endif; ?>
	<td class="<?php echo $this->_tpl_vars['messageClass']; ?>
 message"><?php echo $this->_tpl_vars['entry']->message; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<?php endif; ?>
	
</div>

<div id="sidebar">

<h2>View System Log</h2>

<form action="logs.php" method="get" class="sidebar_form">

<p>From:<br />
<?php echo smarty_function_html_select_date(array('prefix' => 'start','month_format' => '%b','day_value_format' => '%02d','time' => $this->_tpl_vars['startDate']), $this);?>

</p>

<p>To:<br />
<?php echo smarty_function_html_select_date(array('prefix' => 'end','month_format' => '%b','day_value_format' => '%02d','time' => $this->_tpl_vars['endDate']), $this);?>

</p>

<p>Search:<br />
<input type="text" name="search" value="<?php echo $this->_tpl_vars['logForm']['search']; ?>
" /></p>

<p><input type="submit" name="submit" value="Generate" id="submit" /></p>

</form>

<p>Use the form above to browse the system log for a date range you specify.</p>

<p>You may also search the text of the log using the "Search" box. For instance, you can search for activity related to a specific user by searching for that user's username.</p>

<p>The username at the beginning of each entry is the user that caused the action.</p>

</div><!-- /#sidebar -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>