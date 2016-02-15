<?php
$user = 'root';
$pass = 'root';
$name = 'forumdb';
$dbh = null;
try {
    $dbh = new PDO('mysql:host=localhost;dbname='.$name, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}