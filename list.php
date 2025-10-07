<?php
require 'db.php';

$perPage = 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $perPage;

// Get total count
$totalStmt = $pdo->query("SELECT COUNT(*) FROM items");
$totalItems = $totalStmt->fetchColumn();
$totalPages = ceil($totalItems / $perPage);

// Fetch items for current page
$stmt = $pdo->prepare("SELECT * FROM items ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>All Lost & Found Items</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .items-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill,minmax(250px,1fr));
      gap: 15px;
    }
    .item.card {
      background: var(--card);
      border-radius: 6px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      padding: 10px;
      text-decoration: none;
      color: inherit;
      display: flex;
      flex-direction: column;
    }
    .item img {
      width: 100%;
      height: 140px;
      object-fit: cover;
      border-radius: 4px;
    }
    .badge.lost {
      background-color: #ff4d4d;
      color: white;
      padding: 3px 7px;
      border-radius: 4px;
      font-size: 0.8em;
    }
    .badge.found {
      background-color: #4CAF50;
      color: white;
      padding: 3px 7px;
      border-radius: 4px;
      font-size: 0.8em;
    }
    .pagination {
      margin-top: 20px;
      text-align: center;
    }
    .pagination a {
      margin: 0 5px;
      padding: 6px 12px;
      background: var(--accent);
      color: white;
      text-decoration: none;
      border-radius: 4px;
    }
    .pagination a.disabled {
      background: #ccc;
      pointer-events: none;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>All Lost & Found Items</h1>
  <div class="items-grid">
    <?php if (count($items) === 0): ?>
      <p>No items found.</p>
    <?php else: ?>
      <?php foreach ($items as $it): ?>
        <a class="item card" href="view.php?id=<?=htmlspecialchars($it['id'])?>">
          <img src="<?= $it['image'] ? 'uploads/'.htmlspecialchars($it['image']) : 'https://via.placeholder.com/400x240?text=No+Image' ?>" alt="">
          <div style="margin-top:8px">
            <strong><?=htmlspecialchars($it['title'])?></strong>
            <div class="meta">
              <span class="badge <?= $it['type']=='lost'?'lost':'found' ?>"><?=htmlspecialchars($it['type'])?></span>
              &nbsp;• <?=htmlspecialchars($it['location'])?>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="?page=<?= $page - 1 ?>">&laquo; Prev</a>
    <?php else: ?>
      <a class="disabled">&laquo; Prev</a>
    <?php endif; ?>

    Page <?= $page ?> of <?= $totalPages ?>

    <?php if ($page < $totalPages): ?>
      <a href="?page=<?= $page + 1 ?>">Next &raquo;</a>
    <?php else: ?>
      <a class="disabled">Next &raquo;</a>
    <?php endif; ?>
  </div>

  <p><a href="index.php">Back to Home</a></p>
</div>
</body>
</html>