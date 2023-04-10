<?php session_start(); ?>

<header>
  <div class="logo">
    <a href="index.php"><img src="images/logo.png" alt="Wiki Logo"></a>
  </div>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="add_guide.php">Add Guide</a></li>
      <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="logout.php">Log Out (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
      <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>