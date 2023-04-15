<?php
session_start();
include '../config.php';

// Check if user is logged in and has rank 3
if (!isset($_SESSION['user_id'])) {
  header('Location: ../login.php');
  exit();
}
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT rank_number FROM users INNER JOIN ranks ON users.rank_id = ranks.id WHERE users.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0 || $result->fetch_assoc()['rank_number'] < 3) {
  header('Location: ../index.php');
  exit();
}

// Handle form submissions
if (isset($_POST['update_settings'])) {
  $site_title = $_POST['site_title'];
  $site_description = $_POST['site_description'];
  $contact_email = $_POST['contact_email'];

  $stmt = $conn->prepare("UPDATE settings SET site_title = ?, site_description = ?, contact_email = ?");
  $stmt->bind_param("sss", $site_title, $site_description, $contact_email);
  $stmt->execute();

  // Redirect back to manage_settings.php with a success message
  header('Location: manage_settings.php?message=Settings updated.');
  exit();
}

// Get current settings
$stmt = $conn->prepare("SELECT name, value FROM settings");
$stmt->execute();
$result = $stmt->get_result();
$settings = [];
while ($row = $result->fetch_assoc()) {
  $settings[$row['name']] = $row['value'];
}
$site_title = $settings['site_name'];
$logo_url = $settings['logo_url'];
$default_category_id = $settings['default_category_id'];
$comments_moderation = $settings['comments_moderation'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Wiki - Manage Settings</title>
  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
<?php include 'admin_sidebar.php'; ?>

<main>
  <div class="content">
    <h1>Manage Settings</h1>

    <?php
    if (isset($_GET['message'])) {
      echo '<p class="success">' . htmlspecialchars($_GET['message']) . '</p>';
    }
    ?>

    <form method="POST">
      <label for="site_title">Site Title:</label>
      <input type="text" id="site_title" name="site_title" value="<?php echo htmlspecialchars($site_title); ?>">

      <label for="site_description">Site Description:</label>
      <textarea id="site_description" name="site_description"><?php echo htmlspecialchars($site_description); ?></textarea>

      <label for="contact_email">Contact Email:</label>
      <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($contact_email); ?>">

      <button type="submit" name="update_settings">Update Settings</button>
    </form>
  </div>
</main>
</body>
</html>