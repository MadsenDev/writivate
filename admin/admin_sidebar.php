<?php
session_start();
include '../config.php';
include 'check_permissions.php';

// Fetch content_type_plural and content_type_single from the 'settings' table
$stmt = $conn->prepare("SELECT name, value FROM settings WHERE name = 'content_type_plural' OR name = 'content_type_single'");
$stmt->execute();
$result = $stmt->get_result();
$content_types = [];
while ($row = $result->fetch_assoc()) {
  $content_types[$row['name']] = $row['value'];
}
$content_type_plural = $content_types['content_type_plural'];
$content_type_single = $content_types['content_type_single'];

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit();
}

$username = $_SESSION['username'];
$user_rank_id = get_user_rank_id($conn, $username);
?>

<aside class="sidebar">
  <div class="sidebar-header">
    <a href="index.php"><img src="/public/images/logo.png" alt="Logo"></a>
  </div>
  <nav class="sidebar-nav">
    <ul class="nav-list">
      <li>
        <a href="/index.php">
          <i class="fas fa-folder"></i> View Site
        </a>
      </li>
      <li>
        <a href="manage_languages.php">
          <i class="fas fa-cogs"></i> Manage Languages
        </a>
      </li>
      <li>
        <a href="manage_categories.php">
          <i class="fas fa-folder"></i> Manage Categories
        </a>
      </li>
      <li>
        <a href="manage_ranks.php">
          <i class="fas fa-users"></i> Manage Ranks
        </a>
      </li>
      <li>
        <a href="manage_users.php">
          <i class="fas fa-users"></i> Manage Users
        </a>
      </li>
      <li>
        <a href="manage_guides.php">
          <i class="fas fa-book"></i> Manage <?php echo htmlspecialchars($content_type_plural); ?>
        </a>
      </li>
      <li>
        <a href="manage_suggestions.php">
          <i class="fas fa-comments"></i> Manage Suggestions
        </a>
      </li>
      <li>
        <a href="manage_views.php">
          <i class="fas fa-comments"></i> Manage Views
        </a>
      </li>
      <li>
        <a href="manage_settings.php">
          <i class="fas fa-cogs"></i> Manage Settings
        </a>
      </li>
    </ul>
  </nav>

  <?php if (isset($username)) : ?>
    <div class="sidebar-footer">
      Logged in as <br> <b><?php echo $username; ?></b><br>
      <a href="../auth/logout.php">Logout</a>
    </div>
  <?php endif; ?>
</aside>