<?php
include 'config.php';
include 'functions.php';
$site_name = get_setting_value($conn, 'site_name');

session_start();

// Get user id from URL or current user id
$user_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

// Fetch user data from the database
$user = get_user_by_id($conn, $user_id);

if (!$user) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include 'head.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <?php if ($_SESSION['user_id'] == $user_id): ?>
        <h1 class="profile-username"><?php echo $user['username']; ?> <a href="edit_profile.php">Edit Profile</a></h1>
        <?php endif; ?>
        <p class="profile-rank"><?php echo get_rank_title($conn, $user['rank_id']); ?></p>
        <p>Email: <?php echo $user['email']; ?></p>
        
        <!-- Inside the .content div -->
<div class="recently-viewed-guides">
    <h2>Recently Viewed Guides</h2>
    <?php
    $recently_viewed_guides = get_recently_viewed_guides($conn, $user_id);
    if (!empty($recently_viewed_guides)) {
        echo "<ul>";
        foreach ($recently_viewed_guides as $guide) {
            echo "<li><a href='guide.php?id=" . $guide['id'] . "'>" . htmlspecialchars($guide['title']) . "</a> - " . date("F j, Y, g:i a", strtotime($guide['view_time'])) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No recently viewed guides.</p>";
    }
    ?>
</div>

    </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>