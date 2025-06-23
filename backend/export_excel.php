<?php
require 'connect.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="donations.csv"');

$output = fopen('php://output', 'w');

// CSV Headers
fputcsv($output, [
    'Org Type', 'Org Name', 'Address', 'Contact Person', 'Phone',
    'Email', 'Dishes (Full Info)', 'Pickup Time', 'Instructions', 'Submitted At', 'Status'
]);

// Fetch all records — nothing filtered or deleted
$result = $conn->query("SELECT * FROM system ORDER BY submitted_at DESC");

while ($row = $result->fetch_assoc()) {
    $decodedDishes = json_decode($row['dishes'], true);
    $formattedDishes = '';

    if (is_array($decodedDishes)) {
        $index = 1;
        foreach ($decodedDishes as $dish) {
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

    fputcsv($output, [
        $row['role'],
        $row['name'],
        $row['address'],
        $row['contact'],
        $row['phone'],
        $row['email'],
        $formattedDishes,
        $row['pickup_time'],
        $row['instructions'],
        $row['submitted_at'],
        $row['is_picked'] ? 'Picked' : 'Pending'
    ]);
}

fclose($output);
exit;
?>
