<?php
  include 'config.php';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $content = $_POST['content'];
    $updater_id = $_SESSION['user_id'];
    $filename = str_replace(' ', '-', strtolower($title)) . '.md';
    $file_dir = 'guides';
    $file_path = "$file_dir/$filename";

    // Get the old file path
    $stmt = $conn->prepare("SELECT file_path FROM guides WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $old_file_path = $row['file_path'];

    // Save the new content to the new file path
    if (!is_dir($file_dir)) {
      mkdir($file_dir);
    }
    file_put_contents($file_path, $content);

    // Delete the old file if the file path has changed
    if ($old_file_path !== $file_path) {
      unlink($old_file_path);
    }

    // Update the guide in the database
    $stmt = $conn->prepare("UPDATE guides SET title = ?, category_id = ?, file_path = ? WHERE id = ?");
    $stmt->bind_param("sisi", $title, $category_id, $file_path, $id);
    $stmt->execute();

    // Insert the update information into the guide_updates table
    $stmt = $conn->prepare("INSERT INTO guide_updates (guide_id, updater_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $id, $updater_id);
    $stmt->execute();

    header("Location: guide.php?id=$id");
    exit();
  }
?>