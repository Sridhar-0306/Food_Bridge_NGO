<?php
require 'connect.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="donations.csv"');

$output = fopen('php://output', 'w');

// CSV Headers
fputcsv($output, [
    'Org Type', 'Org Name', 'Address', 'Contact Person', 'Phone',
    'Email', 'Dishes (Readable)', 'Instructions',
    'Status', 'Pickup Time (IST)', 'Submitted At (IST)'
]);

// Fetch all donations
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

    // Convert pickup_time from UTC to IST
    $pickupIST = '';
    if (!empty($row['pickup_time'])) {
        $pickup = new DateTime($row['pickup_time'], new DateTimeZone('UTC'));
        $pickup->setTimezone(new DateTimeZone('Asia/Kolkata'));
        $pickupIST = $pickup->format('Y-m-d H:i:s');
    }

    // Submitted time is already in IST — use directly
    $submittedIST = $row['submitted_at'] ?? '';

    fputcsv($output, [
        $row['role'],
        $row['name'],
        $row['address'],
        $row['contact'],
        $row['phone'],
        $row['email'],
        $formattedDishes,
        $row['instructions'],
        $row['is_picked'] ? 'Picked' : 'Pending',
        $pickupIST,
        $submittedIST
    ]);
}

fclose($output);
exit;
?>
