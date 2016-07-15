<?php /* Smarty version 2.6.10, created on 2012-09-28 16:50:25
         compiled from ajax/member_search.html */ ?>
<ul>
<?php $_from = $this->_tpl_vars['search_members']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
<li><span class="name"><?php echo $this->_tpl_vars['m']->getName(); ?>
</span><span class="id"><?php echo $this->_tpl_vars['m']->id; ?>
</span></li>
<?php endforeach; endif; unset($_from); ?>
</ul>