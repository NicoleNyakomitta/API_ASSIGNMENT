<?php
// db_connect.php
$host = '127.0.0.1';
$db   = 'restaurant';
$user = 'root';
$pass = '1234'; 

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    http_response_code(500);
    die("DB connection failed: " . $mysqli->connect_error);
}

// set charset
$mysqli->set_charset($charset);
?>
