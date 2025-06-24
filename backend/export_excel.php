<?php
require 'connect.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="donations.csv"');

$output = fopen('php://output', 'w');

// CSV Headers
fputcsv($output, [
    'Org Type', 'Org Name', 'Address', 'Contact Person', 'Phone',
    'Email', 'Dishes (Readable)', 'Preferred Date/Time', 'Instructions',
    'Status', 'Pickup Time (IST)', 'Submitted At (IST)'
]);

// Fetch all records (no filters)
$result = $conn->query("SELECT * FROM system ORDER BY submitted_at DESC");

while ($row = $result->fetch_assoc()) {
    $dishes = json_decode($row['dishes'], true);
    $formattedDishes = '';

    if (is_array($dishes)) {
        $index = 1;
        foreach ($dishes as $dish) {
            $formattedDishes .= "{$index}. {$dish['name']} ({$dish['type']}, Qty: {$dish['quantity']}, Packing: {$dish['packing']}, Exp: {$dish['expiry']}";
            if (!empty($dish['description'])) {
                $formattedDishes .= ", Desc: {$dish['description']}";
            }
            $formattedDishes .= ")\n";
            $index++;
        }
    } else {
        $formattedDishes = 'Invalid dish data';
    }

    // Convert pickup_time and submitted_at to IST
    $pickupIST = '';
    if (!empty($row['pickup_time'])) {
        $utc = new DateTime($row['pickup_time'], new DateTimeZone('UTC'));
        $utc->setTimezone(new DateTimeZone('Asia/Kolkata'));
        $pickupIST = $utc->format('Y-m-d H:i:s');
    }

    $submittedIST = '';
    if (!empty($row['submitted_at'])) {
        $utc = new DateTime($row['submitted_at'], new DateTimeZone('UTC'));
        $utc->setTimezone(new DateTimeZone('Asia/Kolkata'));
        $submittedIST = $utc->format('Y-m-d H:i:s');
    }

    fputcsv($output, [
        $row['role'],
        $row['name'],
        $row['address'],
        $row['contact'],
        $row['phone'],
        $row['email'],
        $formattedDishes,
        $row['donation_datetime'],
        $row['instructions'],
        $row['is_picked'] ? 'Picked' : 'Pending',
        $pickupIST,
        $submittedIST
    ]);
}

fclose($output);
exit;
?>
