<html>
<head>
    <title>Login Test</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@500;900&display=swap" rel="stylesheet">
    <style>
      body {
        font-family: Raleway, sans-serif;
        text-align: center;
        background: rgb(60, 60, 70);
        color: #fff;
      }
      h1 {
        font-weight: bold;
      }
      label {
        margin-right: 0.5em;
      }
      input {
        margin-bottom: 1em;
      }
      a {
        color: #fff;
      }
      .error {
        color: red;
      }
      .accept {
        color: green;
      }
      .message {
        background-color: rgba(255, 255, 255, 0.5);
        padding: 0.5em;
      }
      .button, input[type=submit] {
        color: #fff;
        font-size: 1em;
        text-decoration: none;
        font-family: Raleway, sans-serif;
        border-style: none;
        background: rgba(0, 0, 0);
        padding: 0.5em;
      }
      .button:hover, input[type=submit]:hover {
        color: #3088C3;
      }
    </style>
</head>
<body>
    <?php
    include 'auth.php';

    function error(string $message) {
      echo '<h1 class="message error">'.$message.'</h1>';
    }

    if (array_key_exists('error', $_GET)) {
      switch ($_GET['error']) {
        case 'exists':
          error('User already exists');
          break;
        case 'req':
          error('All fields are required');
          break;
        case 'match':
          error('Passwords don\'t match');
          break;
        case 'login':
          error('Incorrect username/password');
          break;
      }
    }

    if (array_key_exists('created', $_GET)) {
      echo '<h1 class="message accept">Created, you may now log in</h1>';
    }

    $auth = new Authentication();

    if ($auth->isLoggedIn()) {
      echo "<h1 style=\"margin-top: 45%\">Hello " . $auth->getCurrentUser()['username'] . '</h1>';
      ?>
      <a href="/logout.php" class="button">Logout</a>
      <?php
    } else {
      include 'login.inc';
    }
    ?>
</body>
</html>
