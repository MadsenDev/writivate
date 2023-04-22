<?php
include '../config.php';
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT username FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $username = $user['username'];
            
            // Send the username reminder email
            $subject = "Username Reminder";
            $message = "Your username is: {$username}";
            $headers = "From: noreply@madsens.dev" . "\r\n";
            
            mail($email, $subject, $message, $headers);
            
            $success = "An email with your username has been sent to your email address.";
        } else {
            $error = "No user found with this email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fetch Username</title>
    <link rel="icon" type="image/png" href="/public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="../public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="../public/themes/default.css">
</head>
<body>
<div class="auth-content">
    <h1>Fetch Username</h1>
    <p>Enter your email address to receive a reminder with your username.</p>
    
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php elseif (isset($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>
    
    <form action="fetch_username.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit" class="btn btn-primary">Send Username</button>
    </form>
    <p>Back to <a href="login.php">Login</a></p>
</div>
</body>
</html>