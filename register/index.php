<?php
require_once('../connect.php');

function register($conn) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']);
    $email = $_POST['email'];
    $token = generateToken();
    $sql = 'INSERT INTO users (username, password, email, token) VALUES (?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    try {
        if ($stmt->execute(array($username, $password, $email, $token))) {
            setcookie('token', $token, 0, "/");
            echo 'Welcome '.$username;
        }
    }
    catch (PDOException $e) {
        //echo $e->getMessage();
        echo 'Username or Email Already Registered';
        setcookie('token', '', 0, '/');
    }
}

function generateToken() {
    $date = date(DATE_RFC2822);
    $rand = rand();
    return sha1($date.$rand);
}

if(isset($_POST['register'])) {
    register($dbh);
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
    <h3>Register</h3>
    <form method="post" action="">
        <input type="text" name="email" placeholder="Email">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" name="register" value="REGISTER">
    </form>
</div>
</body>