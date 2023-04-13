<?php
  session_start();
  include '../config.php';

  // Check if user is logged in
  if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch the user's rank from the database
    $user_rank = '';
    $stmt = $conn->prepare("SELECT ranks.rank_number FROM users INNER JOIN ranks ON users.rank_id = ranks.id WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $user_rank = $row['rank_number'];
    }

    // Check if user has permission to access the admin panel
    if ($user_rank < 3) {
      header('Location: ../login.php');
      exit();
    }
  } else {
    header('Location: ../login.php');
    exit();
  }
?>

<aside class="sidebar">
  <div class="sidebar-header">
    <img src="../images/logo.png" alt="Logo">
  </div>
  <nav class="sidebar-nav">
    <ul class="nav-list">
      <li>
        <a href="manage_categories.php">
          <i class="fas fa-folder"></i> Manage Categories
        </a>
      </li>
      <li>
        <a href="manage_users.php">
          <i class="fas fa-users"></i> Manage Users
        </a>
      </li>
      <li>
        <a href="manage_guides.php">
          <i class="fas fa-book"></i> Manage Guides
        </a>
      </li>
      <li>
        <a href="manage_comments.php">
          <i class="fas fa-comments"></i> Manage Comments
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
      Logged in as <?php echo $username; ?><br>
      <a href="../logout.php">Logout</a>
    </div>
  <?php endif; ?>
</aside>