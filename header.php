<?php
include 'config.php';
session_start();

// Fetch the user's rank number from the database
$user_rank_number = 0;
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT rank_number FROM users INNER JOIN ranks ON users.rank_id = ranks.id WHERE users.id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_rank_number = $row['rank_number'];
  }
}

// Fetch parent categories and their subcategories
$stmt = $conn->prepare("SELECT * FROM categories WHERE parent_id IS NULL");
$stmt->execute();
$parent_categories = $stmt->get_result();

function fetch_subcategories($conn, $parent_id) {
  $stmt = $conn->prepare("SELECT * FROM categories WHERE parent_id = ?");
  $stmt->bind_param("i", $parent_id);
  $stmt->execute();
  return $stmt->get_result();
}

?>

<header>
  <div class="header-container">
    <div class="logo">
      <a href="/index.php"><img src="/public/images/logo.png" alt="Wiki Logo"></a>
    </div>
    <nav class="categories">
      <ul>
        <?php while ($parent = $parent_categories->fetch_assoc()): ?>
          <li>
            <a href="/categories.php?id=<?= $parent['id'] ?>"><?= $parent['name'] ?></a>
            <?php $subcategories = fetch_subcategories($conn, $parent['id']); ?>
            <?php if ($subcategories->num_rows > 0): ?>
              <ul class="submenu">
                <?php while ($sub = $subcategories->fetch_assoc()): ?>
                  <li><a href="/categories.php?id=<?= $sub['id'] ?>"><?= $sub['name'] ?></a></li>
                <?php endwhile; ?>
              </ul>
            <?php endif; ?>
          </li>
        <?php endwhile; ?>
      </ul>
    </nav>
    <nav class="user-actions">
      <ul>
        <?php
        if ($user_rank_number >= 1) {
          echo "<li><a href=\"/profile.php\">Profile</a></li>";
        }
        ?>
        <?php
        if ($user_rank_number >= 3) {
          echo "<li><a href=\"/admin/index.php\">Dashboard</a></li>";
        }
        ?>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="/auth/logout.php">Log Out (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
        <?php else: ?>
          <li><a href="/auth/login.php">Login</a></li>
          <li><a href="/auth/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>