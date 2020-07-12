<?php
include 'auth.php';

$auth = new Authentication();
$li = $auth->login($_POST['user'], $_POST['pass']);
$auth->cleanup();

if ($li) {
  header('Location: /');
} else {
  header('Location: /?error=login');
}
?>
