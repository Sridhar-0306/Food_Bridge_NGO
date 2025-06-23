<?php
require 'connect.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="donations.csv"');

$output = fopen('php://output', 'w');

// CSV Headers
fputcsv($output, [
    'Org Type', 'Org Name', 'Address', 'Contact Person', 'Phone',
    'Email', 'Dishes (Readable)', 'Preferred Date/Time', 'Instructions', 'Submitted At'
]);

// Fetch from DB
$result = $conn->query("SELECT * FROM system ORDER BY submitted_at DESC");

while ($row = $result->fetch_assoc()) {
    $decodedDishes = json_decode($row['dishes'], true);
    $formattedDishes = '';

    if (is_array($decodedDishes)) {
        $index = 1;
        foreach ($decodedDishes as $dish) {
            $formattedDishes .= "{$index}. {$dish['name']} ({$dish['type']}, Qty: {$dish['quantity']}, Packing: {$dish['packing']}, Exp: {$dish['expiry']})\n";
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
        $row['donation_datetime'],
        $row['instructions'],
        $row['submitted_at']
    ]);
}

fclose($output);
exit;
?>
