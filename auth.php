<?php
define("MYSQL_HOST", "localhost");
define("MYSQL_PORT", "3306");
define("MYSQL_USER", "root");
define("MYSQL_PASS", "password");
define("MYSQL_DB", "logintest");

class Authentication {

    private $db;

    public function Authentication() {
        $this->db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB, MYSQL_PORT);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }


    public function login(string $user, string $pass) {
      // Convert both to uppercase so it's case insensitive
      $up = strtoupper($user);
      $stmt = $this->db->prepare("SELECT * FROM users WHERE upper(username)=?");
      $stmt->bind_param("s", $up);
      $stmt->execute();
      $res = $stmt->get_result();
      if ($res->num_rows == 0) {
        return false;
      }

      $res = $res->fetch_assoc();
      $valid = password_verify($pass, $res['password']);
      if (!$valid) { return false; }

      // Add new session to database
      $stmt = $this->db->prepare("INSERT INTO sessions (id, user_id) VALUES (?, ?)");
      $id = $this->generateSessionID();
      $user_id = $res['id'];
      $stmt->bind_param("ss", $id, $user_id);
      $stmt->execute();
      $stmt->close();

      setcookie('a', $id);
      return true;
    }

    function createUser(string $user, string $pass) {
      // Check for existing user
      $up = strtoupper($user);
      $stmt = $this->db->prepare("SELECT * FROM users WHERE UPPER(username)=?"); //WHERE upper(username)=?");
      $stmt->bind_param("s", $up);
      $stmt->execute();
      $res = $stmt->get_result();
      if ($res->num_rows > 0) {
        return false;
      }
      $stmt->close();

      $hash = password_hash($pass, PASSWORD_BCRYPT);

      $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
      $stmt->bind_param("ss", $user, $hash);
      $stmt->execute();
      $stmt->close();
      return true;
    }

    public function isLoggedIn() {
      if (!array_key_exists('a', $_COOKIE)) {
        return false;
      }

      $stmt = $this->db->prepare("SELECT * FROM sessions WHERE id=?");
      $cookie = $_COOKIE['a'];
      $stmt->bind_param("s", $cookie);
      $stmt->execute();
      return $stmt->get_result()->num_rows > 0;
    }

    public function getCurrentUser() {
      // INNER JOIN pulls data from the users table where the ID matches
      $stmt = $this->db->prepare("SELECT users.id, users.username FROM users INNER JOIN sessions ON sessions.user_id = users.id WHERE sessions.id=?");
      $cookie = $_COOKIE['a'];
      $stmt->bind_param("s", $cookie);
      $stmt->execute();
      $res = $stmt->get_result();

      if ($res->num_rows > 0) {
        return $res->fetch_assoc();
      } else {
        return false;
      }
    }

    public function logout() {
      // Remove session from the database
      $stmt = $this->db->prepare("DELETE FROM sessions WHERE id=?");
      if (!array_key_exists('a', $_COOKIE)) {
        return false;
      }

      $cookie = $_COOKIE['a'];
      $stmt->bind_param('s', $cookie);
      $stmt->execute();

      // Remove the cookie by setting expiry time to the past
      setcookie('a', '', time() - 3600);
    }

    public function cleanup() {
      $this->db->close();
    }

    // Generate 32 character session ID and check it hasn't been used already
    private function generateSessionID() {
      $id = bin2hex(random_bytes(16));
      $stmt = $this->db->prepare("SELECT id FROM sessions WHERE id=?");
      $stmt->bind_param('s', $id);
      $stmt->execute();

      // If ID exists, get new one
      if ($stmt->get_result()->num_rows > 0) {
        $id = generateSessionID();
      }
      return $id;
    }

}


?>
