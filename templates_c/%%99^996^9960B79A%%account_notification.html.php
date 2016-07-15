<?php /* Smarty version 2.6.10, created on 2012-10-04 14:47:29
         compiled from emails/account_notification.html */ ?>
Hi <?php echo $this->_tpl_vars['email_user']->first_name; ?>
,

This is an automated message to notify you of your online account information for the CCA Digital Fine Art Studio Scheduler.

<?php if (! $this->_tpl_vars['email_user']->active): ?>
Your account has been marked as not active, meaning you cannot currently login and make reservations.  If you need your account activated, contact the Studio Manager.
<?php else: ?>
You can view the schedules for DFAS resources, as well as make or cancel reservations, at <?php echo @SITE_URL; ?>
/.

Your username is <?php echo $this->_tpl_vars['email_user']->username; ?>
, and your password is the same as your password for your CCA email account.
<?php endif; ?>

Thanks,
CCA Scheduler