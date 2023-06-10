<?php
include '../config.php';

$guide_id = $_GET['guide_id'];

$stmt = $conn->prepare("SELECT guide_views.id AS view_id, users.id AS user_id, users.username, guide_views.view_time, guide_views.duration FROM guide_views LEFT JOIN users ON guide_views.user_id = users.id WHERE guide_views.guide_id = ? ORDER BY guide_views.view_time DESC");
$stmt->bind_param("i", $guide_id);
$stmt->execute();
$views = $stmt->get_result();

while ($view = $views->fetch_assoc()) {
  echo '<p>';
  echo 'View ID: ' . $view['view_id'] . '<br>';
  echo 'User ID: ' . $view['user_id'] . '<br>';
  echo 'Username: ' . $view['username'] . '<br>';
  echo 'View Time: ' . $view['view_time'] . '<br>';
  echo 'Duration: ' . $view['duration'];
  // Add a button to delete a single guide.
  echo '<form method="GET">';
  echo '<input type="hidden" name="guide_id" value="' . $guide_id . '">';
  echo '<input type="hidden" name="view_id" value="' . $view['view_id'] . '">';
  echo '<button type="submit" name="remove_view" class="btn btn-danger">Delete View</button>';
  echo '</form>';
  echo '</p>';
}
?>