<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Check if the username already exists
  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
    die('Username already exists');
  } else {
    // Insert new user into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $rank = 'view_only'; // Set default rank to 'view_only'
    $stmt = $conn->prepare("INSERT INTO users (username, password, rank) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $rank);
    $stmt->execute();

    // Set the session variables
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['username'] = $username;
    $_SESSION['rank'] = $rank;

    // Redirect to the homepage
    header("Location: index.php");
    exit();
  }
}
?>