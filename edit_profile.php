<?php
session_start();

include 'config.php';
include 'functions.php';
$site_name = get_setting_value($conn, 'site_name');

// Fetch current user data
$user = get_user_by_id($conn, $_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Update the user data in the database
    $updated = update_user($conn, $_SESSION['user_id'], $username, $email, $password);

    if ($updated) {
        header("Location: profile.php");
        exit();
    } else {
        $error = "Error updating profile.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $site_name; ?></title>
    <link rel="icon" type="image/png" href="public/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="public/styles/header.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Edit Profile</h1>
        <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="edit_profile.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo $user['username']; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo $user['email']; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password (leave blank to keep current password):</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>