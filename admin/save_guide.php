<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../config.php';

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

  // After inserting the guide, get its ID
$guide_id = $conn->insert_id;

// Get the tags from the form and sanitize them
$tags_string = trim($_POST['tags']);
$tags = array_map('trim', explode(',', $tags_string));

// Insert the tags and associate them with the guide
foreach ($tags as $tag_name) {
  if ($tag_name === '') continue;
  
  // Check if the tag already exists
  $stmt = $conn->prepare("SELECT id FROM tags WHERE name = ?");
  $stmt->bind_param("s", $tag_name);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
    $tag_id = $result->fetch_assoc()['id'];
  } else {
    $stmt = $conn->prepare("INSERT INTO tags (name) VALUES (?)");
    $stmt->bind_param("s", $tag_name);
    $stmt->execute();
    $tag_id = $stmt->insert_id;
  }
  
  // Insert the guide-tag association
  $stmt = $conn->prepare("INSERT INTO guide_tags (guide_id, tag_id) VALUES (?, ?)");
  $stmt->bind_param("ii", $guide_id, $tag_id);
  $stmt->execute();
  if (!$stmt->execute()) {
    echo "Error: (" . $stmt->errno . ") " . $stmt->error . "<br>";
  }
  
}

  if ($stmt === false) {
    echo "Error: (" . $conn->errno . ") " . $conn->error . "<br>";
  } else {
    $stmt->free_result();
  }

  

  header("Location: manage_guides.php");
  exit();
}
?>