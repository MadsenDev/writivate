<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $title = $_POST['title'];
  $category_id = $_POST['category_id'];
  $content = $_POST['content'];
  $updater_id = $_SESSION['user_id'];

  // Update the guide in the database
  $stmt = $conn->prepare("UPDATE guides SET title = ?, category_id = ?, content = ? WHERE id = ?");
  if ($stmt === false) {
    echo "Error: (" . $conn->errno . ") " . $conn->error;
    exit;
  }
  $stmt->bind_param("sisi", $title, $category_id, $content, $id);
  if (!$stmt->execute()) {
    echo "Error: (" . $stmt->errno . ") " . $stmt->error;
    exit;
  }

  // Insert the update information into the guide_updates table
  $stmt = $conn->prepare("INSERT INTO guide_updates (guide_id, updater_id) VALUES (?, ?)");
  $stmt->bind_param("ii", $id, $updater_id);
  $stmt->execute();

  header("Location: manage_guides.php");
  exit();
}
?>