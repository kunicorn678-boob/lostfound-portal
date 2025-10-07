<?php
require 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type = 'lost';
    $image = null;

    if ($title && $location && $contact) {
        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $imageName = uniqid() . '.' . $ext;
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0755, true);
                }
                if (move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName)) {
                    $image = $imageName;
                } else {
                    $error = "Failed to upload image.";
                }
            } else {
                $error = "Invalid image file type.";
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("INSERT INTO items (title, type, location, contact, description, image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$title, $type, $location, $contact, $description, $image]);
            header('Location: index.php');
            exit;
        }
    } else {
        $error = "Please fill all required fields.";
    }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Report Lost Item</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
  <h1>Report Lost Item</h1>
  <?php if ($error): ?>
    <p style="color:red;"><?=htmlspecialchars($error)?></p>
  <?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <label>Title:<br><input type="text" name="title" required></label><br><br>
    <label>Description:<br><textarea name="description" rows="4"></textarea></label><br><br>
    <label>Location:<br><input type="text" name="location" required></label><br><br>
    <label>Contact Info:<br><input type="text" name="contact" required></label><br><br>
    <label>Image (optional):<br><input type="file" name="image" accept="image/*"></label><br><br>
    <button type="submit">Submit Lost Item</button>
  </form>
  <p><a href="index.php">Back to Home</a></p>
</div>
</body>
</html>
