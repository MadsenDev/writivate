<!DOCTYPE html>
<html>
<head>
  <title>Wiki - Register</title>
  <link rel="stylesheet" type="text/css" href="styles/main.css">
  <link rel="stylesheet" type="text/css" href="styles/header.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <?php include 'sidebar.php'; ?>
  <div class="content">
    <h1>Register</h1>
    <form method="POST" action="register_process.php">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Register</button>
    </form>
  </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>