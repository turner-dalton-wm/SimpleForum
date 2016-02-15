<?php
require_once('../connect.php');

function addPost($conn) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $post_token = generateToken();
    $token = getToken();
    $sql = 'INSERT INTO posts (users_id, title, content, token) (SELECT u.id, ?, ?, ? FROM users u WHERE u.token = ?)';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute(array($title, $content, $post_token, $token))) {
        echo 'Post Submitted Successfully';
    }
}

function generateToken() {
    $date = date(DATE_RFC2822);
    $rand = rand();
    return sha1($date.$rand);
}

function getToken() {
    if (isset($_COOKIE['token'])) {
        return $_COOKIE['token'];
    }
    else {
        //header('location:/SimpleCart/login/');
    }
}
?>
<!DOCTYPE html>
<html>
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
    <h3>Create Post</h3>
    <?php
    if(isset($_POST['addPost'])) {
        addPost($dbh);
    }
    ?>
    <form method="post" action="">
        <input type="text" name="title" placeholder="Title">
        <input type="text" name="content" placeholder="Content">
        <input type="submit" name="addPost" value="Submit">
    </form>
</div>
</body>
