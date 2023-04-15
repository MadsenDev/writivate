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


// Get current settings
$stmt = $conn->prepare("SELECT name, value FROM settings");
$stmt->execute();
$result = $stmt->get_result();
$settings = [];
while ($row = $result->fetch_assoc()) {
  $settings[$row['name']] = $row['value'];
}

// Set variables with fetched values
$site_title = $settings['site_name'];
$site_description = $settings['site_description'];
$contact_email = $settings['contact_email'];

// Handle form submissions
if (isset($_POST['update_settings'])) {
  $site_title = $_POST['site_title'];
  $site_description = $_POST['site_description'];
  $contact_email = $_POST['contact_email'];

  $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'site_name'");
$stmt->bind_param("s", $site_title);
$stmt->execute();

$stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'site_description'");
$stmt->bind_param("s", $site_description);
$stmt->execute();

$stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'contact_email'");
$stmt->bind_param("s", $contact_email);
$stmt->execute();

// Handle logo upload
if (!empty($_FILES['logo_url']['name'])) {
  $target_dir = "../public/images/";
  $target_file = $target_dir . basename($_FILES['logo_url']['name']);
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  // Check if the file is actually an image
  $check = getimagesize($_FILES['logo_url']['tmp_name']);
  if ($check === false) {
    die("File is not an image.");
  }

  // Allow only certain file formats
  if ($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType !== "jpeg" && $imageFileType !== "gif") {
    die("Sorry, only JPG, JPEG, PNG, and GIF files are allowed.");
  }

  // Move the uploaded file to the target directory
  if (!move_uploaded_file($_FILES['logo_url']['tmp_name'], $target_file)) {
    die("Sorry, there was an error uploading your file.");
  }

  // Update the logo URL in the database
  $logo_url = "/public/images/" . basename($_FILES['logo_url']['name']);
  $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE name = 'logo_url'");
  $stmt->bind_param("s", $logo_url);
  $stmt->execute();
}


  // Redirect back to manage_settings.php with a success message
  header('Location: manage_settings.php?message=Settings updated.');
  exit();
}

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

    <form method="POST" enctype="multipart/form-data">
      <label for="site_title">Site Title:</label>
      <input type="text" id="site_title" name="site_title" value="<?php echo htmlspecialchars($site_title); ?>">

      <label for="logo_url">Logo:</label>
      <img src="<?php echo htmlspecialchars($settings['logo_url']); ?>" alt="Current logo" id="current-logo" style="max-width: 200px; max-height: 200px; margin-top: 10px;"><br>
      <input type="file" id="logo_url" name="logo_url" accept="image/*">

      <label for="site_description">Site Description:</label>
      <textarea id="site_description" name="site_description"><?php echo htmlspecialchars($site_description); ?></textarea>

      <label for="contact_email">Contact Email:</label>
      <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($contact_email); ?>">

      <button type="submit" name="update_settings">Update Settings</button>
      <?php
    if (isset($_GET['message'])) {
      echo '<p class="success">' . htmlspecialchars($_GET['message']) . '</p>';
    }
    ?>
    </form>
  </div>
</main>
</body>
</html>