<?php
include 'auth.php';
$auth = new Authentication();
$auth->logout();
$auth->cleanup();
header('Location: /');
?>
