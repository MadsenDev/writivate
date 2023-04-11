<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $category_id = $_POST['category'];
  $content = $_POST['content'];
  $creator_id = $_SESSION['user_id'];

  // Check if a guide with the same title already exists
  $sql = "SELECT * FROM guides WHERE title = '$title'";
$result = mysqli_query($conn, $sql);
if (!$result) {
  echo "Error: (" . mysqli_errno($conn) . ") " . mysqli_error($conn) . "<br>";
} else {
  echo "Statement executed successfully.<br>";
}
if (mysqli_num_rows($result) > 0) {
  die('Guide with this title already exists');
}

  // Insert the guide into the database
  $stmt = $conn->prepare("INSERT INTO guides (title, category_id, content, creator_id, created_at) VALUES (?, ?, ?, ?, NOW())");
  $stmt->bind_param("sisi", $title, $category_id, $content, $creator_id);
  echo "Before executing the statement.<br>";
  $stmt->execute();
  echo "After executing the statement.<br>";
  $stmt->store_result();

  if ($stmt === false) {
    echo "Error: (" . $conn->errno . ") " . $conn->error . "<br>";
  } else {
    $stmt->free_result();
  }

  header("Location: guide.php?id=$stmt->insert_id");
  exit();
}
?>