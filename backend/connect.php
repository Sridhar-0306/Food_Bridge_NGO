<?php
// connect.php

$host = 'sql12.freesqldatabase.com';
$user = 'sql12785684';
$password = 'ELNjgF5R4Z';
$dbname = 'sql12785684';
$port = 3306;

$conn = new mysqli($host, $user, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
