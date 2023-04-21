<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../config.php';

// Fetch the registration_enabled setting
$stmt = $conn->prepare("SELECT value FROM settings WHERE name = 'registration_enabled'");
$stmt->execute();
$result = $stmt->get_result();
$registration_enabled = $result->fetch_assoc()['value'];

// Redirect users to the home page if registration is disabled
if ($registration_enabled == '0') {
  header('Location: ../index.php');
  exit();
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Wiki - Register</title>
  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="../public/styles/main.css">
  <link rel="stylesheet" type="text/css" href="../public/themes/default.css">
</head>
<body>

<main>
  <div class="auth-content">
    <h1>Register</h1>
    <form method="POST" action="register_process.php">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" class="form-control" required>
      </div>
      <div class="form-group">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" class="form-control" required>
</div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
    <p>Back to <a href="../index.php">Home</a></p>
  </div>
</main>
</body>
</html>