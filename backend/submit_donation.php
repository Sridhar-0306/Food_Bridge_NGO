<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require 'connect.php';

// Read JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["error" => "Invalid JSON data"]);
    exit;
}

// Extract fields
$org_type = $conn->real_escape_string($data['org_type'] ?? '');
$org_name = $conn->real_escape_string($data['org_name'] ?? '');
$org_address = $conn->real_escape_string($data['org_address'] ?? '');
$contact_person = $conn->real_escape_string($data['contact_person'] ?? '');
$phone = $conn->real_escape_string($data['phone'] ?? '');
$email = $conn->real_escape_string($data['email'] ?? '');
$dishes = $conn->real_escape_string(json_encode($data['dishes'] ?? []));
$donation_date = $conn->real_escape_string($data['donation_date'] ?? '');
$instructions = $conn->real_escape_string($data['instructions'] ?? '');

// Save to DB
$sql = "INSERT INTO system (org_type, org_name, org_address, contact_person, phone, email, dishes, donation_date, instructions)
        VALUES ('$org_type', '$org_name', '$org_address', '$contact_person', '$phone', '$email', '$dishes', '$donation_date', '$instructions')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["error" => "DB error: " . $conn->error]);
}
?>
