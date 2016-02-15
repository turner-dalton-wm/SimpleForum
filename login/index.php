<?php
require_once('../connect.php');

function login($conn) {
    setcookie('token', "", 0, "/");
    $username = $_POST['username'];
    $password = sha1($_POST['password']);
    $sql = 'SELECT * FROM users WHERE username = ? AND password = ?';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute(array($username, $password))) {
        $valid = false;
        while ($row = $stmt->fetch()) {
            $valid = true;
            $token = generateToken();
            $sql = 'UPDATE users SET token = ? WHERE username = ?';
            $stmt1 = $conn->prepare($sql);
            if ($stmt1->execute(array($token, $username))) {
                setcookie('token', $token, 0, "/");
                echo 'Welcome '.$username;
            }
        }
        if(!$valid) {
            echo 'Username or Password Incorrect';
        }
    }
}

function generateToken() {
    $date = date(DATE_RFC2822);
    $rand = rand();
    return sha1($date.$rand);
}

if(isset($_POST['login'])) {
    login($dbh);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
</head>
<body>
<div>
    <a href="/">Home</a>
    <a href="/login">Login</a>
    <a href="/register">Register</a>
    <a href="/create">Create A Post</a>
</div><br><br>
<div>
    <h3>Log In</h3>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" name="login" value="LOGIN">
    </form>
</div>
</body>
</html>
