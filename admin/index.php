<?php
session_start();
include '../config.php';

// Fetch data for statistics
$result_guides = $conn->query("SELECT COUNT(*) AS count FROM guides");
$result_users = $conn->query("SELECT COUNT(*) AS count FROM users");
$result_views = $conn->query("SELECT COUNT(*) AS count FROM guide_views");
$result_categories = $conn->query("SELECT COUNT(*) AS count FROM categories");
//$result_comments = $conn->query("SELECT COUNT(*) AS count FROM comments");

$guides_count = $result_guides->fetch_assoc()['count'];
$users_count = $result_users->fetch_assoc()['count'];
$views_count = $result_views->fetch_assoc()['count'];
$categories_count = $result_categories->fetch_assoc()['count'];
//$comments_count = $result_comments->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="css/admin.css">
  <script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>
</head>
<body>
  <?php include 'admin_sidebar.php'; ?>
  <div class="content">
    <h1>Admin Dashboard</h1>
    <p>Welcome to the admin dashboard. Choose a menu item to get started or use the quick links below:</p>

    <div class="quick-links">
      <!-- Quick links go here -->
    </div>

    <div class="statistics">
      <div class="stat-box">
        <h2>Guides</h2>
        <p><?php echo $guides_count; ?></p>
      </div>
      <div class="stat-box">
    <h2>Categories</h2>
    <p><?php echo $categories_count; ?></p>
  </div>
  <div class="stat-box">
        <h2>Views</h2>
        <p><?php echo $views_count; ?></p>
      </div>
      <div class="stat-box">
        <h2>Users</h2>
        <p><?php echo $users_count; ?></p>
      </div>
      
      
      
    </div>
    <div class="quick-links">
    <a href="add_guide.php" class="quick-link">
    <i class="fas fa-book"></i>
    <span>Add Guide</span>
  </a>
  <a href="manage_categories.php" class="quick-link">
    <i class="fas fa-folder"></i>
    <span>Manage Categories</span>
  </a>
  <a href="manage_settings.php" class="quick-link">
    <i class="fas fa-cog"></i>
    <span>Manage Settings</span>
  </a>
  <a href="add_user.php" class="quick-link">
    <i class="fas fa-users"></i>
    <span>Add User</span>
  </a>
</div>
  </div>
</body>
</html>