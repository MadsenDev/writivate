<?php
  include 'config.php';
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $content = $_POST['content'];
    $creator_id = $_SESSION['user_id'];
    $filename = str_replace(' ', '-', strtolower($title)) . '.md';
    $file_dir = 'guides';
    $file_path = "$file_dir/$filename";

    // Check if a guide with the same title already exists
    $stmt = $conn->prepare("SELECT * FROM guides WHERE title = ?");
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      die('Guide with this title already exists');
    }

    // Save the markdown file
    if (!is_dir($file_dir)) {
      mkdir($file_dir);
    }
    file_put_contents($file_path, $content);

    // Insert the guide into the database
    $stmt = $conn->prepare("INSERT INTO guides (title, category_id, file_path, creator_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sisi", $title, $category_id, $file_path, $creator_id);
    $stmt->execute();

    header("Location: guide.php?id=$stmt->insert_id");
    exit();
  }
?>