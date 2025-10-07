<?php
require 'db.php';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("Invalid item ID.");
}

$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die("Item not found.");
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>View Item - <?=htmlspecialchars($item['title'])?></title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .item-image { max-width: 100%; height: auto; }
    .badge.lost { background-color: #ff4d4d; color: white; padding: 4px 8px; border-radius: 4px; }
    .badge.found { background-color: #4CAF50; color: white; padding: 4px 8px; border-radius: 4px; }
  </style>
</head>
<body>
<div class="container">
  <h1><?=htmlspecialchars($item['title'])?></h1>
  <div>
    <span class="badge <?= $item['type'] === 'lost' ? 'lost' : 'found' ?>">
      <?=htmlspecialchars(ucfirst($item['type']))?>
    </span>
  </div>
  <div style="margin-top: 10px;">
    <img class="item-image" src="<?= $item['image'] ? 'uploads/' . htmlspecialchars($item['image']) : 'https://via.placeholder.com/600x360?text=No+Image' ?>" alt="Item Image">
  </div>
  <div style="margin-top: 15px;">
    <strong>Description:</strong><br>
    <p><?=nl2br(htmlspecialchars($item['description']))?></p>
  </div>
  <div>
    <strong>Location:</strong> <?=htmlspecialchars($item['location'])?><br>
    <strong>Contact Info:</strong> <?=htmlspecialchars($item['contact'])?><br>
    <strong>Reported On:</strong> <?=htmlspecialchars($item['created_at'])?>
  </div>
  <p><a href="index.php">Back to Home</a></p>
</div>
</body>
</html>
