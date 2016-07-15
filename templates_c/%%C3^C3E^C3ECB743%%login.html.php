<?php /* Smarty version 2.6.10, created on 2012-09-10 16:44:43
         compiled from sidebars/login.html */ ?>
<h2>Log In to Make a Reservation</h2>

<form action="index.php" method="post">

<p>Username:<br />
<input type="text" name="username" id="username" /></p>

<p>Password:<br />
<input type="password" name="password" id="password" /></p>

<p><input type="submit" value="Log In" /></p>

<input type="hidden" name="do_login" value="true" />
</form>

<h2>Account Problems?</h2>

<p>If you are having problems logging in, please email <a href="mailto:<?php echo @HELP_EMAIL; ?>
"><?php echo @HELP_EMAIL; ?>
</a>.</p>