<?php
include '../config.php';
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user_id = $user['id'];
            
            // Generate a random token and set an expiration time
            $token = bin2hex(random_bytes(32));
            $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour')); // The token will be valid for 1 hour
            
            // Insert the token into the database
            $stmt = $conn->prepare("INSERT INTO password_reset_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $token, $expires_at);
            $stmt->execute();
            
            // Send the password reset email
            $reset_link = "http://wiki.madsens.dev/auth/change_password.php?token={$token}";
            $subject = "Password Reset";
            $message = "To reset your password, please click the following link: {$reset_link}";
            $headers = "From: noreply@madsens.dev" . "\r\n";
            
            mail($email, $subject, $message, $headers);
            
            $success = "A password reset email has been sent to your email address.";
        } else {
            $error = "No user found with this email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="../public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="../public/themes/default.css">
</head>
<body>
<div class="auth-content">
    <h1>Reset Password</h1>
    <p>Enter your email address to reset your password.</p>
    
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php elseif (isset($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>
    
    <form action="reset_password.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
    </form>
    <p>Back to <a href="login.php">Login</a></p>
</div>
</body>
</html>