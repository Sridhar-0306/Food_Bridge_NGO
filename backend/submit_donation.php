<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require 'connect.php';

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!$data) {
    echo json_encode(["error" => "Invalid JSON data"]);
    exit;
}

// Extract and sanitize fields
$role = $conn->real_escape_string($data['org_type'] ?? '');
$name = $conn->real_escape_string($data['org_name'] ?? '');
$address = $conn->real_escape_string($data['org_address'] ?? '');
$contact = $conn->real_escape_string($data['contact_person'] ?? '');
$phone = $conn->real_escape_string($data['phone'] ?? '');
$email = $conn->real_escape_string($data['email'] ?? '');
$instructions = $conn->real_escape_string($data['instructions'] ?? '');
$dishes = $conn->real_escape_string(json_encode($data['dishes'] ?? []));
$donation_datetime = $conn->real_escape_string($data['donation_date'] ?? date('Y-m-d H:i:s'));

// Insert into database
$sql = "INSERT INTO system (
    role, name, address, contact, phone, email, instructions,
    dishes, donation_datetime
) VALUES (
    '$role', '$name', '$address', '$contact', '$phone', '$email', '$instructions',
    '$dishes', '$donation_datetime'
)";

// Execute query
if ($conn->query($sql)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["error" => "DB error: " . $conn->error]);
}
?>
