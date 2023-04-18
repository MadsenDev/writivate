<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $guide_id = $_POST['guide_id'];
  $language = $_POST['language'];
  $title = $_POST['title'];
  $content = $_POST['content'];

  // Check if a translation with the same language already exists for the guide
  $stmt = $conn->prepare("SELECT * FROM guide_translations WHERE guide_id = ? AND language = ?");
  $stmt->bind_param("is", $guide_id, $language);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    die('Translation with this language already exists for the guide');
  }

  // Insert the translation into the database
  $stmt = $conn->prepare("INSERT INTO guide_translations (guide_id, language, title, content) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isss", $guide_id, $language, $title, $content);
  $stmt->execute();

  if ($stmt === false) {
    echo "Error: (" . $conn->errno . ") " . $conn->error . "<br>";
  } else {
    $stmt->free_result();
  }

  header("Location: view_translations.php?guide_id={$guide_id}");
  exit();
}
?>