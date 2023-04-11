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



?>

<header>
  <div class="logo">
    <a href="index.php"><img src="images/logo.png" alt="Wiki Logo"></a>
  </div>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <?php
      if ($user_rank_number >= 1) {
        echo "<li><a href=\"add_guide.php\">Add Guide</a></li>";
      }
      ?>
      <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="logout.php">Log Out (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
      <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>