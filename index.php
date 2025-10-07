<?php
session_start();
require 'db.php';

// Fetch latest 12 items ordered by creation date descending
$stmt = $pdo->query("SELECT * FROM items ORDER BY created_at DESC LIMIT 12");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>College Lost & Found Portal</title>
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
  <header class="header">
    <h1>College Lost & Found Portal</h1>
    <nav class="nav" aria-label="Main navigation">
      <a href="report_lost.php">Report Lost</a>
      <a href="report_found.php">Report Found</a>
      <a href="list.php">All Items</a>
    </nav>
  </header>

  <main class="container" role="main">
    <section aria-labelledby="latest-items-title">
      <h2 id="latest-items-title" style="margin-bottom: 20px; text-align: center;">Latest Lost & Found Items</h2>

      <?php if (count($items) === 0): ?>
        <p style="text-align: center; font-style: italic;">No items reported yet.</p>
      <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
          <?php foreach ($items as $item): ?>
            <article class="item-card" style="background: white; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
              <?php if (!empty($item['image']) && file_exists($item['image'])): ?>
                <img src="<?= htmlspecialchars($item['image']) ?>" alt="Image of <?= htmlspecialchars($item['name']) ?>" style="width: 100%; height: 180px; object-fit: cover;" />
              <?php else: ?>
                <div style="width: 100%; height: 180px; background-color: #ddd; display: flex; align-items: center; justify-content: center; color: #666; font-style: italic;">
                  <img class="item-image" src="<?= $item['image'] ? 'uploads/' . htmlspecialchars($item['image']) : 'https://via.placeholder.com/600x360?text=No+Image' ?>" alt="Item Image">
                </div>
              <?php endif; ?>
              <div style="padding: 15px;">
                <h3 style="margin-bottom: 8px; font-size: 1.2rem; color: #004080;"><?= htmlspecialchars($item['title']) ?></h3>
                <p style="margin-bottom: 6px;"><strong>Date:</strong> <?= htmlspecialchars(date('F j, Y', strtotime($item['created_at']))) ?></p>
                <a href="view.php?id=<?= urlencode($item['id']) ?>" class="button" style="margin-top: 10px; display: inline-block;">View Details</a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <footer class="footer" role="contentinfo">
    <p>&copy; <?= date('Y') ?> College Lost & Found Portal. All rights reserved.</p>
  </footer>
</body>
</html>
