<?php
session_start();
include '../config.php';

// Fetch all guide views with associated guide and user information
$stmt = $conn->prepare("SELECT guide_views.id AS view_id, guides.id AS guide_id, guides.title AS guide_title, users.id AS user_id, users.username, guide_views.view_time, guide_views.duration FROM guide_views INNER JOIN guides ON guide_views.guide_id = guides.id LEFT JOIN users ON guide_views.user_id = users.id ORDER BY guides.id ASC");
$stmt->execute();
$views = $stmt->get_result();

// Remove single view
if (isset($_GET['remove_view'])) {
  $view_id = $_GET['remove_view'];
  $stmt = $conn->prepare("DELETE FROM guide_views WHERE id = ?");
  $stmt->bind_param("i", $view_id);
  $stmt->execute();
  header("Location: manage_views.php");
  exit();
}

// Remove all views for a specific guide
if (isset($_GET['remove_all_views_for_guide'])) {
  $guide_id = $_GET['remove_all_views_for_guide'];
  $stmt = $conn->prepare("DELETE FROM guide_views WHERE guide_id = ?");
  $stmt->bind_param("i", $guide_id);
  $stmt->execute();
  header("Location: manage_views.php");
  exit();
}

// Remove all views
if (isset($_POST['remove_all_views'])) {
  $conn->query("DELETE FROM guide_views");
  header("Location: manage_views.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Views</title>
  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
<?php include 'admin_sidebar.php'; ?>

<main>
  <div class="content">
    <h1>Manage Views</h1>

    <table>
      <thead>
        <tr>
          <th>Guide ID</th>
          <th>Guide Title</th>
          <th>User ID</th>
          <th>Username</th>
          <th>View Time</th>
          <th>Duration</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($view = $views->fetch_assoc()): ?>
          <tr>
            <td><?= $view['guide_id'] ?></td>
            <td><?= $view['guide_title'] ?></td>
            <td><?= $view['user_id'] ?></td>
            <td><?= $view['username'] ?></td>
            <td><?= $view['view_time'] ?></td>
            <td><?= $view['duration'] ?></td>
            <td>
              <a href="manage_views.php?remove_view=<?= $view['view_id'] ?>">Remove View</a> |
              <a href="manage_views.php?remove_all_views_for_guide=<?= $view['guide_id'] ?>">Remove All Views for Guide</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <form method="POST">
      <button type="submit" name="remove_all_views">Remove All Views</button>
    </form>
  </div>
</main>
</body>
</html>