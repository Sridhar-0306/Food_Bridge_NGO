<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
require 'connect.php';

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$filter = $_GET['filter'] ?? 'active';
$where = "WHERE 1=1";

// Filter logic (picked/unpicked only; expiry handled below)
if ($filter === 'picked') {
    $where .= " AND is_picked = 1";
} elseif ($filter === 'active') {
    $where .= " AND is_picked = 0";
}

// Fetch donations
$result = $conn->query("SELECT * FROM system $where ORDER BY submitted_at DESC");

// Current datetime for expiry check
$now = new DateTime();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>NGO Admin Dashboard</title>
  <style>
    body { background: #111; color: #fff; font-family: Arial; padding: 20px; }
    h2 { color: gold; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #444; padding: 10px; text-align: left; vertical-align: top; }
    th { background: #222; color: gold; }
    tr:nth-child(even) { background: #1e1e1e; }
    .picked { color: lime; font-weight: bold; }
    .btn { background: gold; color: #000; padding: 5px 10px; border: none; cursor: pointer; border-radius: 4px; }
    .logout { float: right; background: crimson; color: #fff; padding: 5px 10px; border: none; }
    .filters { margin-bottom: 20px; }
    .filters a { color: #fff; margin-right: 15px; text-decoration: none; font-weight: bold; }
  </style>
</head>
<body>
  <h2>NGO Admin Dashboard</h2>
  <form action="logout.php" method="post" style="float:right">
    <button class="logout">Logout</button>
  </form>

  <div class="filters">
    <a href="?filter=active">Active</a>
    <a href="?filter=picked">Picked</a>
  </div>

  <table>
    <tr>
      <th>Org</th>
      <th>Address</th>
      <th>Contact</th>
      <th>Phone</th>
      <th>Email</th>
      <th>Dishes</th>
      <th>Pickup Time</th>
      <th>Instructions</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <?php
        // Decode dishes
        $dishes = json_decode($row['dishes'], true);
        $skip = false;

        // Check for expired items
        if (is_array($dishes)) {
            foreach ($dishes as $dish) {
                if (!empty($dish['expiry'])) {
                    $expiry = DateTime::createFromFormat('Y-m-d\TH:i', $dish['expiry']);
                    if ($expiry && $expiry < $now) {
                        $skip = true;
                        break;
                    }
                }
            }
        }

        if ($skip) continue;
      ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['role']) ?>)</td>
        <td><?= htmlspecialchars($row['address']) ?></td>
        <td><?= htmlspecialchars($row['contact']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td>
          <?php
            $index = 1;
            foreach ($dishes as $dish) {
              echo "<b>{$index}. " . htmlspecialchars($dish['name']) . "</b><br>";
              echo "Type: " . htmlspecialchars($dish['type']) . " | ";
              echo "Qty: " . htmlspecialchars($dish['quantity']) . "<br>";
              echo "Packaging: " . htmlspecialchars($dish['packing']) . "<br>";
              echo "Expiry: " . str_replace("T", " ", htmlspecialchars($dish['expiry'])) . "<br><br>";
              $index++;
            }
          ?>
        </td>
        <td><?= htmlspecialchars($row['pickup_time']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['instructions'])) ?></td>
        <td><?= $row['is_picked'] ? '<span class="picked">Picked</span>' : 'Pending' ?></td>
        <td>
          <?php if (!$row['is_picked']): ?>
            <form method="POST" action="mark_picked.php">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <button class="btn" type="submit">Mark Picked</button>
            </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <?php if ($pages > 1): ?>
    <div style="margin-top: 20px;">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="?page=<?= $i ?>&filter=<?= $filter ?>" style="margin-right: 8px; color: gold;">
          <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</body>
</html>
