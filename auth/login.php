<!DOCTYPE html>
<html>
<head>
  <title>Wiki - Login</title>
  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="../public/styles/main.css">
  <link rel="stylesheet" type="text/css" href="../public/styles/header.css">
</head>
<body>
<?php include '../header.php'; ?>

<main>
    <?php include '../sidebar.php'; ?>
  <div class="content">
    <h1>Login</h1>
    <form method="POST" action="login_process.php">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
  </div>
</main>

<?php include '../footer.php'; ?>
</body>
</html>