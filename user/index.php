<?php
require_once('../connect.php');

function getUser($conn) {
    $username = getUserToken();
    $sql = 'SELECT * FROM users WHERE username = ?';
    $stmt = $conn->prepare($sql);
    if($stmt->execute(array($username))) {
        while ($row = $stmt->fetch()) {
            echo 'Username: '.$row['username'].'<br>';
            echo 'Member Since: '.$row['time'].'<br><br>';
        }
    }
}

function getUserComments($conn) {
    $post_token = getPostToken();
    $sql = 'SELECT *, u.username FROM posts p LEFT JOIN comments c ON p.id = c.posts_id LEFT JOIN users u ON u.id = c.users_id WHERE p.token = ?';
    $stmt = $conn->prepare($sql);
    if($stmt->execute(array($post_token))) {
        while ($row = $stmt->fetch()) {
            if($row['id'] != null) {
                //REPLACE THIS WITH HTML
                //echo 'COMMENT: <br>';
                //echo 'User ID: '.$row['users_id'].'<br>';
                //echo 'Content: '.$row['content'].'<br><br>';
                echo '<div>
                           <a href=""><span>'.$row['username'].'</span></a>
                           <div>'.$row['content'].'</div>
                        </div>';
            }
        }
    }
}

function getUserToken() {
    if(isset($_GET['u'])) {
        return $_GET['u'];
    }
    else {
        //header();
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
    <h3>User Profile</h3>
    <?php
    getUser($dbh);
    ?>
</div>
<div>
    <?php
    //getUserComments($dbh);
    ?>
</div>
</body>
</html>