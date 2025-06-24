<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    // Get the current time in UTC
    $pickup_time = (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');

    $conn->query("UPDATE system SET is_picked = 1, pickup_time = '$pickup_time' WHERE id = $id");
}

header("Location: dashboard.php");
exit();
