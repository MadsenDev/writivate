<?php
include '../config.php';

if (isset($_GET['id'])) {
  $suggestion_id = $_GET['id'];

  $stmt = $conn->prepare("DELETE FROM suggestions WHERE id = ?");
  $stmt->bind_param('i', $suggestion_id);

  if ($stmt->execute()) {
    header('Location: manage_suggestions.php?success=1');
  } else {
    header('Location: manage_suggestions.php?error=1');
  }
} else {
  header('Location: manage_suggestions.php?error=1');
}
?>