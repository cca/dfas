<?php
require_once 'config.php';

$user = new DO_Users();
$user->whereAdd("username = 'cwood'");
$user->find(true);
$user->admin = 1;
$user->update();
?>
cwood now admin.