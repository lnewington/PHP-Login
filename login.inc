<h1>Login</h1>
<form action="/login.php" method="post">
    <label>Username</label> <input type="text" name="user"/>
    <br />
    <label>Password</label> <input type="text" name="pass" />
    <br />
    <input type="submit" />
</form>

<h1>Create User</h1>
<form action="/create.php" method="post">
    <label>Username</label> <input type="text" name="user" />
    <br />
    <label>Password</label> <input type="text" name="pass" />
    <br />
    <label>Confirm Password</label> <input type="text" name="confirm_pass" />
    <br />
    <input type="submit" />
</form>
