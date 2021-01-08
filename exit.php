<?php
session_start();

$_SESSION = array();
setcookie("user_id", '', time()-3600);
setcookie("session_code", '', time()-3600);

session_unset();
session_destroy();
header("Location: /index.php");
exit();
