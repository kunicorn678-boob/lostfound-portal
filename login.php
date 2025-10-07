<?php
session_start();
require 'db.php';

$error = '';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php');
    exit;
}

// Hardcoded admin credentials (change these for production)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'password123'); // Change this to a strong password

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .login-container {
      max-width: 320px;
      margin: 50px auto;
      padding: 20px;
      background: var(--card);
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    label, input {
      display: block;
      width: 100%;
      margin-bottom: 10px;
    }
    input[type="text"], input[type="password"] {
      padding: 8px;
      font-size: 1em;
    }
    button {
      padding: 10px;
      width: 100%;
      background: var(--accent);
      color: white;
      border: none;
      font-size: 1em;
      cursor: pointer;
      border-radius: 4px;
    }
    .error {
      color: red;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
<div class="login-container">
  <h2>Admin Login</h2>
  <?php if ($error): ?>
    <div class="error"><?=htmlspecialchars($error)?></div>
  <?php endif; ?>
  <form method="post" action="">
    <label for="username">Username:</label>
    <input id="username" name="username" type="text" required autofocus>
    <label for="password">Password:</label>
    <input id="password" name="password" type="password" required>
    <button type="submit">Login</button>
  </form>
  <p><a href="../index.php">Back to Home</a></p>
</div>
</body>
</html>
