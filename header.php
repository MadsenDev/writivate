<?php
include 'config.php';
session_start();

// Fetch the user's rank number from the database
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
}

// Fetch logo URL from the database
$stmt = $conn->prepare("SELECT value FROM settings WHERE name = 'logo_url'");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $logo_url = $row['value'];
} else {
  $logo_url = "/public/images/logo.png"; // Default logo URL in case it's not found in the database
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

// Fetch registration_enabled setting from the database
$stmt = $conn->prepare("SELECT value FROM settings WHERE name = 'registration_enabled'");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $registration_enabled = $row['value'];
} else {
  $registration_enabled = 1; // Default to enabled in case it's not found in the database
}

?>

<header>
  <div class="header-container">
    <div class="logo">
    <a href="/index.php"><img src="<?php echo htmlspecialchars($logo_url); ?>" alt="Wiki Logo"></a>
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
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="/admin/index.php">Dashboard</a></li>
          <li><a href="/profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
          <li><a href="/auth/logout.php">Log Out</a></li>
        <?php else: ?>
          <li><a href="/auth/login.php">Login</a></li>
          <?php if ($registration_enabled == 1): ?>
            <li><a href="/auth/register.php">Register</a></li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>