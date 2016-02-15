<?php
require_once('connect.php');

function getPosts($conn) {
    $sql = 'SELECT * FROM posts ORDER BY -(upvotes - downvotes)';
    $stmt = $conn->prepare($sql);
    if($stmt->execute(array())) {
        while ($row = $stmt->fetch()) {
            echo '<div>
                    <span>Score: '.number_format((($row['upvotes'] / max($row['upvotes'] + $row['downvotes'], 1)) * 100), 0).'%</span>
                    <a href="view.php?p='.$row['token'].'">'.$row['title'].'</a>
                    </div>';
        }
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
    <h3>Top Posts</h3>
    <?php
    getPosts($dbh);
   ?>
</div>
</body>
</html>