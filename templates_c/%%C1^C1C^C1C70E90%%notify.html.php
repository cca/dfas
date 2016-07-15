<?php /* Smarty version 2.6.10, created on 2012-09-10 16:44:43
         compiled from layout/notify.html */ ?>
<?php if (count ( $this->_tpl_vars['passErrors'] )): ?>
<div class="error">
<?php $_from = $this->_tpl_vars['passErrors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
 echo $this->_tpl_vars['e']; ?>
<br />
<?php endforeach; endif; unset($_from); ?>
</div>
<?php endif; ?>

<?php if (count ( $this->_tpl_vars['passMessages'] )): ?>
<div class="message">
<?php $_from = $this->_tpl_vars['passMessages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
 echo $this->_tpl_vars['m']; ?>
<br />
<?php endforeach; endif; unset($_from); ?>
</div>
<?php endif; ?>