<?php
// mark_picked.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $conn->query("UPDATE system SET is_picked = 1 WHERE id = $id");
}

header("Location: dashboard.php");
exit();
