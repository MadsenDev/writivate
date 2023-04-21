<!DOCTYPE html>
<html>
<head>
  <title>Wiki - Login</title>
  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="../public/styles/main.css">
  <link rel="stylesheet" type="text/css" href="../public/themes/default.css">
</head>
<body>
<main>
  <div class="auth-content">
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
    <p>Don't have an account? <a href="register.php">Register</a></p>
    <p>Forgot your password? <a href="reset_password.php">Reset Password</a></p>
    <p>Forgot your username? <a href="reset_username.php">Reset Username</a></p>
    <p>Back to <a href="../index.php">Home</a></p>
  </div>
</main>
</body>
</html>