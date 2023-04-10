<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Check if the username exists
  $stmt = $conn->prepare("SELECT id, password, rank FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $row['password'])) {
      // Set the session variables
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
      $_SESSION['rank'] = $row['rank'];

      // Redirect to the homepage
      header("Location: index.php");
      exit();
    } else {
      die('Incorrect password');
    }
  } else {
    die('Username not found');
  }
}
?>