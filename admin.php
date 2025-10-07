<?php

session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Delete image file if exists
    $stmt = $pdo->prepare("SELECT image FROM items WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($item && $item['image']) {
        $imagePath = '../uploads/' . $item['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Delete item from DB
    $delStmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
    $delStmt->execute([$id]);

    header('Location: admin.php');
    exit;
}

// Fetch all items
$stmt = $pdo->query("SELECT * FROM items ORDER BY created_at DESC");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 8px 12px;
      border: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: var(--accent);
      color: white;
    }
    a.button {
      background-color: var(--accent);
      color: white;
      padding: 6px 12px;
      text-decoration: none;
      border-radius: 4px;
      font-size: 0.9em;
    }
    a.button.delete {
      background-color: #e74c3c;
    }
    .logout {
      float: right;
      margin-top: 10px;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>Admin Dashboard</h1>
  <a href="logout.php" class="button logout">Logout</a>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Type</th>
        <th>Location</th>
        <th>Contact</th>
        <th>Created At</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($items) === 0): ?>
        <tr><td colspan="8">No items found.</td></tr>
      <?php else: ?>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?=htmlspecialchars($item['id'])?></td>
            <td><?=htmlspecialchars($item['title'])?></td>
            <td><?=htmlspecialchars($item['type'])?></td>
            <td><?=htmlspecialchars($item['location'])?></td>
            <td><?=htmlspecialchars($item['contact'])?></td>
            <td><?=htmlspecialchars($item['created_at'])?></td>
            <td>
              <?php if ($item['image']): ?>
                <img src="uploads/<?=htmlspecialchars($item['image'])?>" alt="Image" style="max-width: 80px; max-height: 50px;">
              <?php else: ?>
                No Image
              <?php endif; ?>
            </td>
            <td>
              <a href="../view.php?id=<?=htmlspecialchars($item['id'])?>" class="button" target="_blank">View</a>
              <a href="admin.php?delete=<?=htmlspecialchars($item['id'])?>" class="button delete" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
  <p><a href="index.php">Back to Home</a></p>
</div>
</body>
</html>
