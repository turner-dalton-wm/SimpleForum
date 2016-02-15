<?php
require_once('connect.php');

function getPost($conn) {
    $post_token = getPostToken();
    $sql = 'SELECT * FROM posts WHERE token = ?';
    $stmt = $conn->prepare($sql);
    if($stmt->execute(array($post_token))) {
        while ($row = $stmt->fetch()) {
            echo '<h3>'.$row['title'].'</h3><br>';
            echo '<form method="post" action="" style="display: inline-block;">
                    <input type="submit" name="upvote" value="Upvote">
                    </form>';
            echo '<form method="post" action="" style="display: inline-block;">
                    <input type="submit" name="downvote" value="Downvote">
                    </form><br><br>';
            echo 'Content: '.$row['content'].'<br><br>';
        }
    }
}

function getPostComments($conn) {
    $post_token = getPostToken();
    $sql = 'SELECT *, u.username FROM posts p LEFT JOIN comments c ON p.id = c.posts_id LEFT JOIN users u ON u.id = c.users_id WHERE p.token = ?';
    $stmt = $conn->prepare($sql);
    if($stmt->execute(array($post_token))) {
        while ($row = $stmt->fetch()) {
            if($row['id'] != null) {
                echo '<div>
                           <a href="/user/?u='.$row['username'].'"><span>'.$row['username'].'</span></a>
                           <div>'.$row['content'].'</div>
                        </div><br>';
            }
        }
    }
}

function addPostComment($conn) {
    $users_id = getUserId($conn);
    $content = $_POST['content'];
    $post_token = getPostToken();
    $sql = 'INSERT INTO comments (posts_id, users_id, content) (SELECT p.id, ?, ? FROM posts p WHERE p.token = ?)';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute(array($users_id, $content, $post_token))) {
        echo 'Comment Submitted Successfully';
    }
}

function getUserId($conn) {
    $token = getToken();
    $sql = 'SELECT id FROM users WHERE token = ?';
    $stmt = $conn->prepare($sql);
    if($stmt->execute(array($token))) {
        while ($row = $stmt->fetch()) {
            return $row['id'];
        }
    }
}

function upvote($conn) {
    $post_token = getPostToken();
    $sql = 'UPDATE posts SET upvotes = upvotes + 1 WHERE token = ?';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute(array($post_token))) {

    }
}

function downvote($conn) {
    $post_token = getPostToken();
    $sql = 'UPDATE posts SET downvotes = downvotes + 1 WHERE token = ?';
    $stmt = $conn->prepare($sql);
    if ($stmt->execute(array($post_token))) {

    }
}

function getPostToken() {
    if(isset($_GET['p'])) {
        return $_GET['p'];
    }
    else {
        //header();
    }
}

function getToken() {
    if (isset($_COOKIE['token'])) {
        return $_COOKIE['token'];
    }
    else {
        header('location:/login/');
    }
}

if(isset($_POST['addPostComment'])) {
    addPostComment($dbh);
}

if(isset($_POST['upvote'])) {
    upvote($dbh);
}

if(isset($_POST['downvote'])) {
    downvote($dbh);
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
    <?php
    getPost($dbh);
    ?>
</div>
<div>
    <?php
    getPostComments($dbh);
    ?>
</div>
<div>
    <form method="post" action="">
        <input type="text" name="content" placeholder="Content">
        <input type="submit" name="addPostComment" value="Submit">
    </form>
</div>
</body>
</html>
