<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../config.php';
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $token = $_POST['token'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        if (validate_password_reset_token($conn, $token)) {
            $user_id = get_user_id_by_token($conn, $token);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
            $stmt->execute();

            remove_password_reset_token($conn, $token);

            $success = "Your password has been updated. <a href='login.php'>Return to login</a>";
        } else {
            $error = "Invalid or expired password reset token.";
        }
    }
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    if (!validate_password_reset_token($conn, $token)) {
        $error = "Invalid or expired password reset token.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="../public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="../public/themes/default.css">
</head>
<body>
<div class="auth-content">
    <h1>Change Password</h1>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php elseif (isset($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php else: ?>
        <form action="change_password.php" method="post">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password" required>
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    <?php endif; ?>
    <p><a href="login.php">Return to login</a></p>
</div>
</body>
</html>