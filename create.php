<?php
include 'auth.php';

if ($_POST['pass'] != $_POST['confirm_pass']) {
  header("Location: /?error=match");
  die();
} elseif (strlen($_POST['user']) == 0 || strlen($_POST['pass']) == 0 || strlen($_POST['confirm_pass']) == 0) {
  header('Location: /?error=req');
  die();
}

$auth = new Authentication();

if (!$auth->createUser($_POST['user'], $_POST['pass'])) {
  header('Location: /?error=exists');
} else {
  header('Location: /?created');
}

$auth->cleanup();
?>
