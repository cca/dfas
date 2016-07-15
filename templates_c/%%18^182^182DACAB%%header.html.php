<?php /* Smarty version 2.6.10, created on 2012-09-10 16:44:42
         compiled from layout/header.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'layout/header.html', 23, false),array('modifier', 'default', 'layout/header.html', 59, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>DFAS Scheduler</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="css/scheduler.css" />
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js"></script>
<script type="text/javascript" src="js/dateformat.js"></script>
<script type="text/javascript" src="js/scheduler.js"></script>
<?php if ($this->_tpl_vars['user']->isAdmin()): ?>
<link rel="stylesheet" href="css/admin.css" type="text/css" media="all" />
<script type="text/javascript" src="js/admin.js"></script>
<?php endif; ?>
</head>

<body>

<div id="top-bar"><div class="inner">
  
<div id="ip">Your IP Address is <?php echo $_SERVER['REMOTE_ADDR']; ?>
.</div>
<div id="today">Today's date is <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A, %B %e") : smarty_modifier_date_format($_tmp, "%A, %B %e")); ?>
.</div>

</div></div><!-- /#top-bar -->

<div id="page">

<div id="header">
<a href="http://technology.cca.edu/" id="logo-link"><img src="<?php echo @SITE_URL; ?>
/images/cca-ets.png" alt="CCA Educational Technology Services" width="1026" height="111" border="0" /></a>

<span class="whoAmI">
	<?php if ($this->_tpl_vars['user']->id): ?>
  Logged in as <b><?php echo $this->_tpl_vars['user']->getName(); ?>
</b>
  <?php else: ?>
  You are not logged in.
  <?php endif; ?>
</span>


</div>

<div class="breadcrumb">
  You Are Here: 
  <a href="<?php echo @ETS_URL; ?>
/">ETS Home</a> › 
  <a href="<?php echo @ETS_URL; ?>
/hours" title="">Hours &amp; Facilities</a> › 
  <a href="<?php echo @ETS_URL; ?>
/hours/printing" title="">Print Services</a> › 
  <a href="<?php echo @ETS_URL; ?>
/hours/printing/dfas">DFAS</a> › 
  Scheduler
</div> <!-- /#breadcrumb -->


<div id="content-section">

<?php if ($this->_tpl_vars['user']->isAdmin()):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'layout/admin_nav.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<h1><?php echo ((is_array($_tmp=@$this->_tpl_vars['h1'])) ? $this->_run_mod_handler('default', true, $_tmp, 'LaserCamm Schedule') : smarty_modifier_default($_tmp, 'LaserCamm Schedule')); ?>
</h1>